<?php
session_start();
header('Content-Type: application/json'); // Define que a resposta será JSON

// Função auxiliar para enviar resposta JSON e encerrar
function respond($success, $message, $data = null)
{
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    respond(false, "Professor não logado.");
}

// Conecta com o banco de dados
include '../conexao.php';
if ($conn->connect_error) {
    respond(false, "Falha na conexão: " . $conn->connect_error);
}

// Verifica se o ID do aluno foi passado
if (!isset($_GET['id'])) {
    respond(false, "ID do aluno não fornecido.");
}

$aluno_id = intval($_GET['id']); // segurança: garantir que é número inteiro

// Atualiza os dados se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $conn->real_escape_string($_POST['nome'] ?? '');
    $ano = $conn->real_escape_string($_POST['ano'] ?? '');
    $sala = strtoupper($conn->real_escape_string($_POST['sala'] ?? ''));
    $email = $conn->real_escape_string($_POST['email'] ?? '');

    if (in_array($ano, ['1', '2', '3'])) {
        $serie = $ano . 'º Ano EM ' . $sala;  // Ensino Médio
    } else {
        $serie = $ano . 'º Ano ' . $sala;
    }

    $sql = "UPDATE aluno SET nome='$nome', serie='$serie', email='$email' WHERE id=$aluno_id";

    if ($conn->query($sql) === TRUE) {
        respond(true, "Dados atualizados com sucesso!");
    } else {
        respond(false, "Erro ao atualizar: " . $conn->error);
    }
}

// Busca os dados do aluno para exibir (GET)
$sql = "SELECT * FROM aluno WHERE id=$aluno_id";
$result = $conn->query($sql);

if ($result->num_rows != 1) {
    respond(false, "Aluno não encontrado.");
}

$aluno = $result->fetch_assoc();

// Extrai o ano e a sala da série
preg_match('/(\d+)º Ano\s*(EM\s*)?(\w+)/', $aluno['serie'], $match);

$ano = $match[1] ?? '';
$sala = $match[3] ?? '';
$em = isset($match[2]) ? 'EM' : '';

// Retorna os dados do aluno em JSON
respond(true, "Dados do aluno carregados com sucesso.", [
    'id' => $aluno['id'],
    'nome' => $aluno['nome'],
    'ano' => $ano,
    'sala' => $sala,
    'em' => $em,
    'email' => $aluno['email']
]);

$conn->close();
?>