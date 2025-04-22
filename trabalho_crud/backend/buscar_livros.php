<?php
$conn = new mysqli('localhost', 'root', '', 'crud_db');

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

$termo = isset($_GET['term']) ? $conn->real_escape_string($_GET['term']) : '';

$sql = "SELECT id, nome_livro FROM livro WHERE nome_livro LIKE '$termo%'";

$resultado = $conn->query($sql);

$livros = [];

while ($row = $resultado->fetch_assoc()) {
    $livros[] = [
        'id' => $row['id'],
        'text' => $row['nome_livro']
    ];
}

header('Content-Type: application/json');
echo json_encode($livros);