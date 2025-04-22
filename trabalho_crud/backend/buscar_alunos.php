<?php
$conn = new mysqli('localhost', 'root', '', 'crud_db');

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

$termo = isset($_GET['term']) ? $conn->real_escape_string($_GET['term']) : '';

$sql = "SELECT id, nome FROM aluno WHERE nome LIKE '$termo%'";

$resultado = $conn->query($sql);

$alunos = [];

while ($row = $resultado->fetch_assoc()) {
    $alunos[] = [
        'id' => $row['id'],     // esse valor vai no form
        'text' => $row['nome']  // esse valor aparece pro usuário
    ];
}

header('Content-Type: application/json');
echo json_encode($alunos);