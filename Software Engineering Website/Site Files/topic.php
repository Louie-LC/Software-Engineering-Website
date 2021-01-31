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



$set_page = filter_input(INPUT_GET, 'page');
$q = "SELECT topic_id AS id, topic_title AS title, topic_description AS description FROM topic WHERE topic_id='$set_page'";
$topic_result = @mysqli_query($dbc, $q); // Run the query.
$topic = mysqli_fetch_array($topic_result, MYSQLI_ASSOC);

//Set page title once you know the topic you're looking at
$page_title = $topic['title'];
include ('./includes/header.php');

$solution_page = filter_input(INPUT_GET, 'solset');
$solution_page--;
$solution_page *= 5;

$q = "SELECT solution_id AS sid, solution_title AS title FROM solution WHERE topic_id='{$topic['id']}' ORDER BY solution_id DESC LIMIT " . $solution_page . ", 5";
$r = @mysqli_query($dbc, $q);

$q = "SELECT solution_id FROM solution WHERE topic_id='{$topic['id']}'";
$all = @mysqli_query($dbc, $q);

$num = mysqli_num_rows($all);


echo '<div class="page-header">' .
        '<h3>Algorithms</h3>' .
    '</div>';

echo '<div class="well">';

//Print out topic info
echo '<div><b>'. $topic['title'] . '</b></div>';
echo  '<div class="well">' . $topic['description'] . '</div>';
echo '<div><b>Solutions:</b></div>';
echo '<div class="well">';

while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
{
    echo '<a href="solution.php?topic=' . $topic['id'] . '&solution=' . $row['sid'] . '&commentset=1">' .
            '<dt>' . $row['title'] . '</dt>' .
            '</a>';
            
}
echo '</div>';

//Logic for the "prev" navigation button
$solution_page = filter_input(INPUT_GET, 'solset');
if($solution_page == 1)
{
    echo '<< Prev ';
}
else
{
    $solution_page--;
    echo '<a href ="topic.php?page=' . $topic['id'] . '&solset=' . $solution_page . '"><< Prev </a>';
}

$solution_page = filter_input(INPUT_GET, 'solset');
$solution_page--;
$currentfive = floor($solution_page / 5);
$starting = $currentfive * 5;
$starting++;
$totalsets = ceil($num / 5);
for($i = $starting; $i < $starting + 5 && $i <= $totalsets; $i++)
{
    echo '<a href ="topic.php?page=' . $topic['id'] . '&solset=' . $i . '">' . $i . ' </a>';
}

//Logic for the "next" navigation button
$solution_page = filter_input(INPUT_GET, 'solset');
if($solution_page >= $num / 5)
{
    echo 'Next >>';
}
else
{
    $solution_page++;
    echo '<a href ="topic.php?page=' . $topic['id'] . '&solset=' . $solution_page . '">Next>></a>';
}

echo '</div>';

mysqli_free_result($topic_result);
mysqli_free_result($r);
mysqli_close($dbc);
?>

<?php
    //Displaying the buttons on the bottom of the page based on login information
    if(isset($_SESSION['user_id'])) {
        
        $topic = filter_input(INPUT_GET, 'page');
        echo '<form action="postsolution.php" method="get">';
        echo '<button class="btn btn-sm btn-primary" type="submit" name="submit">Add Solution</button>';
        echo '<input type="hidden" name="topic" value="' . $topic . '" />';
        echo '</form>';
        if($_SESSION['user_type_id'] == 1) {
            echo '<form action="deletetopic.php?topic=' . $_GET['page'] . '" method="post">';
            echo '<button class="btn btn-sm btn-primary" type="submit" name="submit">Delete Topic</button>';
            echo '</form>';
            
        }
    }
    
?>


<?php
include ('./includes/footer.html');
?>