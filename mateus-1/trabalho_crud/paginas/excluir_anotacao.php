<?php
include '../conexao.php';

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM anotacoes WHERE id = $id";
    if($conn->query($sql) === TRUE) {
        header("Location: relatorios.php"); // Ajuste para a página correta
        exit();
    } else {
        echo "Erro ao excluir: " . $conn->error;
    }
} else {
    echo "ID não especificado.";
}
?>