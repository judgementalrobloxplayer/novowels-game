<?php
include_once "./main.php";

function addListToDB() {
    $pdo = openDB();
    $jsonData = json_decode(file_get_contents('php://input'), TRUE);
    $response = array();
    do {
        $id = generateRandomCode();
    
        $sql = "SELECT * FROM wordList WHERE BINARY listID = :listID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':listID', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        if ($stmt->rowCount() == 0) {
            break;
        } 
        } while (true);
    
    $title = $jsonData['title'];
    $time = $jsonData['time'];
    $words = json_encode($jsonData['words'], JSON_UNESCAPED_UNICODE);
    $sql = "INSERT INTO wordList (title, time, listID, data) VALUES ('$title', '$time', '$id', '$words')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $response['success'] = 1;
    $response['id'] = $id;
    echo json_encode($response);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    addListToDB();
}
?>
