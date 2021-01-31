<?php
include ('includes/session.php');
require_once ('mysqli_connect.php');

$is_successful = false;
$errors = array();
$username = $_POST['username'];
$password = $_POST['password'];
$q = "SELECT user_type_id AS type, pass FROM users WHERE username='$username'";
$r = @mysqli_query($dbc, $q);
$user = mysqli_fetch_array($r, MYSQLI_ASSOC);

if(empty($user)){
    $errors[] = 'Invalid username.';
}
else if($user['type'] != 1) {
    $errors[] = 'The supplied account is not an admin account.';
}
else {
    if(sha1($password) == $user['pass']) {
        $is_successful = true;
        $topic_ID = $_GET['topic'];
        
        //This grabs all solutions for each topic
        $q = "SELECT solution_id AS sid FROM solution WHERE topic_id ='$topic_ID'";
        $solution_set = @mysqli_query($dbc, $q);
        
        while($solution_row = mysqli_fetch_array($solution_set, MYSQLI_ASSOC)) {
            //This should grab all comments from each solution.
            $q = "SELECT comment_id as cid FROM comment WHERE solution_id='{$solution_row['sid']}'";
            $comment_set = @mysqli_query($dbc, $q);
            
            while($comment_row = mysqli_fetch_array($comment_set, MYSQLI_ASSOC)) {
                $q = "DELETE FROM posted_to WHERE comment_id='{$comment_row['cid']}'";
                $delete_result = @mysqli_query($dbc, $q);
                
                $q = "DELETE FROM comment WHERE comment_id='{$comment_row['cid']}'";
                $delete_result = @mysqli_query($dbc, $q);
            }
            
            $q = "DELETE FROM solution WHERE solution_id='{$solution_row['sid']}'";
            $delete_result = @mysqli_query($dbc, $q);
        }
        
        $q = "DELETE FROM topic WHERE topic_id='$topic_ID'";
        $delete_result = @mysqli_query($dbc, $q);        
        
        mysqli_free_result($solution_set);
    }
    else {
        $errors[] = "The password supplied was incorrect.";
    }
}

$page_title = "Delete Topic Confirmation";
include ('./includes/header.php');

if(!empty($errors)) {
    foreach($errors as $one) {
        echo "$one <br />";
    }
}

echo '<div class="page-header">' .
        '<h3>Algorithms</h3>' .
    '</div>';

if($is_successful) {
    echo '<div class="well">The topic was deleted.';
    echo '</div>';
}
else {
    echo '<div class="well">The topic could not be deleted. Please supply the correct credentials and try again.';
    echo '</div>';

    echo '<form action="deletetopic.php?topic=' . $_GET['topic'] . '" method="post">';
    echo '<button class="btn btn-sm btn-primary" type="submit" name="submit">Return</button>';
    echo '</form>';    
}


?>

<?php
include ('./includes/footer.html');
?>