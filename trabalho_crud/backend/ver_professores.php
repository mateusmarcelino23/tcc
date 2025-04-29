<?php
// Inicia a sessão
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: login.php"); // Redireciona para o login se não estiver logado
    exit();
}

// Conectar com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'crud_db');

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta para buscar todos os professores
$sql = "SELECT * FROM professor";
$result = $conn->query($sql);

// Fecha a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Professores</title>
    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Link para o datatables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- Link para o CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="../frontend/ver.css">

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
            <li><a href="info_prof.php">Informações do professor</a></li>
            <li><a href="configuracoes.php">Configurações</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li><a href="ver_professores.php">Professores</a></li>
        </ul>
    </div>

    <!-- Voltar ao painel -->
    <div class="mt-3 text-start">
        <a href="painel.php" class="link-back">< Voltar para o painel</a>
    </div>

    <!-- Cadastrar professor -->
    <div class="mt-3 text-end">
        <a href="cadastrar_professor.php" class="link-registrar">Cadastrar Professor</a>
    </div>
    
    <div class="container">
        <h2 class="text-center">Professores Cadastrados</h2>
        <table id="professoresTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Editar</th>
                    <th>Remover</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nome'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td><a href='editar_professor.php?id=" . $row['id'] . "' class='edit-link'>Editar</a></td>";
                    echo "<td><a href='?remover=" . $row['id'] . "' class='delete-link' onclick='return confirm(\"Tem certeza de que deseja remover este professor?\")'>Remover</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#professoresTable').DataTable();
        });
    </script>

</body>
</html>