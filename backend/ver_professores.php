<?php
// Inicia a sessão
session_start();
header('Content-Type: application/json'); // Define que a resposta será JSON

$response = []; // Array que armazenará a resposta

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit();
}

// Conectar com o banco de dados
include '../conexao.php';

// Verifica a conexão
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Falha na conexão com o banco de dados: ' . $conn->connect_error]);
    exit();
}

// Remover professor se solicitado
if (isset($_GET['remover'])) {
    $professor_id = intval($_GET['remover']); // segurança contra SQL Injection

    // Impede que o professor logado se remova
    if ($professor_id == $_SESSION['professor_id']) {
        $response['success'] = false;
        $response['message'] = 'Você não pode remover a si mesmo enquanto estiver logado.';
        echo json_encode($response);
        exit();
    }

    // Verifica se o professor está vinculado a algum empréstimo
    $check_emprestimo = "SELECT * FROM emprestimo WHERE id_professor = $professor_id";
    $result_check = $conn->query($check_emprestimo);

    if ($result_check->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = 'O professor está vinculado a um ou mais empréstimos. Primeiro remova os empréstimos em seu nome para depois excluí-lo.';
        echo json_encode($response);
        exit();
    } else {
        // Remove o professor
        $sql_remover = "DELETE FROM professor WHERE id = $professor_id";
        if ($conn->query($sql_remover) === TRUE) {
            $response['success'] = true;
            $response['message'] = 'Professor removido com sucesso.';
        } else {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = 'Erro ao remover professor: ' . $conn->error;
        }
        echo json_encode($response);
        exit();
    }
}

// Caso contrário, retorna todos os professores
$sql = "SELECT * FROM professor";
$result = $conn->query($sql);

$professores = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $professores[] = $row;
    }
}

$response['success'] = true;
$response['professores'] = $professores;

echo json_encode($response);

// Fecha a conexão
$conn->close();
exit();
?>