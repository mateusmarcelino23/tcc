<?php
session_start();

if (!isset($_SESSION['professor_id'])) {
    http_response_code(403);
    echo "Acesso negado.";
    exit();
}

include '../conexao.php';

if ($conn->connect_error) {
    http_response_code(500);
    echo "Erro na conexão com o banco de dados.";
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql = "UPDATE emprestimo SET status = 2 WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "ok";
    } else {
        http_response_code(500);
        echo "Erro ao atualizar o status.";
    }
    
    $stmt->close();
} else {
    http_response_code(400);
    echo "ID inválido.";
}

$conn->close();
?>