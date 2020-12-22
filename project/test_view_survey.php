<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
//we'll put this at the top so both php block have access to it
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<?php
//fetching
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT title,description,visibility,created,modified FROM Survey WHERE Survey.id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>
<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-title">
            <?php safer_echo($result["title"]); ?>
        </div>
        <div class="card-body">
            <div>
                <p>Info</p>
                <div>Title: <?php safer_echo($result["title"]); ?></div>
                <div>Description: <?php safer_echo($result["description"]); ?></div>
                <div>Visibility: <?php safer_echo($result["visibility"]); ?></div>
                <div>Create Date: <?php safer_echo($result["created"]); ?></div>
                <div>Modify Date: <?php safer_echo($result["modified"]); ?></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php");
