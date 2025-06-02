<?php
session_start();
include '../conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['professor_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit();
}

if (!isset($_POST['texto'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Texto não enviado']);
    exit();
}

$id_professor = $_SESSION['professor_id'];
$texto = $conn->real_escape_string($_POST['texto']);
$data_atual = date('Y-m-d H:i:s');

$sql = "INSERT INTO anotacoes (texto, data, id_professor) VALUES ('$texto', '$data_atual', $id_professor)";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Anotação salva com sucesso']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $conn->error]);
}

$conn->close();

?>