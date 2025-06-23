<?php
// Inicia a sessão
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

// Consulta para buscar todos os professores
$sql = "SELECT * FROM professor";
$result = $conn->query($sql);

// Verifica se foi solicitado remover um aluno
if (isset($_GET['remover'])) {
    $professor_id = $_GET['remover'];

    // Verifica se o aluno está em algum empréstimo
    $check_emprestimo = "SELECT * FROM emprestimo WHERE id_professor = $professor_id";
    $result_check = $conn->query($check_emprestimo);

    if ($result_check->num_rows > 0) {
        echo "<script>alert('O professor está vinculado a um empréstimo. Primeiro remova o empréstimo para depois excluir o professor.');</script>";
    } else {
        // Remove o aluno
        $sql_remover = "DELETE FROM professor WHERE id = $professor_id";
        $conn->query($sql_remover);

        // Redireciona silenciosamente após exclusão
        header("Location: ../frontend/ver_professores_front.php");
        exit();
    }
}

// Consulta para buscar todos os professores
$sql = "SELECT * FROM professor";
$result = $conn->query($sql);

// Fecha a conexão
$conn->close();
?>