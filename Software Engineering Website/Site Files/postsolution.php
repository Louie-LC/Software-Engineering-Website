<?php
include ('includes/session.php');
require_once ('mysqli_connect.php');

$page_title = 'Post Solution';
include ('./includes/header.php');

if(isset($_POST['submitted'])) {
    $errors = array();
    
    if(empty($_POST['solution_title'])) {
        $errors[] = 'You forgot to enter a title for the solution.';
    }
    else {
        $title = mysqli_real_escape_string($dbc, trim($_POST['solution_title']));
    }
    if(empty($_POST['source_code']) && ($_FILES['code_file']['error'] > 0)) {
        $errors[] = 'You need to submit a solution using at least one of the available options.';
    }
    else {
        if(isset($_POST['source_code'])) {
            $sc = $_POST['source_code'];
            $source = mysqli_real_escape_string($dbc, $sc);
        }
        else {
            $source = null;
        }
        if($_FILES['code_file']['error'] == 0) {
            $file_parts = pathinfo($_FILES['code_file']['name']);
            
            switch($file_parts['extension'])
            {
                case "cpp":
                case "txt":
                case "java":
                    $file = rand(1000, 100000). "-" . $_FILES['code_file']['name'];
                    $file_loc = $_FILES['code_file']['tmp_name'];
                    $folder="uploads/";
                    if(move_uploaded_file($file_loc,$folder.$file))
                    {
                        echo 'Move_Uploaded_File Sucess';
                    }
                    else
                    {
                        echo 'Move_Uploaded_File failed';
                    }
                break;
            
                default:
                    $errors[] = 'The file you uploaded was not of type: .cpp, .java, or .txt';
                
            }

        }
        else {
            $file = null;
        }   
    }
    
    if(empty($errors)) {
        $topic = $_REQUEST['topic'];
        if($file == null) {
            $q = "INSERT INTO solution (topic_id, solution_title, source_code) VALUES ('$topic', '$title', '$source')";            
        }
        else if($source == null) {
            $q = "INSERT INTO solution (topic_id, solution_title, file_name) VALUES ('$topic', '$title', '$file')";
        }
        else {
            $q = "INSERT INTO solution (topic_id, solution_title, source_code, file_name) VALUES ('$topic', '$title', '$source', '$file')";
        }
        
        $r = @mysqli_query($dbc, $q);
        if($r) {
                echo '<h1>Thank you!</h1>
                <p>Your solution is now posted!</p><p><br /></p>';	
           
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
        '<h3>Post New Solution</h3>' .
    '</div>';
echo '<div class="well">';
?>

<form role="form" action="postsolution.php" method="post" enctype="multipart/form-data">
    
    <p>Solution Title:<input type="normal" class="form-control" placeholder="The Solution's Title" required autofocus name="solution_title" maxlength="50" value="<?php if (isset($_POST['solution_title'])) echo $_POST['solution_title']; ?>" /></p>
    <div>Upload the solution, either as a file, as source code, or both.</div>
    <?php 
        $topic = $_REQUEST['topic'];
        echo '<input type="hidden" name="topic" value="' . $topic . '" />';
    ?>
    <p>As Source Code: <textarea class="form-control" placeholder="Source code for solution" name="source_code" maxlength="10000" rows="10" cols="40" value="<?php if (isset($_POST['source_code'])) echo $_POST['source_code']; ?>"></textarea></p>
    <p>As a File: <input type="file" name="code_file">
    <p><button type="submit" name="Post Solution" class="btn btn-sm btn-primary" />Post Solution</button></p>
    <input type="hidden" name="submitted" value="TRUE" />
    
    
    
</form>

<?php
include ('./includes/footer.html');
?>