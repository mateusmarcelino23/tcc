<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: login.php"); // Redireciona para o login se não estiver logado
    exit();
}

// Conecta com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'crud_db');

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta para obter alunos
$alunos = $conn->query("SELECT * FROM aluno");

// Consulta para obter livros
$livros = $conn->query("SELECT * FROM livro");

// Fechar a conexão
$conn->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conecta novamente para salvar o empréstimo
    $conn = new mysqli('localhost', 'root', '', 'crud_db');
    
    // Verifica a conexão
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Recebe os dados do formulário
    $id_aluno = $_POST['id_aluno'];
    $id_livro = $_POST['id_livro'];
    $data_emprestimo = $_POST['data_emprestimo'];
    $data_devolucao = $_POST['data_devolucao'];
    $id_professor = $_SESSION['professor_id'];

    // Consulta para registrar o empréstimo
    $sql = "INSERT INTO emprestimo (id_aluno, id_professor, id_livro, data_emprestimo, data_devolucao)
            VALUES ('$id_aluno', '$id_professor', '$id_livro', '$data_emprestimo', '$data_devolucao')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Empréstimo registrado com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao registrar o empréstimo: " . $conn->error . "</p>";
    }

    // Fecha a conexão
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registrar Empréstimo</title>
    
    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Link para o CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="../frontend/registrar.css">

</head>

<body>

    <!-- Cabeçalho -->
    <header>
        <div class="header">Biblioteca M.V.C</div>
    </header>

    <!-- Voltar ao painel -->
    <div class="mt-3 text-start">
        <a href="painel.php" class="link-back">< Voltar para o painel</a>
    </div>

    <div class="container">
        <h2 class="text-center">Registrar Empréstimo</h2>
        <form action="registrar_emprestimo.php" method="POST">
            <div class="mb-3">
                <label for="id_aluno" class="form-label">Aluno:</label>
                <select name="id_aluno" id="id_aluno" class="form-select" required>
                    <option value="">Selecione um aluno</option>
                    <?php while ($aluno = $alunos->fetch_assoc()) : ?>
                        <option value="<?= $aluno['id'] ?>"><?= $aluno['nome'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="id_livro" class="form-label">Livro:</label>
                <select name="id_livro" id="id_livro" class="form-select" required>
                    <option value="">Selecione um livro</option>
                    <?php while ($livro = $livros->fetch_assoc()) : ?>
                        <option value="<?= $livro['id'] ?>"><?= $livro['nome_livro'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="data_emprestimo" class="form-label">Data de Emprestimo:</label>
                <input type="date" name="data_emprestimo" id="data_emprestimo" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="data_devolucao" class="form-label">Data de Devolução:</label>
                <input type="date" name="data_devolucao" id="data_devolucao" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-gradient w-100">Registrar Empréstimo</button>
        </form>
    </div>

        <!-- Link para o JavaScript do Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js
    /bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>