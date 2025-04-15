<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: login.php"); // Redireciona para o login se não estiver logado
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conecta com o banco de dados
    $conn = new mysqli('localhost', 'root', '', 'crud_db');

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $serie = $_POST['serie'];
    $email = $_POST['email'];

    // Consulta para inserir o aluno no banco de dados
    $sql = "INSERT INTO aluno (nome, serie, email) VALUES ('$nome', '$serie', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Aluno cadastrado com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao cadastrar aluno: " . $conn->error . "</p>";
    }

    // Fecha a conexão
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Aluno</title>

    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Link para a fonte do Google Fonts (exemplo: Roboto) -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <!-- Vinculando o CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="../frontend/style.css">

</head>
<body>

    <!-- Cabeçalho -->