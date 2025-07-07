<?php
include '../backend/editar_aluno.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Aluno</title>
    <link rel="icon" href="../imagens/1748908346791.png" type="image/x-icon">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../estilos/style.css">
    <link rel="stylesheet" type="text/css" href="../estilos/registrar.css">
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
        <a href="ver_alunos_front.php" class="link-back">
            < Voltar</a>
    </div>

    <div class="mensagem">
        <?php
        if (isset($_SESSION['mensagem_editar_aluno'])) {
            echo $_SESSION['mensagem_editar_aluno'];
            unset($_SESSION['mensagem_editar_aluno']);
        }
        ?>
    </div>

    <div class="container">
        <h2 class="text-center">Editar Aluno</h2>

        <form action="../backend/editar_aluno.php?id=<?= $aluno_id ?>" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome completo:</label>
                <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($aluno['nome']) ?>" autocomplete="off" required>
            </div>

            <div class="mb-3 d-flex">
                <div class="flex-grow-1 me-2">
                    <label for="ano" class="form-label">Ano:</label>
                    <select name="ano" id="ano" class="form-select" required>
                        <option value="">Selecione o ano</option>
                        <option value="6" <?= $ano == '6' ? 'selected' : '' ?>>6º Ano</option>
                        <option value="7" <?= $ano == '7' ? 'selected' : '' ?>>7º Ano</option>
                        <option value="8" <?= $ano == '8' ? 'selected' : '' ?>>8º Ano</option>
                        <option value="9" <?= $ano == '9' ? 'selected' : '' ?>>9º Ano</option>
                        <option value="1" <?= $ano == '1' ? 'selected' : '' ?>>1º Ano EM</option>
                        <option value="2" <?= $ano == '2' ? 'selected' : '' ?>>2º Ano EM</option>
                        <option value="3" <?= $ano == '3' ? 'selected' : '' ?>>3º Ano EM</option>
                    </select>

                </div>
                <div class="flex-shrink-1">
                    <label for="sala" class="form-label">Classe:</label>
                    <input type="text" name="sala" id="sala" class="form-control" maxlength="1" value="<?= htmlspecialchars($sala) ?>" autocomplete="off" required>
                </div>
                <script>
                    document.getElementById('sala').addEventListener('input', function() {
                        this.value = this.value.toUpperCase();
                    });
                </script>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($aluno['email']) ?>" autocomplete="off" required>
            </div>

            <button type="submit" class="btn btn-gradient w-100">Atualizar</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Link para arquivos JS -->
    <script src="../interatividade/script.js"></script>
    <script src="../interatividade/devtools_block.js"></script>
    <script src="../interatividade/logout.js"></script>

</body>

</html>