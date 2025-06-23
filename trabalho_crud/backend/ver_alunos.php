<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php"); // Redireciona para o login se não estiver logado
    exit();
}

// Conectar com o banco de dados
include '../conexao.php';

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Verifica se foi solicitado remover um aluno
if (isset($_GET['remover'])) {
    $aluno_id = $_GET['remover'];

    // Verifica se o aluno está em algum empréstimo
    $check_emprestimo = "SELECT * FROM emprestimo WHERE id_aluno = $aluno_id";
    $result_check = $conn->query($check_emprestimo);

    if ($result_check->num_rows > 0) {
        echo "<script>alert('O aluno está registrado em um empréstimo. Primeiro remova o empréstimo para depois excluir o aluno.');</script>";
    } else {
        // Remove o aluno
        $sql_remover = "DELETE FROM aluno WHERE id = $aluno_id";
        $conn->query($sql_remover);

        // Redireciona silenciosamente após exclusão
        header("Location: ../frontend/ver_alunos_front.php");
        exit();
    }
}

// Consulta para buscar todos os alunos
$sql = "SELECT * FROM aluno";
$result = $conn->query($sql);

// Fechar a conexão
$conn->close();
?>