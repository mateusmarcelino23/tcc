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

$aluno_id = intval($_GET['id']); // segurança: garantir que é número inteiro

// Atualiza os dados se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $conn->real_escape_string($_POST['nome']);
    $ano = $conn->real_escape_string($_POST['ano']);
    $sala = strtoupper($conn->real_escape_string($_POST['sala']));
    $email = $conn->real_escape_string($_POST['email']);

    // Concatenar o ano e a sala para formar a série
    if (in_array($ano, ['1', '2', '3'])) {
        $serie = $ano . 'º Ano EM ' . $sala;  // Ensino Médio
    } else {
        $serie = $ano . 'º Ano ' . $sala;
    }

    // Atualiza os dados no banco
    $sql = "UPDATE aluno SET nome='$nome', serie='$serie', email='$email' WHERE id=$aluno_id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['mensagem_editar_aluno'] = "<p style='color: green;'>Dados atualizados com sucesso!</p>";
    } else {
        $_SESSION['mensagem_editar_aluno'] = "<p style='color: red;'>Erro ao atualizar: " . $conn->error . "</p>";
    }

    // Redireciona para a página de edição para mostrar a mensagem
    header("Location: ../frontend/editar_aluno_front.php?id=$aluno_id");
    exit();
}

// Busca os dados do aluno para exibir no formulário (GET)
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

// Se o aluno está no EM, a variável $em será 'EM' (não está sendo usada diretamente no frontend, pode remover se quiser)
$em = isset($match[2]) ? 'EM' : '';
?>