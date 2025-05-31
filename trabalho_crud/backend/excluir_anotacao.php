<?php
include '../conexao.php';
session_start();

if (!isset($_SESSION['professor_id'])) {
    http_response_code(403);
    echo "Não autorizado";
    exit();
}

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM anotacoes WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        // Recebe a página anterior enviada no POST
        $paginaAnterior = isset($_POST['paginaAnterior']) ? $_POST['paginaAnterior'] : '../frontend/relatorios_front.php';

        // Redireciona para a página anterior
        header('Location: ' . $paginaAnterior);
        exit();

    } else {
        http_response_code(500);
        echo "Erro ao excluir anotação: " . $conn->error;
    }
} else {
    http_response_code(400);
    echo "ID não fornecido.";
}
$conn->close();
?>