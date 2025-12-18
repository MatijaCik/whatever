<?php
require_once "connect.php";

header('Content-Type: application/json; charset=utf-8');

$term = $_GET['q'] ?? '';
$term = trim($term);

if (strlen($term) < 1) {
    die(json_encode([]));
}

$query = "
    SELECT id, naziv, kalorije, proteini, ugljikohidrati, masti
    FROM hrana
    WHERE naziv LIKE '%" . $conn->real_escape_string($term) . "%'
    ORDER BY naziv
    LIMIT 20
";

$result = $conn->query($query);

$results = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
}

echo json_encode($results);
