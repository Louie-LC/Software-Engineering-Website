<?php # Script 3.4 - index.php
include ('includes/session.php');

$page_title = 'Welcome to this Site!';
include ('./includes/header.php');
?>
<div class="page-header">
    <h1>Computer Science Centeral</h1>
</div>
<div class="well">
    <p>Welcome to Computer Science Central! Don't forget to create an account and login.</p>
</div>
<div class="well">
<a href="README.txt" target="_blank">Readme File</a>
</br>
<a href="Project Presentation.pptx" targe="_blank">Project Presentation File</a>
</div>
<?php
include ('./includes/footer.html');
?>