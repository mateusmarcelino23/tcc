<?php
include '../backend/ver_alunos.php'; // Inclui o script de backend para ver alunos
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
    <link rel="stylesheet" type="text/css" href="../estilos/style.css">
    <link rel="stylesheet" type="text/css" href="../estilos/ver.css">

</head>

<body>
    <!-- Cabeçalho -->
    <nav class="header">
        <a href="../../" class="header-link">
            <img src="../imagens/1748908346791.png" alt="Logo" class="header-logo" />
            <span class="header-text">Biblioteca M.V.C </span>
        </a>
        <span id="toggleSidebar" class="openbtn" onclick="toggleNav()">&#9776;</span>
    </nav>

    <!-- Menu lateral -->
    <div class="sidebar" id="mySidebar">
        <ul>
            <li><a href="relatorios_front.php">Relatórios</a></li>
            <li><a href="../backend/logout.php" id="logoutLink">Logout</a></li>
        </ul>
    </div>

    <div class="mt-3 text-start">
        <a href="../../" class="link-back">
            < Voltar para o painel</a>
    </div>

    <div class="mt-3 text-start">
        <a href="../../" class="link-back responsive-link">
            < Voltar</a>
    </div>

    <div class="container mt-5">
        <h2 class="text-center">Lista de Alunos</h2>

        <div class="text-end mb-2">
            <a href="cadastrar_aluno_front.php" class="link-registrar">Cadastrar Aluno</a>
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
                        echo "<td class='scrollable-cell'>" . $row['nome'] . "</td>";
                        echo "<td class='scrollable-cell'>" . $row['serie'] . "</td>";
                        echo "<td class='scrollable-cell'>" . $row['email'] . "</td>";
                        echo "<td class='scrollable-cell'><a href='editar_aluno_front.php?id=" . $row['id'] . "' class='edit-link'>Editar</a></td>";
                        echo "<td class='scrollable-cell'><a href='?remover=" . $row['id'] . "' class='delete-link' onclick='return confirm(\"Tem certeza de que deseja remover este aluno?\")'>Remover</a></td>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

        <!-- Link para arquivos JS -->
        <script src="../interatividade/script.js"></script>
        <script src="../interatividade/devtools_block.js"></script>
        <script src="../interatividade/logout.js"></script>
        <script src="../interatividade/ver.js"></script>
</body>

</html>