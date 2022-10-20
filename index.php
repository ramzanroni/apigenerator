<?php
$requiredData = array(
    "className" => "Student",
    "tableName" => "students",
    "columnName" => [array(
        'name' => 'id',
        'type' => 'int'
    ), array(
        'name' => 'name',
        'type' => 'varchar'
    ), array(
        'name' => 'school',
        'type' => 'varchar'
    ), array(
        'name' => 'phone',
        'type' => 'varchar'
    ), array(
        'name' => 'address',
        'type' => 'varchar'
    ), array(
        'name' => 'status',
        'type' => 'varchar'
    )],
    "primaryKey" => 'id',
    "get" => true,
    "post" => true,
    "delete" => true,
    "put" => true
);
$className = $requiredData['className'];
$tableName = $requiredData['tableName'];
$colNames = $requiredData['columnName'];
$primaryKey = $requiredData['primaryKey'];
$getMethod = $requiredData['get'];
$postMethod = $requiredData['post'];
$deleteMethod = $requiredData['delete'];
$updateMethod = $requiredData['put'];
// exception classs 
echo 'class ' . $className . 'Exception extends Exception{}<br>';
// make class 
echo 'class ' . $className . '{<br>';
foreach ($colNames as $colName) {
    echo 'private $_' . $colName['name'] . ';<br>';
}

echo 'public function __construct(';
$countCol = count($colNames);
$counter = 0;
foreach ($colNames as $colName) {
    $counter++;
    if ($counter == $countCol) {
        echo '$' . $colName['name'];
    } else {
        echo '$' . $colName['name'] . ',';
    }
}
echo ')<br>';
echo '{<br>';
foreach ($colNames as $colName) {
    echo '$this->set' . ucfirst($colName['name']) . '($' . $colName['name'] . ');<br>';
}
echo '}<br>';
foreach ($colNames as $colName) {
    echo 'public function set' . ucfirst($colName['name']) . '($' . $colName['name'] . '){<br> $this->_' . $colName['name'] . '=$' . $colName['name'] . ';}<br>';
    echo 'public function get' . ucfirst($colName['name']) . '(){<br>return $this->_' . $colName['name'] . ';<br>}<br>';
}
echo 'public function return' . $className . 'Array(){<br>';
echo '$' . $tableName . '=array();<br>';
foreach ($colNames as $colName) {
    echo '$' . $tableName . '[' . '"' . $colName['name'] . '"]=$this->get' . ucfirst($colName['name']) . '();<br>';
}
echo 'return $' . $tableName . ';<br>';
echo '}<br>';
echo '}<br>';


echo "controller file<br>";

echo 'include db connection, model, response file<br><br><br><br><br><br><br>';


