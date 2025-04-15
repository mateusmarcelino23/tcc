<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: login.php");
    exit();
}

// Conectar com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'crud_db');

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Remover empréstimo se o ID for passado via GET
if (isset($_GET['remover_id'])) {
    $id = intval($_GET['remover_id']);
    $conn->query("DELETE FROM emprestimo WHERE id = $id");
    header("Location: painel.php");
    exit();
}

// Consulta para buscar todos os empréstimos
$sql = "SELECT e.id, e.data_emprestimo, e.data_devolucao, a.nome AS aluno_nome, l.nome_livro, l.nome_autor 
        FROM emprestimo e
        JOIN aluno a ON e.id_aluno = a.id
        JOIN livro l ON e.id_livro = l.id";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel da Biblioteca</title>

    <!-- Link para conexão com Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Link para o CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="../frontend/painel.css">

</head>
<body>
    <!-- Cabeçalho -->
    <div class="header">Biblioteca M.V.C</div>

    <!-- Mensagem de boas-vindas -->
    <div class="welcome">
        Bem-vindo, <?php echo $_SESSION['professor_nome']; ?>!
    </div>

    <!-- mensagem acima do container-->
    <div class="explanation">
        Painel do Professor
    </div>

    <!-- Container do painel -->
    <div class="container">
        <table class="table table-bordered">
            <tr>
                <td><a href="ver_emprestimos.php" class="btn btn-primary">Empréstimos</a></td>
            </tr>
            <tr>
                <td><a href="ver_livros.php" class="btn btn-primary">Livros</a></td>
            </tr>
            <tr>
                <td><a href="ver_alunos.php" class="btn btn-primary">Alunos</a></td>
            </tr>
            <tr>
                <td><a href="ver_professores.php" class="btn btn-primary">Professores</a></td>
            </tr>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Função para abrir o menu lateral
        function openNav() {
            document.getElementById("mySidebar").style.right = "0";  // Abrir menu da direita
        }

        // Função para fechar o menu lateral
        function closeNav() {
            document.getElementById("mySidebar").style.right = "-250px";  // Fechar menu da direita
        }
    </script>

</body>
</html>