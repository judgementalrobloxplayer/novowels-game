<?php
include_once "./main.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['type'])) {
        $response = array();
        $pdo = openDB();
        if ($_POST['type'] === 'checkID') {
            $query = "SELECT COUNT(*) FROM wordList WHERE BINARY listID = :listID";
            $statement = $pdo->prepare($query);
            $statement->bindParam(':listID', $_POST['listID'], PDO::PARAM_STR);
            $statement->execute();
            $count = $statement->fetchColumn();
            if ($count > 0) {
                $response['exists'] = 1;
            } else {
                $response['exists'] = 0;
            }
            
        } elseif ($_POST['type'] === 'rand') {
            $query = "SELECT listID FROM wordList ORDER BY RAND() LIMIT 1";
            $statement = $pdo->prepare($query);
            $statement->execute();
            $code = $statement->fetchColumn();
            if ($code == null) {
                $response['exists'] = 0;
            } else {
                $response['exists'] = 1;
                $response['code'] = $code;
            }
            
        } elseif ($_POST['type'] === 'list') {
            $query = "SELECT listid,title FROM wordList ORDER BY title";
            $statement = $pdo->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            if ($result == null) {
                $response['list'] = null;
            } else {
                $response['list'] = $result;
            }
            
        } elseif ($_POST['type'] === 'newWord') {
            $query = "SELECT COUNT(*) FROM wordList WHERE BINARY listID = :listID";
            $statement = $pdo->prepare($query);
            $statement->bindParam(':listID', $_POST['listID'], PDO::PARAM_STR);
            $statement->execute();
            $count = $statement->fetchColumn();
            if ($count > 0) {
                $query = "SELECT * FROM wordList WHERE BINARY listID = :listID";
                $statement = $pdo->prepare($query);
                $statement->bindParam(':listID', $_POST['listID'], PDO::PARAM_STR);
                $statement->execute();

                $word = $statement->fetch(PDO::FETCH_ASSOC);
                $response = array(
                    'exists' => 1,
                    'time' => json_decode($word['time'], true),
                    'data' => json_decode($word['data'], true)                    
                );
            } else {
                $response['exists'] = 0;
            }              
        }
        $pdo = null; 
        echo json_encode($response);
    } 
}
?>
