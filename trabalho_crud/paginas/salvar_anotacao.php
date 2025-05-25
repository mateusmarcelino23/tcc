<?php

session_start();
include '../conexao.php';

if (!isset($_SESSION['professor_id'])) {
    header('Location: login.php');
    exit();
}

$id_professor = $_SESSION['professor_id'];
$texto = $conn->real_escape_string($_POST['texto']);
$data_atual = date('Y-m-d H:i:s');

$sql = "INSERT INTO anotacoes (texto, data, id_professor) VALUES ('$texto', '$data_atual', $id_professor)";

if ($conn->query($sql) === TRUE) {
    // Recebe a página anterior enviada no POST
    $paginaAnterior = isset($_POST['paginaAnterior']) ? $_POST['paginaAnterior'] : 'relatorios.php';

    // Evita redirecionamento para locais inválidos, opcionalmente
    // por segurança, você pode validar que $paginaAnterior seja local e dentro do seu domínio

    header('Location: ' . $paginaAnterior);
    exit();
} else {
    echo "Erro: " . $conn->error;
}

$conn->close();

?>