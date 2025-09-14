<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['professor_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit();
}

include '../conexao.php';
$conn->set_charset("utf8");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => "Falha na conexão com o banco de dados: " . $conn->connect_error]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_aluno = $_POST['id_aluno'] ?? null;
    $id_professor = $_SESSION['professor_id'];
    $id_livro = $_POST['id_livro'] ?? null;

    // Validação básica
    if (!$id_aluno || !$id_livro || empty($_POST['data_emprestimo']) || empty($_POST['data_devolucao'])) {
        echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
        exit();
    }

    // Converte datas do formato d/m/Y para Y-m-d
    $data_emprestimo = DateTime::createFromFormat('d/m/Y', $_POST['data_emprestimo']);
    $data_devolucao = DateTime::createFromFormat('d/m/Y', $_POST['data_devolucao']);

    if (!$data_emprestimo || !$data_devolucao) {
        echo json_encode(['success' => false, 'message' => 'Formato de data inválido.']);
        exit();
    }

    $data_emprestimo = $data_emprestimo->format('Y-m-d');
    $data_devolucao = $data_devolucao->format('Y-m-d');

    if ($data_devolucao < $data_emprestimo) {
        echo json_encode(['success' => false, 'message' => 'A data de devolução não pode ser anterior à data de empréstimo.']);
        exit();
    }

    $status = 0; // Em andamento

    // Prepared statement para evitar SQL Injection
    $stmt = $conn->prepare("INSERT INTO emprestimo (id_aluno, id_professor, id_livro, data_emprestimo, data_devolucao, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiissi", $id_aluno, $id_professor, $id_livro, $data_emprestimo, $data_devolucao, $status);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Empréstimo registrado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao registrar o empréstimo: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método HTTP inválido.']);
}

$conn->close();
exit();
?>