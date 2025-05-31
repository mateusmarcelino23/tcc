<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    // Redireciona para a página de login se não estiver logado
    header("Location: ../frontend/login_front.php");
    exit();
}

// Conexão com o banco de dados
include '../conexao.php';

// Verifica se ocorreu erro na conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta SQL para buscar todos os empréstimos com dados relacionados
$sql = "SELECT e.id, e.data_emprestimo, e.data_devolucao, e.status, 
               a.nome AS aluno_nome, l.nome_livro, l.nome_autor, 
               p.nome AS professor_nome
        FROM emprestimo e
        JOIN aluno a ON e.id_aluno = a.id
        JOIN livro l ON e.id_livro = l.id
        JOIN professor p ON e.id_professor = p.id";

$result = $conn->query($sql);

// Verifica se foi solicitada remoção de um empréstimo
if (isset($_GET['remover'])) {
    $id_emprestimo = intval($_GET['remover']); // Garante que seja um número inteiro
    $sql_remover = "DELETE FROM emprestimo WHERE id = $id_emprestimo";

    if ($conn->query($sql_remover) === TRUE) {
        // Redireciona após remoção
        header("Location: ../frontend/ver_emprestimos_front.php");
        exit();
    } else {
        echo "Erro ao remover o empréstimo: " . $conn->error;
    }
}

// Fecha a conexão
$conn->close();
?>