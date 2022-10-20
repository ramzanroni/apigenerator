<?php
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (array_key_exists("id", $_GET)) {
        $id = $_GET["id"];
        if ($id === "") {
            $response = new Response();
            $response->etHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("id cannot be blank");
            $response->send();
            exit();
        }
        $tasksSQL = $readDB->prepare("SELECT * FROM tasks WHERE id=:id");
        $tasksSQL->bindParam(":id", $id, PDO::PARAM_STR);
    } else {
        $tasksSQL = $readDB->prepare("SELECT * FROM tasks");
    }
    $tasksSQL->execute();
    $rowCount = $tasksSQL->rowCount();
    if ($rowCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage("Data not found");
        $response->send();
        exit();
        $tasksArray = array();
        while ($row = $tasksSQL->fetch(PDO::FETCH_ASSOC)) {
        }
    }
}
