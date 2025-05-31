<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php");
    exit();
}

// Conexão com o banco
include '../conexao.php';
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Função para converter data de d/m/Y para Y-m-d
function converterDataParaBD($data) {
    $partes = explode('/', $data);
    return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
}

// Função para converter data de Y-m-d para d/m/Y
function converterDataParaBR($data) {
    $partes = explode('-', $data);
    return $partes[2] . '/' . $partes[1] . '/' . $partes[0];
}

if (!isset($_GET['id'])) {
    echo "ID do empréstimo não fornecido.";
    exit();
}

$id_emprestimo = intval($_GET['id']);

// Buscar dados do empréstimo
$stmt = $conn->prepare("SELECT e.id, e.id_aluno, e.id_livro, e.data_emprestimo, e.data_devolucao, a.nome AS aluno_nome, l.nome_livro
                        FROM emprestimo e
                        JOIN aluno a ON e.id_aluno = a.id
                        JOIN livro l ON e.id_livro = l.id
                        WHERE e.id = ?");
$stmt->bind_param("i", $id_emprestimo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Empréstimo não encontrado.";
    exit();
}
$emprestimo = $result->fetch_assoc();

// Se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_aluno = intval($_POST['id_aluno']);
    $id_livro = intval($_POST['id_livro']);
    $data_emprestimo = converterDataParaBD($_POST['data_emprestimo']);
    $data_devolucao = converterDataParaBD($_POST['data_devolucao']);

    // Atualiza o empréstimo no banco
    if ($data_devolucao < $data_emprestimo) {
        echo "<p style='color: red;'>A data de devolução não pode ser anterior à data de empréstimo.</p>";
        exit();
    }

    $stmt = $conn->prepare("UPDATE emprestimo SET id_aluno = ?, id_livro = ?, data_emprestimo = ?, data_devolucao = ? WHERE id = ?");
    $stmt->bind_param("iissi", $id_aluno, $id_livro, $data_emprestimo, $data_devolucao, $id_emprestimo);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Empréstimo atualizado com sucesso!</p>";
        // Atualiza dados na tela após submissão
        $emprestimo['id_aluno'] = $id_aluno;
        $emprestimo['id_livro'] = $id_livro;
        $emprestimo['data_emprestimo'] = $data_emprestimo;
        $emprestimo['data_devolucao'] = $data_devolucao;
    } else {
        echo "<p style='color: red;'>Erro ao atualizar: " . $stmt->error . "</p>";
    }
}

$conn->close();
?>