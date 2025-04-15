<?php
// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: login.php");
    exit();
}

// Conecta com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'crud_db');

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro ao conectar com o banco de dados: " . $conn->connect_error);
}

// Consulta para buscar o empréstimo a ser editado
$id = intval($_GET['id']);
$sql = "SELECT e.id, e.data_emprestimo, e.data_devolucao, a.nome AS aluno_nome, l.nome_livro, l.nome_autor, p.nome AS professor_nome 
        FROM emprestimo e
        JOIN aluno a ON e.id_aluno = a.id
        JOIN livro l ON e.id_livro = l.id
        JOIN professor p ON e.id_professor = p.id
        WHERE e.id = $id";
$result = $conn->query($sql);

// Verifica se o empréstimo existe
if ($result->num_rows == 0) {
    header("Location: ver_emprestimos.php");
    exit();
}

// Busca os dados do empréstimo
$emprestimo = $result->fetch_assoc();

// Fecha a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Empréstimo</title>
    
    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Link para a fonte do Google Fonts (exemplo: Roboto) -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <!-- Link para o CSS do Flatpickr -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    
</head>

<body>
    <header class="bg-primary text-white p-3">
        <h1 class="text-center">Biblioteca M.V.C</h1>
    </header>

    <main class="container mt-3">
        <h2 class="text-center">Editar Empréstimo</h2>

        <form action="editar_emprestimo.php?id=<?php echo $id; ?>" method="POST">
            <div class="mb-3">
                <label for="data_emprestimo" class="form-label">Data de Empréstimo:</label>
                <input type="text" name="data_emprestimo" id="data_emprestimo" class="form-control" value="<?php echo date("d/m/Y", strtotime($emprestimo['data_emprestimo'])); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="data_devolucao" class="form-label">Data de Devolução:</label>
                <input type="text" name="data_devolucao" id="data_devolucao" class="form-control" value="<?php echo date("d/m/Y", strtotime($emprestimo['data_devolucao'])); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-gradient w-100">Editar Empréstimo</button>
        </form>

        <a href="ver_emprestimos.php" class="btn btn-secondary mt-3">Voltar</a>
    </main>

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

<?php
// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conecta novamente para salvar o empréstimo
    $conn = new mysqli('localhost', 'root', '', 'crud_db');
    
    // Verifica a conexão
    if ($conn->connect_error) {
        die("Erro ao conectar com o banco de dados: " . $conn->connect_error);
    }

    // Recebe os dados do formulário
    $data_emprestimo = $_POST['data_emprestimo'];
    $data_devolucao = $_POST['data_devolucao'];

    // Consulta para atualizar o empréstimo
    $sql = "UPDATE emprestimo SET data_emprestimo = '$data_emprestimo', data_devolucao = '$data_devolucao' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: ver_emprestimos.php");
        exit();
    } else {
        die("Erro ao atualizar o empréstimo: " . $conn->error);
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
}
