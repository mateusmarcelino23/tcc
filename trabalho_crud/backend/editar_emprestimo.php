<?php
session_start();
header('Content-Type: application/json');

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Professor não está logado.'
    ]);
    exit();
}

// Conexão com o banco
include '../conexao.php';
if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro de conexão: ' . $conn->connect_error
    ]);
    exit();
}

// Função para converter data de d/m/Y para Y-m-d
function converterDataParaBD($data)
{
    $partes = explode('/', $data);
    return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
}

// Função para converter data de Y-m-d para d/m/Y
function converterDataParaBR($data)
{
    $partes = explode('-', $data);
    return $partes[2] . '/' . $partes[1] . '/' . $partes[0];
}

if (!isset($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID do empréstimo não fornecido.'
    ]);
    exit();
}

$id_emprestimo = intval($_GET['id']);

// Se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_aluno = intval($_POST['id_aluno']);
    $id_livro = intval($_POST['id_livro']);
    $data_emprestimo = converterDataParaBD($_POST['data_emprestimo']);
    $data_devolucao = converterDataParaBD($_POST['data_devolucao']);

    if ($data_devolucao < $data_emprestimo) {
        echo json_encode([
            'success' => false,
            'message' => 'A data de devolução não pode ser anterior à data de empréstimo.'
        ]);
        exit();
    }

    $stmt = $conn->prepare("UPDATE emprestimo SET id_aluno = ?, id_livro = ?, data_emprestimo = ?, data_devolucao = ? WHERE id = ?");
    $stmt->bind_param("iissi", $id_aluno, $id_livro, $data_emprestimo, $data_devolucao, $id_emprestimo);

    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Empréstimo atualizado com sucesso!'
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Erro ao atualizar: ' . $stmt->error
        ];
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
    exit();
}

// Se não for POST, busca os dados do empréstimo
$stmt = $conn->prepare("SELECT e.id, e.id_aluno, e.id_livro, e.data_emprestimo, e.data_devolucao, a.nome AS aluno_nome, l.nome_livro
                        FROM emprestimo e
                        JOIN aluno a ON e.id_aluno = a.id
                        JOIN livro l ON e.id_livro = l.id
                        WHERE e.id = ?");
$stmt->bind_param("i", $id_emprestimo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Empréstimo não encontrado.'
    ]);
    exit();
}

$emprestimo = $result->fetch_assoc();

$stmt->close();
$conn->close();

echo json_encode([
    'success' => true,
    'data' => $emprestimo
]);
exit();
?>