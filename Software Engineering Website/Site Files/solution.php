<?php # Script 3.4 - index.php
include ('includes/session.php');
?>
<style>
table {
    border-collapse: collapse;
    width: 100%;
    
}

table, th, td {
    border: 1px solid black;
    background-color: white;
    word-wrap: break-word;
    word-break:break-all;
} 

th {
    background-color: lightgray;
}

td {
    height: 50px;
    vertical-align: top;
}
    
</style>

<?php
require_once ('mysqli_connect.php');

$error = null;
$dbc_error = null;
$dbc_success = null;
if(isset($_POST['submitted'])) {
    if(empty($_POST['comment'])) {
        $error = 'No text entered in the comment field, please try again.';
    }
    else {
        $comment = mysqli_real_escape_string($dbc, trim($_POST['comment']));
    }
    
    if($error == null) {


        $q = "INSERT INTO comment (solution_id, comment_text) VALUES ('{$_GET['solution']}', '$comment')";
        $r = @mysqli_query($dbc, $q);
        if($r) {
            //Retrieve the comment id of the comment just added to the database
            $comment_id = mysqli_insert_id($dbc);

            $q = "INSERT INTO posted_to (comment_id, user_id) VALUES ('$comment_id', '{$_SESSION['user_id']}')";
            $r = @mysqli_query($dbc, $q);
            
            if($r) {
                $dbc_success = "Comment posted succesfully";
            }
            else {
                $dbc_error = '<h1>System Error</h1>
                <p class="error">Your post could not be added due to a system error. We apologize for any inconvenience.</p>'; 
            }
        }
        else {
            $dbc_error = '<h1>System Error</h1>
                <p class="error">Your comment could not be posted due to a system error. We apologize for any inconvenience.</p>'; 
        }

    }
    
}



$set_solution = filter_input(INPUT_GET, 'solution');

$q = "SELECT solution_id AS id, solution_title AS title, source_code AS source, file_name as file FROM solution WHERE solution_id='$set_solution'";
$solution_results = @mysqli_query($dbc, $q);
$solution = mysqli_fetch_array($solution_results, MYSQLI_ASSOC);

$q = "SELECT comment_id FROM comment WHERE solution_id='{$solution['id']}'";
$all_comments = @mysqli_query($dbc, $q);
$num = mysqli_num_rows($all_comments);

$comment_page = filter_input(INPUT_GET, 'commentset');
$comment_page--;
$comment_page *= 5;

$q = "SELECT comment_id AS cid, comment_text AS text FROM comment WHERE solution_id='{$solution['id']}' ORDER BY comment_id ASC LIMIT " . $comment_page . ", 5";
$comment_r = @mysqli_query($dbc, $q);

//Set page title once you know the solution you're looking at
$page_title = $solution['title'];
include ('./includes/header.php');

if($error != null) {
    
    echo $error ;
}
if($dbc_error != null) {
    echo $dbc_error;
}
if($dbc_success != null) {
    echo $dbc_success;
}
echo '<div class="page-header">' .
        '<h3>Algorithms</h3>' .
    '</div>';

echo '<div class="well">';
//Print out solution info
echo '<div><b>'. $solution['title'] . '</b></div>';

if($solution['source'] != null) {
    echo '<div>';
    echo '<pre>';
    echo htmlspecialchars($solution['source']);
    echo '</pre>';
    echo '</div>';    
}
if($solution['file'] != null) {
    echo '<div>';
    echo '<a href="uploads/' . $solution['file']. '" target="_blank">Retrieve File Containing Code</a>';
    echo '</div>';
}
echo '</div>';

//Start of comment section

echo '<div><b>Comments:</b></div>';
echo '<div class="well">';
echo '<table>';
while($row = mysqli_fetch_array($comment_r, MYSQLI_ASSOC))
{
    $q = "SELECT user_id AS uid, date_posted AS timestamp  FROM posted_to WHERE comment_id='{$row['cid']}'";
    $posted_result = @mysqli_query($dbc, $q);
    $posted_info = mysqli_fetch_array($posted_result, MYSQLI_ASSOC);
    
    $q = "SELECT username FROM users WHERE user_id='{$posted_info['uid']}'";
    $user_result = @mysqli_query($dbc, $q);
    $user_info = mysqli_fetch_array($user_result, MYSQLI_ASSOC);
    
    echo '<tr>';
    echo '<th>' . $user_info['username'] . '</th>';
    echo '<th>' . $posted_info['timestamp'] . '</th>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<td colspan="2">' . $row['text'] . '</td>';
    echo '</tr>';
    
}
echo '</table>';
echo '</div>';




//Logic for the "prev" navigation button
$comment_page = filter_input(INPUT_GET, 'commentset');
if($comment_page == 1)
{
    echo '<< Prev ';
}
else
{
    $comment_page--;
    echo '<a href ="solution.php?topic=' . $_GET['topic'] . '&solution=' . $_GET['solution'] . '&commentset=' . $comment_page . '"><< Prev </a>';
}

$comment_page = filter_input(INPUT_GET, 'commentset');
$comment_page--;
$currentfive = floor($comment_page / 5);
$starting = $currentfive * 5;
$starting++;
$totalsets = ceil($num / 5);
for($i = $starting; $i < $starting + 5 && $i <= $totalsets; $i++)
{
    echo '<a href ="solution.php?topic=' . $_GET['topic'] . '&solution=' . $_GET['solution'] . '&commentset=' . $i . '">' . $i . ' </a>';
}

//Logic for the "next" navigation button
$comment_page = filter_input(INPUT_GET, 'commentset');
if($comment_page >= $num / 5)
{
    echo 'Next >>';
}
else
{
    $comment_page++;
    echo '<a href ="solution.php?topic=' . $_GET['topic'] . '&solution=' . $_GET['solution'] . '&commentset=' . $comment_page . '">Next >></a>';
}

echo '</div>';






/*To Do List
 * Code in functionality for changing comment page
 */
mysqli_free_result($solution_results);
mysqli_free_result($all_comments);
mysqli_free_result($comment_r);
?>
<?php 
if(isset($_SESSION['user_id'])) {
    $action = 'solution.php?topic=' . $_GET['topic'] . '&solution=' . $_GET['solution'] . '&commentset=' . $_GET['commentset'];
    echo '<form action="' . $action . '" method="POST">';

    echo 'Enter a comment:<textarea class="form-control" placeholder="Comment" name="comment" maxlength="10000" rows="10" cols="40" value="';
    if (isset($_POST['comment'])) {
        echo $_POST['comment'];
    }
    echo '"></textarea>';
    
    echo '<button type="submit" name="post comment" class="btn btn-sm btn-primary" />Post Comment</button>';
    echo '<input type="hidden" name="submitted" value="TRUE" />';
    echo '</form>';
}       
?>
   
<?php
include ('./includes/footer.html');
?>