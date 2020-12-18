<?php

//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");

?>


<a href="home.php">Home</a>
<?php if (!is_logged_in()): ?>
<a href="login.php">Login</a>
<a href="register.php">Register</a>
<?php endif; ?>
<?php if (is_logged_in()): ?>
<a href="profile.php">Profile</a>
<a href="logout.php">Logout</a>
<?php endif; ?>


<style>
	a{
		margin: 10px;
		font-family: Helvetica;
	}
</style>
