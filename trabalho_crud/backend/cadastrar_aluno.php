<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../conexao.php';

    if ($conn->connect_error) {
        $_SESSION['mensagem_aluno'] = "<p style='color: red;'>Falha na conexão com o banco de dados: " . $conn->connect_error . "</p>";
        header("Location: ../frontend/cadastrar_aluno_front.php");
        exit();
    }

    $nome = $_POST['nome'];
    $ano = $_POST['ano'];
    $sala = $_POST['sala'];
    $email = $_POST['email'];

    if (in_array($ano, ['1', '2', '3'])) {
        $serie = $ano . 'º Ano EM ' . $sala;
    } else {
        $serie = $ano . 'º Ano ' . $sala;
    }

    $sql = "INSERT INTO aluno (nome, serie, email) VALUES ('$nome', '$serie', '$email')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['mensagem_aluno'] = "<p style='color: green;'>Aluno cadastrado com sucesso!</p>";
    } else {
        $_SESSION['mensagem_aluno'] = "<p style='color: red;'>Erro ao cadastrar aluno: " . $conn->error . "</p>";
    }

    $conn->close();

    header("Location: ../frontend/cadastrar_aluno_front.php");
    exit();
}
?>

