<?php
include ('includes/session.php');
require_once ('mysqli_connect.php');

$page_title = 'Post Topic';
include ('./includes/header.php');

if(isset($_POST['submitted'])) {
    $errors = array();
    
    if(empty($_POST['topic_title'])) {
        $errors[] = 'You forgot to enter a title for the topic.';
    }
    else {
        $title = mysqli_real_escape_string($dbc, trim($_POST['topic_title']));
    }
    
    if(empty($_POST['topic_description'])) {
        $errors[] = 'You forgot to enter a desciption for the topic.';
    }
    else {
        $description = mysqli_real_escape_string($dbc, trim($_POST['topic_description']));
    }
    
    if(empty($errors)) {
        $q = "INSERT INTO topic (topic_id, topic_title, topic_description) VALUES (null, '$title', '$description')";
        $r = @mysqli_query($dbc, $q);
        if($r) {
                echo '<h1>Thank you!</h1>
                <p>Your topic is now posted!</p><p><br /></p>';	
           
        }
        else {
                echo '<h1>System Error</h1>
                <p class="error">Your topic could not be posted due to a system error. We apologize for any inconvenience.</p>'; 

                // Debugging message:
                echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';            
        }
        
    }
    else {
        echo '<h1>Error!</h1>
        <p class="error">The following error(s) occurred:<br />';
        foreach($errors as $msg) {
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p><p><br /></p>';
    }
}




echo '<div class="page-header">' .
        '<h3>Post New Topic</h3>' .
    '</div>';
echo '<div class="well">';
?>

<form role="form" action="posttopic.php" method="post">
    
    <p>Topic Title: <input type="normal" class="form-control" placeholder="The Topic's Title" required autofocus name="topic_title" maxlength="50" value="<?php if (isset($_POST['topic_title'])) echo $_POST['topic_title']; ?>" /></p>
    <p>Description of Topic: <textarea class="form-control" placeholder="Descrption of the Topic" required name="topic_description" maxlength="10000" rows="10" cols="40" value="<?php if (isset($_POST['topic_description'])) echo $_POST['topic_description']; ?>"></textarea></p>
    <p><button type="submit" name="Post Topic" class="btn btn-sm btn-primary" />Post Topic</button></p>
    <input type="hidden" name="submitted" value="TRUE" />
    
</form>

<?php
include ('./includes/footer.html');
?>