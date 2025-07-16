<?php
// Conexão com o banco
include '../conexao.php';
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Verifica se o usuário está logado
session_start();
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php");
    exit();
}

// fuso horário
date_default_timezone_set('America/Sao_Paulo');


// Consulta 1: Alunos que mais leram
$sqlAlunos = "SELECT a.nome AS aluno_nome, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN aluno a ON e.id_aluno = a.id
              WHERE e.status = 2
              GROUP BY e.id_aluno
              ORDER BY total DESC
              LIMIT 5";
$resultAlunos = $conn->query($sqlAlunos);
$temAlunos = $resultAlunos->num_rows > 0;

// Consulta 2: Livros mais lidos
$sqlLivros = "SELECT l.nome_livro, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN livro l ON e.id_livro = l.id
              WHERE e.status = 2
              GROUP BY e.id_livro
              ORDER BY total DESC
              LIMIT 5";
$resultLivros = $conn->query($sqlLivros);
$temLivros = $resultLivros->num_rows > 0;

// Consulta 3: Séries que mais leram
$sqlSeries = "SELECT a.serie, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN aluno a ON e.id_aluno = a.id
              WHERE e.status = 2
              GROUP BY a.serie
              ORDER BY total DESC
              LIMIT 5";
$resultSeries = $conn->query($sqlSeries);
$temSeries = $resultSeries->num_rows > 0;

// Consulta para observações
$sqlNotas = "SELECT n.id, n.texto, n.data, p.nome AS professor_nome, CONVERT_TZ(data, '+00:00', '-03:00') AS data_corrigida
             FROM anotacoes n
             JOIN professor p ON n.id_professor = p.id
             ORDER BY n.data DESC";
$resultNotas = $conn->query($sqlNotas);
$temNotas = $resultNotas->num_rows > 0;

?>