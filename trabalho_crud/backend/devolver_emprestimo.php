<?php
session_start();

include '../conexao.php';

// Verifica se professor está logado
if (!isset($_SESSION['professor_id'])) {
    echo 'erro';
    exit();
}

// Verifica se o ID foi passado
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Atualiza o status e data de devolução
    $sql = "UPDATE emprestimo SET status = 'Devolvido', data_devolucao = NOW() WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo 'ok';
    } else {
        echo 'erro';
    }
} else {
    echo 'erro';
}

$conn->close();
?>