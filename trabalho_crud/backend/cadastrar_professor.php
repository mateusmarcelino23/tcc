<?php
session_start();
include '../conexao.php';

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php");
    exit();
}

// Verifica a conexão
if ($conn->connect_error) {
    $_SESSION['mensagem_professor'] = "<p style='color: red;'>Falha na conexão com o banco de dados: " . $conn->connect_error . "</p>";
    header("Location: ../frontend/cadastrar_professor_front.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO professor (nome, cpf, email, senha) VALUES ('$nome', '$cpf', '$email', '$senha_hash')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['mensagem_professor'] = "<p style='color: green;'>Professor cadastrado com sucesso!</p>";
    } else {
        $_SESSION['mensagem_professor'] = "<p style='color: red;'>Erro ao cadastrar professor: " . $conn->error . "</p>";
    }

    $conn->close();

    header("Location: ../frontend/cadastrar_professor_front.php");
    exit();
}
?>
