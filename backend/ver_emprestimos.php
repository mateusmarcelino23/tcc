<?php
session_start();
header('Content-Type: application/json'); // Define resposta como JSON

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Professor não está logado."
    ]);
    exit();
}

// Conexão com o banco de dados
include '../conexao.php';

// Verifica se ocorreu erro na conexão
if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Falha na conexão com o banco de dados: " . $conn->connect_error
    ]);
    exit();
}

// Se foi solicitada remoção de um empréstimo
if (isset($_GET['remover'])) {
    $id_emprestimo = intval($_GET['remover']);
    $sql_remover = "DELETE FROM emprestimo WHERE id = $id_emprestimo";

    if ($conn->query($sql_remover) === FALSE) {
        echo json_encode([
            "success" => false,
            "message" => "Erro ao remover o empréstimo: " . $conn->error
        ]);
        $conn->close();
        exit();
    }
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

$emprestimos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $emprestimos[] = $row;
    }
}

// Retorna os dados em JSON
echo json_encode([
    "success" => true,
    "data" => $emprestimos
]);

$conn->close();
?>
