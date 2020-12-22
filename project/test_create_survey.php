<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if(!has_role("Tester")){
    //redirects to login and kills the rest of script (prevents it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

<form method="POST">
    <label>Title</label>
    <input name="title" placeholder="Title"/>

    <label>Description</label>
    <input name="description" placeholder="Description"/>

    <label>Visibility</label>
    <select name="visibility">
        <option value="0">Draft</option>
        <option value="1">Private</option>
        <option value="2">Public</option>
    </select>

    <input type="submit" name="save" value="Create"/>
</form>

<?php
if(isset($_POST["save"])){
    //add proper validation/checks
    $title = $_POST["title"];
    $description = $_POST["description"];
    $visibility = $_POST["visibility"];
    $createDate = date('Y-m-d H:i:s');
    $modDate = date('Y-m-d H:i:s');
    $user = get_user_id();
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Survey (title, description, visibility, created, modified, user_id) VALUES(:title, :description, :visibility, :createDate, :modDate, :user)");
    $r = $stmt->execute([
        ":title"=>$title,
        ":description"=>$description,
        ":visibility"=>$visibility,
        ":createDate"=>$createDate,
        ":modDate"=>$modDate,
        ":user"=>$user
    ]);

    if($r){
        flash("Created successfully with id: " . $db->lastInsertId());
    }
    else{
        $e = $stmt->errorInfo();
        flash("Error creating: " . var_export($e, true));
    }

}

?>

<?php require(__DIR__ . "/partials/flash.php");?>
