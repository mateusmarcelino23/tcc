<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php");
    exit();
}

// Conecta com o banco de dados
include '../conexao.php';
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o ID do aluno foi passado
if (!isset($_GET['id'])) {
    echo "ID do aluno não fornecido.";
    exit();
}

$aluno_id = $_GET['id'];

// Atualiza os dados se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $ano = $_POST['ano'];
    $sala = $_POST['sala'];
    $email = $_POST['email'];

    // Concatenar o ano e a sala para formar a série
    if (in_array($ano, ['1', '2', '3'])) {
        $serie = $ano . 'º Ano EM ' . $sala;  // Se for Ensino Médio, adicionar 'EM'
    } else {
        $serie = $ano . 'º Ano ' . $sala;  // Caso contrário, apenas o ano e sala
    }

    // Atualiza os dados no banco
    $sql = "UPDATE aluno SET nome='$nome', serie='$serie', email='$email' WHERE id=$aluno_id";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Aluno atualizado com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao atualizar: " . $conn->error . "</p>";
    }
}

// Busca os dados do aluno
$sql = "SELECT * FROM aluno WHERE id=$aluno_id";
$result = $conn->query($sql);
if ($result->num_rows != 1) {
    echo "Aluno não encontrado.";
    exit();
}

$aluno = $result->fetch_assoc();

// Extrai o ano e a sala da série
preg_match('/(\d+)º Ano\s*(EM\s*)?(\w+)/', $aluno['serie'], $match);

// Ajusta os valores conforme a série
$ano = $match[1] ?? '';
$sala = $match[3] ?? '';

// Se o aluno está no EM, a série será 'EM'
$em = isset($match[2]) ? 'EM' : '';
?>