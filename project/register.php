<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (isset($_POST["register"])) {
    	$email = null;
    	$password = null;
    	$confirm = null;
    	$username = null;

    	if (isset($_POST["email"])) {
        	$email = $_POST["email"];
   	 }

    	if (isset($_POST["password"])) {
        	$password = $_POST["password"];
    	}

    	if (isset($_POST["confirm"])) {
        	$confirm = $_POST["confirm"];
    	}

    	if (isset($_POST["username"])) {
        	$username = $_POST["username"];
    	}
    	$isValid = true;

    	//check if passwords match on the server side
    	if ($password == $confirm) {
    	}

    	else {
        	flash("Passwords don't match");
        	$isValid = false;
    	}

    	if (!isset($email) || !isset($password) || !isset($confirm)) {
        	$isValid = false;
    	}

    	//TODO other validation as desired, remember this is the last line of defense
    	if ($isValid) {
        	$hash = password_hash($password, PASSWORD_BCRYPT);
        	$db = getDB();

        	if (isset($db)) {
            		//here we'll use placeholders to let PDO map and sanitize our data
            		$stmt = $db->prepare("INSERT INTO Users(email, username, password) VALUES(:email,:username, :password)");

            		//here's the data map for the parameter to data
            		$params = array(":email" => $email, ":username" => $username, ":password" => $hash);
            		$r = $stmt->execute($params);

            		$e = $stmt->errorInfo();

            		if ($e[0] == "00000") {
                		flash("Successfully registered, please login.");
            		}

            		else {
                		if ($e[0] == "23000") { //code for duplicate entry
                   	 	flash("Either username or email is already registered, please try again");
                	}

                	else {
                    		flash("uh oh something went wrong");
                	}
            	}
        }
}

else {
	flash("There was a validation issue");
}
}

//safety measure to prevent php warnings
if (!isset($email)) {
    	$email = "";
}

if (!isset($username)) {
    	$username = "";
}
?>

<h2>Register</h2>

<form method="POST">
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required value="<?php safer_echo($email); ?>"/><br><br>
    <label for="user">Username:</label><br>
    <input type="text" id="user" name="username" required maxlength="60" value="<?php safer_echo($username); ?>"/><br><br>
    <label for="p1">Password:</label><br>
    <input type="password" id="p1" name="password" required/><br><br>
    <label for="p2">Confirm Password:</label><br>
    <input type="password" id="p2" name="confirm" required/><br><br>
    <input type="submit" id="submit" name="register" value="Register"/>
</form>

<?php require(__DIR__ . "/partials/flash.php");?>

<style>
	*{
		text-align: center;
	}

	label{
		color: #5b6e85;
		font-family: Helvetica;
	}

	h2{
		color: #23354a;
		font-family: Helvetica;
	}

	#submit{
		background-color: #b6c0cc;
		border: none;
		padding: 10px;
	}
</style>
