<?php
include 'trabalho_crud/backend/painel.php';
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
    <link rel="stylesheet" type="text/css" href="trabalho_crud/estilos/style.css">
    <link rel="stylesheet" type="text/css" href="trabalho_crud/estilos/painel.css">

</head>

<body>
    <!-- Cabeçalho -->
    <nav class="header">
        <a href="" class="header-link">
            <img src="trabalho_crud/imagens/1748908346791.png" alt="Logo" class="header-logo" />
            <span class="header-text">Biblioteca M.V.C </span>
        </a>
        <span id="toggleSidebar" class="openbtn" onclick="toggleNav()">&#9776;</span>
    </nav>

    <!-- Menu lateral -->
    <div class="sidebar" id="mySidebar">
        <ul>
            <li><a href="trabalho_crud/frontend/relatorios_front.php">Relatórios</a></li>
            <li><a href="trabalho_crud/backend/logout.php" id="logoutLink">Logout</a></li>
        </ul>
    </div>

    <!-- Mensagem de boas-vindas -->
    <div class="welcome">
        Bem-vindo(a), <?php echo $_SESSION['professor_primeiro_nome']; ?>!
    </div>


    <!-- abaixo da mensagem de boas-vindas -->
    <div class="explanation">
        Painel do Professor
    </div>

    <!-- Container do painel -->
    <div class="container">
        <div class="btn-container">

            <a href="trabalho_crud/frontend/ver_emprestimos_front.php" class="btn">Empréstimos</a>

            <a href="trabalho_crud/frontend/cadastrar_livros_front.php" class="btn">Cadastrar Livros</a>

            <a href="trabalho_crud/frontend/ver_alunos_front.php" class="btn">Alunos</a>

            <a href="trabalho_crud/frontend/ver_professores_front.php" class="btn">Professores</a>

        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Link para arquivos JS -->
    <script src="trabalho_crud/interatividade/script.js"></script>
    <script src="trabalho_crud/interatividade/devtools_block.js"></script>
    <script src="trabalho_crud/interatividade/logout.js"></script>

</body>

</html>