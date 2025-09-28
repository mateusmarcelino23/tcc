<?php
session_start();
header('Content-Type: application/json'); // Define o retorno como JSON

$response = [];

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Acesso negado. Faça login.']);
    exit();
}

// Conectar com o banco de dados
include '../conexao.php';

if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Falha na conexão com o banco de dados: ' . $conn->connect_error]);
    exit();
}

// Remover aluno se solicitado
if (isset($_GET['remover'])) {
    $aluno_id = intval($_GET['remover']); // Segurança contra SQL Injection

    // Verifica se o aluno está em algum empréstimo
    $check_emprestimo = "SELECT * FROM emprestimo WHERE id_aluno = $aluno_id";
    $result_check = $conn->query($check_emprestimo);

    if ($result_check->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = 'O aluno está vinculado a um ou mais empréstimos. Primeiro remova os empréstimos em seu nome para depois excluí-lo.';
        echo json_encode($response);
        exit();
    } else {
        // Remove o aluno
        $sql_remover = "DELETE FROM aluno WHERE id = $aluno_id";
        if ($conn->query($sql_remover) === TRUE) {
            $response['success'] = true;
            $response['message'] = 'Aluno removido com sucesso.';
        } else {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = 'Erro ao remover aluno: ' . $conn->error;
        }
        echo json_encode($response);
        exit();
    }
}

// Caso contrário, retorna todos os professores
$sql = "SELECT * FROM aluno";
$result = $conn->query($sql);

$alunos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $alunos[] = $row;
    }
}

$response['success'] = true;
$response['alunos'] = $alunos;

echo json_encode($response);

// Fecha a conexão
$conn->close();
exit();
?>