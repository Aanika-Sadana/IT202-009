<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<h2>Login</h2>

<form method="POST">
    <label for="emailuser">Email/Username:</label><br>
    <input type="text" id="emailuser" name="emailuser" required/><br><br>
    <label for="p1">Password:</label><br>
    <input type="password" id="p1" name="password" required/><br><br>
    <input type="submit" id="login"  name="login" value="Login"/>
</form>

<?php

if (isset($_POST["login"])) {
    $entry = null;
    $email = null;
    $username = null;
    $password = null;
    $isEmail = false;

    if (isset($_POST["emailuser"])) {
        $entry = $_POST["emailuser"];

	if(strpos($entry, "@") !== false){
		$email = $entry;
		$isEmail = true;
	}
	else{
		$username = $entry;
		$isEmail = false;
	}
    }
    if (isset($_POST["password"])) {
        $password = $_POST["password"];
    }
    $isValid = true;

    if (!isset($entry) || !isset($password)) {
        $isValid = false;

	if(!isset($entry)){
		flash("Email/Username not entered");
	}
	if(!isset($password)){
		flash("Password not entered");
	}
    }


    if ($isValid) {
        $db = getDB();
        if (isset($db)) {


	    if($isEmail){
		$stmt = $db->prepare("SELECT id, email, username, password from Users WHERE email = :email LIMIT 1");
		$params = array(":email" => $email);
	    }

	    else{
		$stmt = $db->prepare("SELECT id, email, username, password from Users WHERE username = :username LIMIT 1");
		$params = array(":username" => $username);
	    }

            //$stmt = $db->prepare("SELECT id, email, username, password from Users WHERE email = :email LIMIT 1");
            //$params = array(":email" => $entry);

            $r = $stmt->execute($params);
            $e = $stmt->errorInfo();
            if ($e[0] != "00000") {
                //echo "uh oh something went wrong: " . var_export($e, true);
		flash("Something went wrong, please try again");
            }
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && isset($result["password"])) {
                $password_hash_from_db = $result["password"];
                if (password_verify($password, $password_hash_from_db)) {
                    $stmt = $db->prepare("
SELECT Roles.name FROM Roles JOIN UserRoles on Roles.id = UserRoles.role_id where UserRoles.user_id = :user_id and Roles.is_active = 1 and UserRoles.is_active = 1");
                    $stmt->execute([":user_id" => $result["id"]]);
                    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    unset($result["password"]);//remove password so we don't leak it beyond this page
                    //let's create a session for our user based on the other data we pulled from the table
                    $_SESSION["user"] = $result;//we can save the entire result array since we removed password
                    if ($roles) {
                        $_SESSION["user"]["roles"] = $roles;
                    }
                    else {
                        $_SESSION["user"]["roles"] = [];
                    }
                    //on successful login let's serve-side redirect the user to the home page.
                    	flash("Log in successful");
			die(header("Location: home.php"));
                }
                else {
                    flash("Invalid password!");
                }
            }
            else {
                flash("Invalid user");
            }
        }
    }
    else {
        flash("There was a validation issue");
    }
}
?>

<?php require(__DIR__ . "/partials/flash.php");

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

        #login{
                background-color: #b6c0cc;
                border: none;
                padding: 10px;
        }
</style>



