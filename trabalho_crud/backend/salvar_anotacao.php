<?php
include '../conexao.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['texto'])) {
    $texto = $conn->real_escape_string($_POST['texto']);
    $sql = "INSERT INTO anotacoes (texto, data) VALUES ('$texto', NOW())";
    $conn->query($sql);
}
header("Location: dashboard.php"); // ou o nome da página principal
exit;
?>