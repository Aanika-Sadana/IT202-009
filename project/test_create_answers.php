<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if(!has_role("Admin")){
        flash("You don't have permission to access this page");
        die(header("Location: login.php"));
}

$db = getDB();
$stmt = $db->prepare("SELECT *  from Questions LIMIT 10");
$r = $stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

        <h3>Create Answer</h3>
        <form method="POST">
                <label>Answer</label>
                <input name="answer" placeholder="Answer"/>

                <label>Question ID</label>
                <select name="question_id"/>
                <?php foreach ($questions as $q): ?>
                <option value="<?php safer_echo($q["id"]); ?>"> <?php safer_echo($q["question"]); ?> </option>
            <?php endforeach; ?>
        </select>

        <input type="submit" name="save" value="Update"/>
    </form>



<?php
if(isset($_POST["save"])){
        $answer = $_POST["answer"];
        $question_id = $_POST["question_id"];
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Answers  (answer, question_id) VALUES(:answer, :question_id)");
    $r = $stmt->execute([
        ":answer"=> $answer,
        ":question_id"=> $question_id
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




