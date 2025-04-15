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
    
    <!-- Link para a fonte do Google Fonts (exemplo: Roboto) -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <!-- Link para o CSS do Flatpickr -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    
</head>
<body>
    
    <!-- Header -->
    <header class="bg-primary text-white p-3">
        <h1 class="text-center">Biblioteca M.V.C</h1>
    </header>

    <!-- Conteúdo principal -->
    <div class="container mt-4">
        <h2>Registrar Empréstimo</h2>
        
        <form action="registrar_emprestimo.php" method="POST">
            <div class="mb-3">
                <label for="id_aluno" class="form-label">Aluno:</label>
                <select name="id_aluno" id="id_aluno" class="form-select" required>
                    <?php while ($aluno = $alunos->fetch_assoc()): ?>
                        <option value="<?php echo $aluno['id']; ?>"><?php echo $aluno['nome']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="id_livro" class="form-label">Livro:</label>
                <select name="id_livro" id="id_livro" class="form-select" required>
                    <?php while ($livro = $livros->fetch_assoc()): ?>
                        <option value="<?php echo $livro['id']; ?>"><?php echo $livro['nome_livro']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="data_emprestimo" class="form-label">Data de Empréstimo:</label>
                <input type="text" name="data_emprestimo" id="data_emprestimo" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="data_devolucao" class="form-label">Data de Devolução:</label>
                <input type="text" name="data_devolucao" id="data_devolucao" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-gradient w-100">Registrar Empréstimo</button>
        </form>

        <a href="painel.php" class="bg-primary text-white btn mt-3">Voltar ao Painel</a>
    </div>

    <!-- Script do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Script do Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <!-- Carregando o idioma português do Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

    <!-- Inicializando o Flatpickr nos campos de data -->
    <script>
        flatpickr("#data_emprestimo", {
            locale: "pt", // Define o idioma para português
            dateFormat: "d/m/Y", // Formato de data (dia/mês/ano)
            minDate: "today", // Impede a seleção de datas passadas
        });

        flatpickr("#data_devolucao", {
            locale: "pt", // Define o idioma para português
            dateFormat: "d/m/Y", // Formato de data (dia/mês/ano)
            minDate: "today", // Impede a seleção de datas passadas
        });
    </script>
</body>
</html>