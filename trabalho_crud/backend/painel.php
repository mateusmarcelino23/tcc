<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: trabalho_crud/frontend/login_front.php");
    exit();
}

// Conectar com o banco de dados
include 'trabalho_crud/conexao.php';

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Primeiro nome do professor logado no painel
if (!isset($_SESSION['professor_primeiro_nome']) && isset($_SESSION['professor_nome'])) {
    $nomeCompleto = trim($_SESSION['professor_nome']);
    $_SESSION['professor_primeiro_nome'] = explode(' ', $nomeCompleto)[0];
}

$conn->close();
?>