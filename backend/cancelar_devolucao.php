<?php
include '../conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Buscar a data de devolução prevista
    $sql = "SELECT data_devolucao FROM emprestimo WHERE id = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $emprestimo = $result->fetch_assoc();
        $dataDevolucao = $emprestimo['data_devolucao'];
        $hoje = date('Y-m-d');

        if (!empty($dataDevolucao) && $dataDevolucao < $hoje) {
            $novoStatus = 1; // Atrasado
        } else {
            $novoStatus = 0; // Em andamento
        }

        $update = "UPDATE emprestimo SET status = $novoStatus WHERE id = $id";

        if ($conn->query($update) === TRUE) {
            echo "ok";
        } else {
            echo "Erro ao atualizar: " . $conn->error;
        }
    } else {
        echo "Empréstimo não encontrado.";
    }
}
?>