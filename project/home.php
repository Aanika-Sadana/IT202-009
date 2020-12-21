<?php
require_once(__DIR__ . "/partials/nav.php");
?>

<?php

//use this to safely get the email to display
$email = "";

if(isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])){
	$email = $_SESSION["user"]["email"];
}

?>

<h2>Home</h2>

<p>Welcome, <?php echo $email; ?> </p>

<?php require(__DIR__ . "/partials/flash.php");

<style>
        *{
                text-align: center;
        }

	p{
		color: #5b6e85;
		font-family: Helvetica;
	}

        h2{
                color: #23354a;
                font-family: Helvetica;
        }

</style>

