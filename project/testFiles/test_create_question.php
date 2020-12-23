<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if(!has_role("Admin")){
	flash("You don't have permission to access this page");
	die(header("Location: login.php"));
}

$db = getDB();
$stmt = $db->prepare("SELECT *  from Survey LIMIT 10");
$r = $stmt->execute();
$survey = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

	<h3>Create Question</h3>
	<form method="POST">
		<label>Question</label>
		<input name="question" placeholder="Question"/>

		<label>Survey ID</label>
		<select name="survey_id"/>
                <?php foreach ($survey as $s): ?>
                <option value="<?php safer_echo($s["id"]); ?>"> <?php safer_echo($s["title"]); ?> </option>
            <?php endforeach; ?>
        </select>

        <input type="submit" name="save" value="Update"/>
    </form>



<?php
if(isset($_POST["save"])){
	$question = $_POST["question"];
        $survey_id = $_POST["survey_id"];
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Questions  (question, survey_id) VALUES(:question, :survey_id)");
    $r = $stmt->execute([
        ":question"=> $question,
        ":survey_id"=> $survey_id
    ]);
    if ($r) {
        flash("Created successfully with id: " . $db->lastInsertId());
    }
    else {
        $e = $stmt->errorInfo();
        flash("Error creating: " . var_export($e, true));
    }
}
?>
<?php require(__DIR__ . "/partials/flash.php");
