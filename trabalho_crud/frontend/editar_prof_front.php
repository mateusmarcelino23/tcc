<?php
include '../backend/editar_prof.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Professor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../estilos/style.css">
    <link rel="stylesheet" type="text/css" href="../estilos/registrar.css">
</head>

<body>
    <!-- Cabeçalho -->
    <nav class="header">
        <a href="../../" class="header-link">
            <img src="../imagens/1748908346791.png" alt="Logo" class="header-logo" />
            Biblioteca M.V.C
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
        <a href="ver_professores_front.php" class="link-back">
            < Voltar</a>
    </div>

    <div class="container">
        <h2 class="text-center">Editar Professor</h2>

        <form action="../backend/editar_prof.php?id=<?= $prof_id ?>" id="editarForm" method="POST" novalidate>
            <div class="mb-3">
                <label for="nome" class="form-label">Nome e Sobrenome:</label>
                <input type="text" name="nome" id="nome" class="form-control" required value="<?= htmlspecialchars($prof['nome']) ?>" autocomplete="off">
            </div>

            <div class="mb-3">
                <label for="cpf" class="form-label">CPF:</label>
                <input type="text" name="cpf" id="cpf" class="form-control" autocomplete="off" required
                    pattern="\d{3}\.\d{3}\.\d{3}-\d{2}"
                    value="<?= htmlspecialchars(preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $prof['cpf'])) ?>"
                    placeholder="000.000.000-00">
                <div class="invalid-feedback">Informe um CPF válido no formato 000.000.000-00.</div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required value="<?= htmlspecialchars($prof['email']) ?> " autocomplete="off">
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Nova Senha (opcional):</label>
                <input type="text" name="senha" id="senha" class="form-control" autocomplete="off"
                    pattern="[a-z0-9]{8,16}" minlength="8" maxlength="16"
                    title="A senha deve conter apenas letras minúsculas e números, entre 8 e 16 caracteres.">
                <div class="invalid-feedback">Use apenas letras minúsculas e números (8-16 caracteres).</div>
            </div>

            <button type="submit" class="btn btn-gradient w-100">Salvar Alterações</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cpfInput = document.getElementById("cpf");

            cpfInput.addEventListener("input", function() {
                let value = cpfInput.value.replace(/\D/g, "");
                if (value.length > 11) value = value.slice(0, 11);
                value = value.replace(/(\d{3})(\d)/, "$1.$2");
                value = value.replace(/(\d{3})(\d)/, "$1.$2");
                value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
                cpfInput.value = value;
            });

            const form = document.getElementById("editarForm");
            form.addEventListener("submit", function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    form.classList.add("was-validated");
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Link para arquivos JS -->
    <script src="../interatividade/script.js"></script>
    <script src="../interatividade/devtools_block.js"></script>
    <script src="../interatividade/logout.js"></script>

</body>

</html>