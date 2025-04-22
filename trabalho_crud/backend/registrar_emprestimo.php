<?php
session_start();
if (!isset($_SESSION['professor_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'crud_db');
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_aluno = $_POST['id_aluno'];
    $id_professor = $_SESSION['professor_id'];
    $id_livro = $_POST['id_livro'];
    $data_emprestimo = $_POST['data_emprestimo'];
    $data_devolucao = $_POST['data_devolucao'];

    $sql = "INSERT INTO emprestimo (id_aluno, id_professor, id_livro, data_emprestimo, data_devolucao)
            VALUES ('$id_aluno', '$id_professor', '$id_livro', '$data_emprestimo', '$data_devolucao')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Empréstimo registrado com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao registrar o empréstimo: " . $conn->error . "</p>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registrar Empréstimo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" type="text/css" href="../frontend/registrar.css">
</head>
<body>
<header>
    <div class="header">Biblioteca M.V.C</div>
</header>

<div class="mt-3 text-start">
    <a href="painel.php" class="link-back">&lt; Voltar para o painel</a>
</div>

<div class="container">
    <h2 class="text-center">Registrar Empréstimo</h2>
    <form action="registrar_emprestimo.php" method="POST">
        <!-- Campo Aluno -->
        <div class="mb-3">
            <label for="id_aluno" class="form-label">Aluno:</label>
            <select name="id_aluno" id="id_aluno" class="form-select" required style="width: 100%;"></select>
        </div>

        <!-- Campo Livro -->
        <div class="mb-3">
            <label for="id_livro" class="form-label">Livro:</label>
            <select name="id_livro" id="id_livro" class="form-select" required style="width: 100%;"></select>
        </div>

        <!-- Datas -->
        <div class="mb-3">
            <label for="data_emprestimo" class="form-label">Data de Empréstimo:</label>
            <input type="date" name="data_emprestimo" id="data_emprestimo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="data_devolucao" class="form-label">Data de Devolução:</label>
            <input type="date" name="data_devolucao" id="data_devolucao" class="form-control" required>
        </div>

        <!-- Botão -->
        <button type="submit" class="btn btn-gradient w-100">Registrar Empréstimo</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    function initSelect2(selector, url, placeholderText) {
        $(selector).select2({
            placeholder: placeholderText,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { term: params.term };
                },
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            },
            minimumInputLength: 1,
            language: {
                noResults: function () {
                    return "Nenhum resultado encontrado";
                }
            }
        });
    }

    initSelect2('#id_aluno', 'buscar_alunos.php', 'Digite o nome do aluno');
    initSelect2('#id_livro', 'buscar_livros.php', 'Digite o nome do livro');
});
</script>
</body>
</html>