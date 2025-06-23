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
    $_SESSION['mensagem_editar_professor'] = "<p style='color: red;'>ID do professor não especificado.</p>";
    header("Location: ../frontend/ver_professores_front.php");
    exit();
}

$id = intval($_GET['id']);

// Atualização (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cpf = preg_replace('/\D/', '', $_POST['cpf']); // Remove pontos e traços
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if (!empty($senha)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE professor SET nome = ?, cpf = ?, email = ?, senha = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nome, $cpf, $email, $senha_hash, $id);
    } else {
        $sql = "UPDATE professor SET nome = ?, cpf = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nome, $cpf, $email, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['mensagem_editar_professor'] = "<p style='color: green;'>Dados atualizados com sucesso!</p>";
    } else {
        $_SESSION['mensagem_editar_professor'] = "<p style='color: red;'>Erro ao atualizar: " . $stmt->error . "</p>";
    }

    $stmt->close();
    header("Location: ../frontend/editar_prof_front.php?id=$id");
    exit();
}

// Recupera dados do professor
$sql = "SELECT * FROM professor WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['mensagem_editar_professor'] = "<p style='color: red;'>Professor não encontrado.</p>";
    header("Location: ../frontend/ver_professores_front.php");
    exit();
}

$prof = $result->fetch_assoc();
$prof_id = $prof['id'];

$stmt->close();
?>