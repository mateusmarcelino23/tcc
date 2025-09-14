<?php
session_start();
header('Content-Type: application/json');

include '../conexao.php';

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuário não está logado.'
    ]);
    exit();
}

// Verifica a conexão
if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'message' => 'Falha na conexão com o banco de dados: ' . $conn->connect_error
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe dados JSON
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        echo json_encode([
            'success' => false,
            'message' => 'JSON inválido ou ausente.'
        ]);
        exit();
    }

    $nome = $conn->real_escape_string($data['nome'] ?? '');
    $cpf = preg_replace('/\D/', '', $data['cpf'] ?? '');
    $email = $conn->real_escape_string($data['email'] ?? '');
    $senha = $data['senha'] ?? '';

    if (empty($nome) || empty($cpf) || empty($email) || empty($senha)) {
        echo json_encode([
            'success' => false,
            'message' => 'Todos os campos são obrigatórios.'
        ]);
        exit();
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO professor (nome, cpf, email, senha) VALUES ('$nome', '$cpf', '$email', '$senha_hash')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode([
            'success' => true,
            'message' => 'Professor cadastrado com sucesso!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao cadastrar professor: ' . $conn->error
        ]);
    }

    $conn->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método HTTP inválido. Use POST.'
    ]);
}

exit();
?>