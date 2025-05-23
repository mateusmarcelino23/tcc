<?php
session_start();
include '../conexao.php';

// Verifica se o professor está logado e tem id na sessão
if (!isset($_SESSION['professor_id'])) {
    header('Location: login.php');
    exit();
}

$id_professor = $_SESSION['professor_id'];
$texto = $conn->real_escape_string($_POST['texto']);
$data_atual = date('Y-m-d H:i:s');

$sql = "INSERT INTO anotacoes (texto, data, id_professor) VALUES ('$texto', '$data_atual', $id_professor)";

if ($conn->query($sql) === TRUE) {
    // Redireciona de volta para a página de relatórios após salvar
    header('Location: relatorios.php'); 
    exit();
} else {
    echo "Erro: " . $conn->error;
}

$conn->close();
?>