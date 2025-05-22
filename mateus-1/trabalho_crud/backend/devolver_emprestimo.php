<?php
// Conectar ao banco de dados
include '../conexao.php';

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se o ID foi passado
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Apaga o empréstimo
    $sql = "DELETE FROM emprestimo WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo 'ok'; // resposta que o JS espera
    } else {
        echo 'erro';
    }
} else {
    echo 'erro';
}

$conn->close();
?>