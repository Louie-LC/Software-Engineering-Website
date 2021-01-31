<?php # Script 3.4 - index.php
include ('includes/session.php');
?>
<style>
    /* unvisited link */
a:link {
    color: black;
}

/* visited link */
a:visited {
    color: gray;
}

/* mouse over link */
a:hover {
    color: black;
}

/* selected link */
a:active {
    color: black;
}
</style>

<?php # Script 3.4 - index.php
require_once ('mysqli_connect.php');

$page_title = 'Algorithms and Solutions';
include ('./includes/header.php');

//First query only retrieving 5 results based on the page the user is on
$setpage = filter_input(INPUT_GET, 'page');
$setpage--;
$setpage *= 5;
$q = "SELECT topic_id AS id, topic_title AS title, topic_description AS description FROM topic ORDER BY topic_id DESC LIMIT " . $setpage . ", 5";		
$r = @mysqli_query($dbc, $q); // Run the query.

//Second query that sees how many total topics there are for displaying the navigation at the bottom of the page
$q = "SELECT topic_id FROM topic";
$all = @mysqli_query($dbc, $q);
$num = mysqli_num_rows($all);


echo '<div class="page-header">' .
        '<h3>Algorithms</h3>' .
    '</div>';
echo '<div class="well">' .
        '<dl>';
while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
{
    echo '<div class="well">' .
            '<a href="topic.php?page=' . $row['id'] . '&solset=1">' .
            '<dt>' . $row['title'] . '</dt>' .
            '</a>' .
            '<dd>' . $row['description'] . '</dd>'.
        '</div>';
            
}
echo '</div>';

echo '<div>View More: ';

//Lofic for the "prev" navigation button
$setpage = filter_input(INPUT_GET, 'page');
if($setpage == 1)
{
    echo '<< Prev ';
}
else
{
    $setpage--;
    echo '<a href ="algorithms.php?page=' . $setpage . '"><< Prev </a>';
}

$setpage = filter_input(INPUT_GET, 'page');
$setpage--;
$currentfive = floor($setpage / 5);
$starting = $currentfive * 5;
$starting++;
$totalsets = ceil($num / 5);
for($i = $starting; $i < $starting + 5 && $i <= $totalsets; $i++)
{
    echo '<a href ="algorithms.php?page=' . $i . '">' . $i . ' </a>';
}

//Logic for the "next" navigation button
$setpage = filter_input(INPUT_GET, 'page');
if($setpage >= $num / 5)
{
    echo 'Next >>';
}
else
{
    $setpage++;
    echo '<a href ="algorithms.php?page=' . $setpage . '">Next >></a>';
}

echo '</div>';

mysqli_free_result ($r);
mysqli_free_result ($all);
mysqli_close($dbc);
?>
<?php
    if(isset($_SESSION['user_id'])) {
        echo '
            <form action="posttopic.php" method="get">
        <button class="btn btn-sm btn-primary" type="submit" name="submit">Add Topic</button>
    </form>';
    }

?>
<?php
include ('./includes/footer.html');
?>