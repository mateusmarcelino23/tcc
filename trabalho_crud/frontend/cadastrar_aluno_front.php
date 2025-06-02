<?php
include '../backend/cadastrar_aluno.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Aluno</title>

    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Vinculando o CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="../estilos/style.css">
    <link rel="stylesheet" type="text/css" href="../estilos/registrar.css">

</head>

<body>
    <!-- Cabeçalho -->
    <nav class="header">
        <a href="../../" class="header-link">Biblioteca M.V.C</a>
        <span id="toggleSidebar" class="openbtn" onclick="toggleNav()">&#9776;</span>
        <script>
            function toggleNav() {
                const sidebar = document.getElementById("mySidebar");
                const toggleBtn = document.getElementById("toggleSidebar");
                sidebar.classList.toggle("open");
                toggleBtn.innerHTML = sidebar.classList.contains("open") ? "&times;" : "&#9776;";
            }
        </script>
    </nav>


    <!-- Menu lateral -->
    <div class="sidebar" id="mySidebar">
        <ul>
            <li><a href="relatorios_front.php">Relatórios</a></li>
            <li><a href="../backend/logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Voltar -->
    <div class="mt-3 text-start">
        <a href="ver_alunos_front.php" class="link-back">
            < Voltar</a>
    </div>

    <!-- Mensagem de feedback -->
    <div class="mensagem">
        <?php
        if (isset($_SESSION['mensagem_aluno'])) {
            echo $_SESSION['mensagem_aluno'];
            unset($_SESSION['mensagem_aluno']);
        }
        ?>
    </div>

    <div class="container">
        <h2 class="text-center">Cadastrar Aluno</h2>

        <form action="../backend/cadastrar_aluno.php" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome completo:</label>
                <input type="text" name="nome" id="nome" class="form-control" autocomplete="off" required>
            </div>

            <div class="mb-3 d-flex">
                <div class="flex-grow-1 me-2">
                    <label for="ano" class="form-label">Ano:</label>
                    <select name="ano" id="ano" class="form-select" required>
                        <option value="">Selecione o ano</option>
                        <option value="6">6º Ano</option>
                        <option value="7">7º Ano</option>
                        <option value="8">8º Ano</option>
                        <option value="9">9º Ano</option>
                        <option value="1">1º Ano EM</option>
                        <option value="2">2º Ano EM</option>
                        <option value="3">3º Ano EM</option>
                    </select>
                </div>
                <div class="flex-shrink-1">
                    <label for="sala" class="form-label">Classe:</label>
                    <input type="text" name="sala" id="sala" class="form-control" maxlength="1" autocomplete="off" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" autocomplete="off" required>
            </div>

            <button type="submit" class="btn btn-gradient w-100">Cadastrar</button>
        </form>
    </div>

    <script>
        document.getElementById('sala').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    </script>


    <!-- Link para o JavaScript do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Link para a tratativa do JS -->
    <script src="../tratativa/script.js"></script>

</body>

</html>