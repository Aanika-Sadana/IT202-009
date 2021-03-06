er. Keep an eye out for those patterns.

<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}

$sID = $_GET["id"];

$page = 1;
$per_page = 10;

if(isset($_GET["page"])){
	try{
		$page = (int)$_GET["page"];
	}
	catch(Exception $e){
	}
}

$db = getDB();
$stmt->prepare("SELECT count(*) as total FROM  Questions as Questions JOIN Surveys ON Questions.survey_id = Surveys.id WHERE Surveys.id = :q");
$stmt->execute([":q"=>$sID]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total = 0;

if($result){
     $total = (int)$result["total"];
}

$total_pages = ceil($total / $per_page);
$offset = ($page-1) * $per_page;

$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":id", get_user_id());
$stmt->execute();
$e = $stmt->errorInfo();
if($e[0] !- "00000"){
	flash(var_export($e, true), "alert");
}
$results = $stmt->fetchAll(PDO::FETCH_ASSOC); 



?>
<?php
//get latest 10 surveys we haven't take
$db = getDB();
$stmt = $db->prepare("SELECT id, name FROM Surveys WHERE (SELECT count(1) from Responses where user_id = :id and survey_id = Surveys.id) = 0 order by created desc LIMIT 10");
$r = $stmt->execute([":id" => get_user_id()]);
if ($r) {
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching surveys: " . var_export($stmt->errorInfo(), true), "danger");
}



$count = 0;
if (isset($results)) {
    $count = count($results);
}
?>
<div class="container-fluid">
    <h3>Surveys (<?php echo $count; ?>)</h3>
    <?php if (isset($results) && $count > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $s): ?>
                <div class="list-group-item">
                    <div class="row">
                        <div class="col-8"><?php safer_echo($s["name"]); ?></div>
                        <div class="col">
                            <a type="button" class="btn btn-success"
                               href="<?php echo getURL("survey.php?id=" . $s["id"]); ?>">
                                Take Survey
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No surveys available</p>
    <?php endif; ?>
</div>
<?php require(__DIR__ . "/partials/flash.php"); ?>
