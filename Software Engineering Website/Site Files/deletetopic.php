<?php
include ('includes/session.php');
require_once ('mysqli_connect.php');


$q = "SELECT topic_id AS tid, topic_title AS title FROM topic WHERE topic_id ='{$_GET['topic']}'";
$topic_result = @mysqli_query($dbc, $q);
$topic = mysqli_fetch_array($topic_result, MYSQLI_ASSOC);


$page_title = $topic['title'];
include ('./includes/header.php');

echo '<div class="page-header">' .
        '<h3>Algorithms</h3>' .
    '</div>';

echo '<div class="well">Are you sure you would like to delete "' . $page_title . '"?';
echo '<br />This will also delete all solutions, and comments associated with this topic.';
echo '<br />Enter your administrator login credentials to confirm the deletion.';
echo '</div>';
?>


<form role="form" action="confirmdelete.php?topic=<?php echo $_GET['topic']; ?>" method="post">
    
    <p>Admin Username: <input type="normal" class="form-control" placeholder="Username" required autofocus name="username" maxlength="20" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" /></p>
    <p>Admin Password: <input type="password" class="form-control" placeholder="Password" required autofocus name="password" maxlength="20" value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>" /></p>
    <p><button type="submit" name="Delete Topic" class="btn btn-sm btn-primary" />Delete Topic</button></p>
    <input type="hidden" name="submitted" value="TRUE" />
    
</form>

<?php
include ('./includes/footer.html');
?>