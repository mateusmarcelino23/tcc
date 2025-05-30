<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: login.php"); // Redireciona para o login se não estiver logado
    exit();
}

// Conectar com o banco de dados
include '../conexao.php';

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Verifica se foi solicitado remover um aluno
if (isset($_GET['remover'])) {
    $aluno_id = $_GET['remover'];

    // Verifica se o aluno está em algum empréstimo
    $check_emprestimo = "SELECT * FROM emprestimo WHERE id_aluno = $aluno_id";
    $result_check = $conn->query($check_emprestimo);

    if ($result_check->num_rows > 0) {
        echo "<script>alert('O aluno está registrado em um empréstimo. Primeiro remova o empréstimo para depois excluir o aluno.');</script>";
    } else {
        // Remove o aluno
        $sql_remover = "DELETE FROM aluno WHERE id = $aluno_id";
        $conn->query($sql_remover);

        // Redireciona silenciosamente após exclusão
        header("Location: ver_alunos.php");
        exit();
    }
}

// Consulta para buscar todos os alunos
$sql = "SELECT * FROM aluno";
$result = $conn->query($sql);

// Fechar a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Alunos</title>

    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Link para o datatables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- Vinculando o CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="../estilos/ver.css">

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

        <div class="mt-3 text-start">
        <a href="../../" class="link-back">< Voltar para o painel</a>
        </div>

        <div class="container mt-5">
            <h2 class="text-center">Lista de Alunos</h2>
            <div class="text-end mb-2">
                <a href="cadastrar_aluno.php" class="link-registrar">Cadastrar Aluno</a>
            </div>
            <div class="table-container">
            <table id="emprestimosTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Série</th>
                        <th>Email</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    while ($row = $result->fetch_assoc()) { 
                        echo "<tr>";
                        echo "<td>" . $row['nome'] . "</td>";
                        echo "<td>" . $row['serie'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td><a href='editar_aluno.php?id=" . $row['id'] . "' class='edit-link'>Editar</a></td>";
                        echo "<td><a href='?remover=" . $row['id'] . "' class='delete-link' onclick='return confirm(\"Tem certeza de que deseja remover este aluno?\")'>Remover</a></td>";
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