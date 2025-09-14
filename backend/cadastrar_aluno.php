<?php
session_start();
header('Content-Type: application/json');

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Usuário não autenticado."
    ]);
    http_response_code(401); // Unauthorized
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../conexao.php';

    if ($conn->connect_error) {
        echo json_encode([
            "success" => false,
            "message" => "Falha na conexão com o banco de dados: " . $conn->connect_error
        ]);
        http_response_code(500); // Internal Server Error
        exit();
    }

    // Recebe dados do POST
    $nome = $_POST['nome'] ?? '';
    $ano = $_POST['ano'] ?? '';
    $sala = $_POST['sala'] ?? '';
    $email = $_POST['email'] ?? '';

    // Validação básica
    if (empty($nome) || empty($ano) || empty($sala) || empty($email)) {
        echo json_encode([
            "success" => false,
            "message" => "Todos os campos são obrigatórios."
        ]);
        http_response_code(400); // Bad Request
        exit();
    }

    // Monta a série
    if (in_array($ano, ['1', '2', '3'])) {
        $serie = $ano . 'º Ano EM ' . $sala;
    } else {
        $serie = $ano . 'º Ano ' . $sala;
    }

    // Escapa strings para evitar SQL Injection
    $nome = $conn->real_escape_string($nome);
    $serie = $conn->real_escape_string($serie);
    $email = $conn->real_escape_string($email);

    $sql = "INSERT INTO aluno (nome, serie, email) VALUES ('$nome', '$serie', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode([
            "success" => true,
            "message" => "Aluno cadastrado com sucesso!"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Erro ao cadastrar aluno: " . $conn->error
        ]);
        http_response_code(500); // Internal Server Error
    }

    $conn->close();
} else {
    // Método não permitido
    echo json_encode([
        "success" => false,
        "message" => "Método HTTP não permitido. Use POST."
    ]);
    http_response_code(405); // Method Not Allowed
}
exit();
?>