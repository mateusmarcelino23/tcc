<?php
session_start();
// Conecta ao banco de dados
include '../conexao.php';

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Criptografa a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Consulta para inserir o professor no banco de dados
    $sql = "INSERT INTO professor (nome, cpf, email, senha) VALUES ('$nome', '$cpf', '$email', '$senha_hash')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Professor cadastrado com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao cadastrar professor: " . $conn->error . "</p>";
    }

    // Fecha a conexão
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Professor</title>
    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Vinculando o CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="../frontend/registrar.css">

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

    <!-- Voltar -->
    <div class="mt-3 text-start">
        <a href="ver_professores.php" class="link-back">< Voltar</a>
    </div>

    <div class="container">
    <h2 class="text-center">Cadastrar Professor</h2>

    <form id="cadastroForm" action="cadastrar_professor.php" method="POST" novalidate>
        <div class="mb-3">
            <label for="nome" class="form-label">Nome e Sobrenome:</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="cpf" class="form-label">CPF:</label>
            <input type="text" name="cpf" id="cpf" class="form-control" required 
                pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" 
                placeholder="000.000.000-00">
            <div class="invalid-feedback">Informe um CPF válido no formato 000.000.000-00.</div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label">Senha:</label>
            <input type="text" name="senha" id="senha" class="form-control" required
                pattern="[a-z0-9]{8,16}" minlength="8" maxlength="16"
                title="A senha deve conter apenas letras minúsculas e números, entre 8 e 16 caracteres.">
            <div class="invalid-feedback">Use apenas letras minúsculas e números (8-16 caracteres).</div>
        </div>

        <button type="submit" class="btn btn-gradient w-100">Cadastrar Professor</button>
    </form>
</div>

<!-- Máscara e validação via JS -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const cpfInput = document.getElementById("cpf");

        cpfInput.addEventListener("input", function (e) {
            let value = cpfInput.value.replace(/\D/g, ""); // Remove tudo que não for número
            if (value.length > 11) value = value.slice(0, 11); // Limita a 11 dígitos

            // Aplica a máscara: 000.000.000-00
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

            cpfInput.value = value;
        });

        // Validação do formulário
        const form = document.getElementById("cadastroForm");
        form.addEventListener("submit", function (e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                form.classList.add("was-validated");
            }
        });
    });
</script>

<!-- Bootstrap JS (corrigido link quebrado) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>