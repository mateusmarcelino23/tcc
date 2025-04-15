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

// Consulta para buscar todos os livros
$sql = "SELECT * FROM livro";
$result = $conn->query($sql);

// Fecha a conexão
$conn->close();
?>

<!DOCTYPE html> 
<html lang="pt-BR"> 
<head> 
<meta charset="UTF-8">
    <title>Lista de Livros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../frontend/ver.css">

<div class="mt-3 text-start">
<a href="painel.php" class="link-back">< Voltar para o painel</a>
</div>

    
</head>
<body>
<div class="header">Biblioteca M.V.C</div>
</div>

<a href="cadastrar_livros.php" class="link-registrar">Cadastrar Livro</a>

<div class="container mt-4">
    <h2 class="text-center">Lista de Livros</h2>
    <div class="text-end mb-2">
    
    <table id="emprestimosTable" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Autor</th>
                <th>ISBN</th>
                <th>Remover</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while ($row = $result->fetch_assoc()) { 
                echo "<tr>"; 
                echo "<td>" . $row['id'] . "</td>"; 
                echo "<td>" . $row['nome_livro'] . "</td>"; 
                echo "<td>" . $row['nome_autor'] . "</td>"; 
                echo "<td>" . $row['isbn'] . "</td>"; 
                echo "<td><a href='?remover=" . $row['id'] . "' class='delete-link' onclick='return confirm(\"Tem certeza de que deseja remover este livro?\")'>Remover</a></td>"; 
            } 
            ?>
        </tbody>
    </table>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#emprestimosTable').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
        }
    });
});
</script>
</body>

</html>
