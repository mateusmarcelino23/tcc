<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: login.php");
    exit();
}

// Conectar com o banco de dados
include '../conexao.php';

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Verifica se a ação de remover foi solicitada
if (isset($_GET['remover'])) {
    $id = intval($_GET['remover']); // ID do livro a ser removido

    // Verifica se o livro está em algum empréstimo
    $checkSql = "SELECT * FROM emprestimo WHERE id_livro = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $resultCheck = $checkStmt->get_result();

    if ($resultCheck->num_rows > 0) {
        echo "<script>alert('Este livro está vinculado a um empréstimo. Primeiro remova o empréstimo para depois excluir o livro.');</script>";
    } else {
        // Remove o livro
        $deleteSql = "DELETE FROM livro WHERE id = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Redireciona silenciosamente após exclusão
        header("Location: ver_livros.php");
        exit();
    }

    $checkStmt->close();
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
    <link rel="stylesheet" type="text/css" href="../estilos/ver.css">

<div class="mt-3 text-start">
<a href="../../index.php" class="link-back">< Voltar para o painel</a>
</div>

    
</head>
<body>
    <!-- Cabeçalho -->
    <nav class="header">Biblioteca M.V.C
            <!-- Botão para abrir/fechar o menu lateral -->
            <span id="toggleSidebar" class="openbtn" onclick="toggleNav()">&#9776;</span>


            <script>
                function toggleNav() {
                    const sidebar = document.getElementById("mySidebar");
                    const toggleBtn = document.getElementById("toggleSidebar");

                    if (sidebar.classList.contains("open")) {
                        sidebar.classList.remove("open");
                        toggleBtn.innerHTML = "&#9776;"; // ícone de abrir
                    } else {
                        sidebar.classList.add("open");
                        toggleBtn.innerHTML = "&times;"; // ícone de fechar
                    }
                }
            </script>

    </nav>

    <!-- Menu lateral -->
    <div class="sidebar" id="mySidebar">
        <ul>
            <li><a href="relatorios.php">Relatórios</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

<a href="cadastrar_livros.php" class="link-registrar">Cadastrar Livro</a>

<div class="container mt-4">
    <h2 class="text-center">Lista de Livros</h2>
    <div class="text-end mb-2">
    
    <div class="table-container">
    <table id="emprestimosTable" class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Autor</th>
                <th>ISBN</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while ($row = $result->fetch_assoc()) { 
                echo "<tr>"; 
                echo "<td>" . $row['nome_livro'] . "</td>"; 
                echo "<td>" . $row['nome_autor'] . "</td>"; 
                echo "<td>" . $row['isbn'] . "</td>";
                echo "<td><a href='?remover=" . $row['id'] . "' class='delete-link' onclick='return confirm(\"Tem certeza de que deseja remover este livro?\")'>Remover</a></td>"; 
            } 
            ?>
        </tbody>
    </table>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#emprestimosTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
        }
    });
});
</script>

<!-- Link para a tratativa do JS -->
<script src="../tratativa/script.js"></script>

</body>
</html>