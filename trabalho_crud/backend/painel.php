<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: trabalho_crud/frontend/login_front.php");
    exit();
}

// Conectar com o banco de dados
include 'trabalho_crud/conexao.php';

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Remover empréstimo se o ID for passado via GET
if (isset($_GET['remover_id'])) {
    $id = intval($_GET['remover_id']);
    $conn->query("DELETE FROM emprestimo WHERE id = $id");
    header("Location: painel.php");
    exit();
}

// Consulta para buscar todos os empréstimos
$sql = "SELECT e.id, e.data_emprestimo, e.data_devolucao, a.nome AS aluno_nome, l.nome_livro, l.nome_autor 
        FROM emprestimo e
        JOIN aluno a ON e.id_aluno = a.id
        JOIN livro l ON e.id_livro = l.id";
$result = $conn->query($sql);

$conn->close();
?>