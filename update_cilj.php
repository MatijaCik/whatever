<?php
session_start();
require_once "connect.php";

if(!isset($_SESSION['id'])) {
    http_response_code(403);
    exit('Nije dozvoljeno');
}

$user_id = $_SESSION['id'];
$novi_cilj = isset($_POST['novi_cilj']) ? (int)$_POST['novi_cilj'] : 0;

if($novi_cilj > 0){
    $stmt = $conn->prepare("UPDATE korisnici SET cilj_kcal = ? WHERE id = ?");
    $stmt->bind_param("ii", $novi_cilj, $user_id);
    if($stmt->execute()){
        echo 'OK';
        
    } else {
        echo 'ERROR';
    }
    $stmt->close();
} else {
    echo 'ERROR';
}
?>
