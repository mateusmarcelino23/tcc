<?php
session_start();
include '../conexao.php';

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php");
    exit();
}

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Verifica se o ID do professor foi passado
if (!isset($_GET['id'])) {
    echo "<p style='color: red;'>ID do professor não especificado.</p>";
    exit;
}

$id = intval($_GET['id']);

// Atualização (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = preg_replace('/\D/', '', $_POST['cpf']); // Remove pontos e traços
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if (!empty($senha)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE professor SET nome='$nome', cpf='$cpf', email='$email', senha='$senha_hash' WHERE id=$id";
    } else {
        $sql = "UPDATE professor SET nome='$nome', cpf='$cpf', email='$email' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Dados atualizados com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao atualizar: " . $conn->error . "</p>";
    }
}

// Recupera dados do professor
$result = $conn->query("SELECT * FROM professor WHERE id = $id");
if ($result->num_rows != 1) {
    echo "<p style='color: red;'>Professor não encontrado.</p>";
    exit;
}
$prof = $result->fetch_assoc();
?>