<?php
session_start();

// Retorna JSON indicando se o usuário está logado
if (!isset($_SESSION['professor_id'])) {
    echo json_encode([
        "logged_in" => false
    ]);
    exit();
}

echo json_encode([
    "logged_in" => true
]);
?>