echo '//get data<br>';
if ($getMethod == true) {
    echo 'if($_SERVER["REQUEST_METHOD"]==="GET"){<br>';
    echo 'try{<br>';
    echo 'if(array_key_exists("' . $primaryKey . '", $_GET)){<br>';
    echo '$' . $primaryKey . '=$_GET["' . $primaryKey . '"];<br>';
    echo 'if($' . $primaryKey . '===""){<br>';
    echo '$response=new Response();<br>';
    echo '$response->setHttpStatusCode(400);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage("' . $primaryKey . ' cannot be blank");<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';


    echo '$' . $tableName . 'SQL=$readDB->prepare("SELECT * FROM ' . $tableName . ' WHERE ' . $primaryKey . '=:' . $primaryKey . '");<br>';
    echo '$' . $tableName . 'SQL->bindParam(":' . $primaryKey . '", $' . $primaryKey . ', PDO::PARAM_STR);<br>';
    echo '}else{<br>';
    echo '$' . $tableName . 'SQL=$readDB->prepare("SELECT * FROM ' . $tableName . '");<br>';
    echo '}<br>';
    echo '$' . $tableName . 'SQL->execute();<br>';
    echo '$rowCount = $' . $tableName . 'SQL->rowCount();<br>';
    echo 'if($rowCount===0){<br>';
    echo ' $response = new Response();<br>';
    echo '$response->setHttpStatusCode(404);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage("Data not found");<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';
    echo '$' . $tableName . 'Array = array();<br>';
    echo 'while($row=$' . $tableName . 'SQL->fetch(PDO::FETCH_ASSOC)){<br>';
    echo '$' . $tableName . 'Data= new ' . $className . '(';
    $counter = 0;
    foreach ($colNames as $colName) {
        $counter++;
        if ($counter == $countCol) {
            echo '$row["' . $colName['name'] . '"]';
        } else {
            echo '$row["' . $colName['name'] . '"],';
        }
    }
    echo ');<br>';
    echo '$' . $tableName . 'Array[]=$' . $tableName . 'Data->return' . $className . 'Array();<br>';
    echo '}<br>';
    echo ' $returnData = array();<br>';
    echo '$returnData["rows_returned"] = $rowCount;<br>';
    echo '$returnData["' . $tableName . '"] = $' . $tableName . 'Array;<br>';
    echo '$response = new Response();<br>';
    echo '$response->setHttpStatusCode(200);<br>';
    echo '$response->setSuccess(true);<br>';
    echo '$response->toCache(true);<br>';
    echo '$response->setData($returnData);';
    echo '$response->send();<br>';
    echo 'exit;<br>';
    echo '} catch(' . $className . 'Exception $ex) {<br>';
    echo '$response = new Response();<br>';
    echo '$response->setHttpStatusCode(500);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage($ex->getMessage());<br>';
    echo '$response->send();<br>';
    echo 'exit;<br>';
    echo '} catch (PDOException $ex) {<br>';
    echo 'error_log("Database query error - " . $ex, 1);<br>';
    echo '$response = new Response();<br>';
    echo '$response->setHttpStatusCode(500);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage($ex->getMessage());<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';
    // echo 'if(array_key_exists("' . $primaryKey . '", $_GET)){<br>';
    // echo '$' . $primaryKey . '=$_GET["' . $primaryKey . '"];<br>';
    echo "}<br>";
}
echo '//Post data<br>';
if ($postMethod == true) {
    echo 'elseif ($_SERVER["REQUEST_METHOD"] === "POST") {<br>';
    echo 'try{<br>';
    echo 'if($_SERVER["CONTENT_TYPE"] !== "application/json") {<br>';
    echo '$response = new Response();<br>';
    echo '$response->setHttpStatusCode(400);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage("Content type header is not set to JSON");<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';
    echo '$rawPostData = file_get_contents("php://input");<br>';
    echo 'if (!$jsonData = json_decode($rawPostData)) {<br>';
    echo '$response = new Response();<br>';
    echo '$response->setHttpStatusCode(400);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage("Request body is not valid JSON");<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';

    echo '$' . $tableName . '=new ' . $className . '("",';
    $counter = 0;
    foreach ($colNames as $colName) {
        $counter++;
        if ($colName['name'] != $primaryKey) {
            if ($counter == $countCol) {
                echo '$jsonData->' . $colName['name'];
            } else {
                echo '$jsonData->' . $colName['name'] . ',';
            }
        }
    }
    echo ');<br>';
    foreach ($colNames as $colName) {
        echo '$' . $colName['name'] . '=$' . $tableName . '->get' . ucfirst($colName['name']) . '();<br>';
    }
    echo '$' . $tableName . 'InsertSQL= $writeDB->prepare("INSERT INTO ' . $tableName . ' (';
    $counter = 0;
    foreach ($colNames as $colName) {
        $counter++;
        if ($colName['name'] != $primaryKey) {
            if ($counter == $countCol) {
                echo $colName['name'];
            } else {
                echo $colName['name'] . ',';
            }
        }
    }
    echo ') VALUES (';
    $counter = 0;
    foreach ($colNames as $colName) {
        $counter++;
        if ($colName['name'] != $primaryKey) {
            if ($counter == $countCol) {
                echo ':' . $colName['name'];
            } else {
                echo ':' . $colName['name'] . ',';
            }
        }
    }
    echo ')");<br>';
    foreach ($colNames as $colName) {
        if ($colName['name'] != $primaryKey) {
            echo '$' . $tableName . 'InsertSQL->bindParam(":' . $colName['name'] . '", $' . $colName['name'] . ', PDO::PARAM_STR);<br>';
        }
    }
    echo '$' . $tableName . 'InsertSQL->execute();<br>';
    echo '$rowCount = $' . $tableName . 'InsertSQL->rowCount();<br>';
    echo 'if ($rowCount === 0) {<br>';
    echo ' $response = new Response();<br>';
    echo '$response->setHttpStatusCode(500);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage("Insert operation failed...");<br>';
    echo '$response->send();<br>';
    echo ' exit();<br>';
    echo '}<br>';
    echo ' if ($rowCount) {<br>';
    echo '$response = new Response();<br>';
    echo '$response->setHttpStatusCode(200);<br>';
    echo '$response->setSuccess(true);<br>';
    echo '$response->toCache(true);<br>';
    echo '$response->addMessage("Insert data Successfully");<br>';

    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';

    echo '} catch (' . $className . 'Exception $ex) {<br>';
    echo '$response = new Response();<br>';
    echo '$response->setHttpStatusCode(400);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage($ex->getMessage());<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '} catch (PDOException $ex) {<br>';
    echo 'error_log("Database query error - " . $ex, 1);<br>';
    echo '$response = new Response();<br>';
    echo '$response->setHttpStatusCode(500);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage($ex->getMessage());<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';

    echo '}<br>';
}
if ($deleteMethod == true) {
    echo ' elseif ($_SERVER["REQUEST_METHOD"] === "DELETE") {<br>';
    echo '$' . $primaryKey . '=$_GET["' . $primaryKey . '"];<br>';
    echo 'if($' . $primaryKey . '===""){<br>';
    echo '$response=new Response();<br>';
    echo '$response->setHttpStatusCode(400);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage("' . $primaryKey . ' cannot be blank");<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';

    echo 'try {<br>';
    echo '$checkData=$readDB->prepare("SELECT * FROM ' . $tableName . ' WHERE ' . $primaryKey . '=:' . $primaryKey . '");<br>';
    echo '$checkData->bindParam(":' . $primaryKey . '", $' . $primaryKey . ',PDO::PARAM_STR);<br>';
    echo '$checkData->execute();<br>';
    echo '$rowCount=$checkData->rowCount();<br>';
    echo 'if($rowCount===0){<br>';
    echo ' $response = new Response();<br>';
    echo '$response->setHttpStatusCode(404);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage("Data not found");<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';
    echo '//delete data<br>';
    echo '$deleteSQL=$writeDB->prepare("DELETE FROM ' . $tableName . ' WHERE ' . $primaryKey . '=:' . $primaryKey . '");<br>';
    echo '$deleteSQL->bindParam(":' . $primaryKey . '", $' . $primaryKey . ',PDO::PARAM_STR);<br>';
    echo '$deleteSQL->execute();<br>';
    echo '$rowCount=$deleteSQL->rowCount();<br>';
    echo 'if($rowCount!=0){<br>';
    echo ' $response = new Response();<br>';
    echo '$response->setHttpStatusCode(200);<br>';
    echo '$response->setSuccess(true);<br>';
    echo '$response->addMessage("Delete data successfully");<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';
    echo '} catch (' . $className . 'Exception $ex) {<br>';
    echo '$response = new Response();<br>';
    echo '$response->setHttpStatusCode(400);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage($ex->getMessage());<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '} catch (PDOException $ex) {<br>';
    echo 'error_log("Database query error - " . $ex, 1);<br>';
    echo '$response = new Response();<br>';
    echo '$response->setHttpStatusCode(500);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage($ex->getMessage());<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';
    echo '}<br>';
}
if ($updateMethod == true) {
    echo 'elseif ($_SERVER["REQUEST_METHOD"] === "PUT") {<br>';
    echo 'try{<br>';
    echo 'if($_SERVER["CONTENT_TYPE"] !== "application/json") {<br>';
    echo '$response = new Response();<br>';
    echo '$response->setHttpStatusCode(400);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage("Content type header is not set to JSON");<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';
    echo '$rawPostData = file_get_contents("php://input");<br>';
    echo 'if (!$jsonData = json_decode($rawPostData)) {<br>';
    echo '$response = new Response();<br>';
    echo '$response->setHttpStatusCode(400);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage("Request body is not valid JSON");<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';

    echo '$' . $tableName . '=new ' . $className . '(';
    $counter = 0;
    foreach ($colNames as $colName) {
        $counter++;
        if ($counter == $countCol) {
            echo '$jsonData->' . $colName['name'];
        } else {
            echo '$jsonData->' . $colName['name'] . ',';
        }
    }
    echo ');<br>';
    foreach ($colNames as $colName) {
        echo '$' . $colName['name'] . '=$' . $tableName . '->get' . ucfirst($colName['name']) . '();<br>';
    }
    echo '$checkData=$readDB->prepare("SELECT * FROM ' . $tableName . ' WHERE ' . $primaryKey . '=:' . $primaryKey . '");<br>';
    echo '$checkData->bindParam(":' . $primaryKey . '", $' . $primaryKey . ',PDO::PARAM_STR);<br>';
    echo '$checkData->execute();<br>';
    echo '$rowCount=$checkData->rowCount();<br>';
    echo 'if($rowCount===0){<br>';
    echo ' $response = new Response();<br>';
    echo '$response->setHttpStatusCode(404);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage("Data not found");<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';
    echo '$mainQuery="UPDATE ' . $tableName . ' SET ";<br>';
    foreach ($colNames as $colName) {
        if ($colName['name'] != $primaryKey) {
            echo 'if($' . $colName['name'] . '!=""){<br>';
            echo '$mainQuery=$mainQuery. "' . $colName['name'] . '=:' . $colName['name'] . ',"; <br>';
            echo '}<br>';
        }
    }
    echo '$mainQuery=substr($mainQuery, 0, -1). " WHERE ' . $primaryKey . '=:' . $primaryKey . '";<br>';
    echo '$updateSql=$writeDB->prepare($mainQuery);<br>';
    foreach ($colNames as $colName) {
        echo 'if($' . $colName['name'] . '!=""){<br>';
        echo '$updateSql->bindParam("' . $colName['name'] . '",$' . $colName['name'] . ', PDO::PARAM_STR);<br>';
        echo '}<br>';
    }
    echo '$updateSql->execute();<br>';
    echo '$rowCount=$updateSql->rowCount();<br>';
    echo 'if($rowCount==1){<br>';
    echo ' $response = new Response();<br>';
    echo '$response->setHttpStatusCode(200);<br>';
    echo '$response->setSuccess(true);<br>';
    echo '$response->addMessage("Data Update success.");<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';
    echo 'else{<br>';
    echo ' $response = new Response();<br>';
    echo '$response->setHttpStatusCode(400);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage("Nothing to change for update!!!");<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';
    echo '} catch (' . $className . 'Exception $ex) {<br>';
    echo '$response = new Response();<br>';
    echo '$response->setHttpStatusCode(400);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage($ex->getMessage());<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '} catch (PDOException $ex) {<br>';
    echo 'error_log("Database query error - " . $ex, 1);<br>';
    echo '$response = new Response();<br>';
    echo '$response->setHttpStatusCode(500);<br>';
    echo '$response->setSuccess(false);<br>';
    echo '$response->addMessage($ex->getMessage());<br>';
    echo '$response->send();<br>';
    echo 'exit();<br>';
    echo '}<br>';
    echo '}<br>';
}
