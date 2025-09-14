<?php
session_start();
include '../conexao.php';

// Define que a saída será JSON
header('Content-Type: application/json');

// Função para enviar resposta JSON
function send_response($status, $message, $data = null)
{
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    send_response('error', 'Professor não está logado.');
}

if ($conn->connect_error) {
    send_response('error', 'Falha na conexão com o banco de dados: ' . $conn->connect_error);
}

// Verifica se o ID do professor foi passado
if (!isset($_GET['id'])) {
    send_response('error', 'ID do professor não especificado.');
}

$id = intval($_GET['id']);

// Atualização (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $nome = $input['nome'] ?? null;
    $cpf = isset($input['cpf']) ? preg_replace('/\D/', '', $input['cpf']) : null;
    $email = $input['email'] ?? null;
    $senha = $input['senha'] ?? null;

    if (!$nome || !$cpf || !$email) {
        send_response('error', 'Nome, CPF e email são obrigatórios.');
    }

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
        send_response('success', 'Dados atualizados com sucesso.');
    } else {
        send_response('error', 'Erro ao atualizar: ' . $stmt->error);
    }
}

// Recupera dados do professor (GET)
$sql = "SELECT id, nome, cpf, email FROM professor WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    send_response('error', 'Professor não encontrado.');
}

$prof = $result->fetch_assoc();
$stmt->close();

send_response('success', 'Professor encontrado.', $prof);

$conn->close();
?>