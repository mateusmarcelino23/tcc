<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['professor_id'])) {
    echo json_encode(['loggedIn' => true, 'nome' => $_SESSION['professor_nome']]);
} else {
    echo json_encode(['loggedIn' => false]);
}
?>