<?php
header('Content-Type: application/json; charset=utf-8');

// Conexão com o banco
include '../conexao.php';
if ($conn->connect_error) {
    echo json_encode(["erro" => "Erro na conexão: " . $conn->connect_error]);
    exit();
}

// Verifica se o usuário está logado
session_start();
if (!isset($_SESSION['professor_id'])) {
    echo json_encode(["erro" => "Usuário não autenticado"]);
    exit();
}

// fuso horário
date_default_timezone_set('America/Sao_Paulo');

$resposta = [
    "alunos" => [],
    "livros" => [],
    "series" => [],
    "notas"  => []
];

// Consulta 1: Alunos que mais leram
$sqlAlunos = "SELECT a.nome AS aluno_nome, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN aluno a ON e.id_aluno = a.id
              WHERE e.status = 2
              GROUP BY e.id_aluno
              ORDER BY total DESC
              LIMIT 5";
$resultAlunos = $conn->query($sqlAlunos);

if ($resultAlunos && $resultAlunos->num_rows > 0) {
    while ($row = $resultAlunos->fetch_assoc()) {
        $resposta["alunos"][] = $row;
    }
}

// Consulta 2: Livros mais lidos
$sqlLivros = "SELECT l.nome_livro, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN livro l ON e.id_livro = l.id
              WHERE e.status = 2
              GROUP BY e.id_livro
              ORDER BY total DESC
              LIMIT 5";
$resultLivros = $conn->query($sqlLivros);

if ($resultLivros && $resultLivros->num_rows > 0) {
    while ($row = $resultLivros->fetch_assoc()) {
        $resposta["livros"][] = $row;
    }
}

// Consulta 3: Séries que mais leram
$sqlSeries = "SELECT a.serie, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN aluno a ON e.id_aluno = a.id
              WHERE e.status = 2
              GROUP BY a.serie
              ORDER BY total DESC
              LIMIT 5";
$resultSeries = $conn->query($sqlSeries);

if ($resultSeries && $resultSeries->num_rows > 0) {
    while ($row = $resultSeries->fetch_assoc()) {
        $resposta["series"][] = $row;
    }
}

// Consulta para observações
$sqlNotas = "SELECT n.id, n.texto, n.data, p.nome AS professor_nome, 
             CONVERT_TZ(data, '+00:00', '-05:00') AS data_corrigida
             FROM anotacoes n
             JOIN professor p ON n.id_professor = p.id
             ORDER BY n.data DESC";
$resultNotas = $conn->query($sqlNotas);

if ($resultNotas && $resultNotas->num_rows > 0) {
    while ($row = $resultNotas->fetch_assoc()) {
        $resposta["notas"][] = $row;
    }
}

// Retorna JSON
echo json_encode($resposta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
$conn->close();
?>