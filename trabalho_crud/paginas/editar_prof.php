<?php
session_start();
include '../conexao.php';

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Verifica se o ID do professor foi passado
if (!isset($_GET['id'])) {
    echo "<p style='color: red;'>ID do professor não especificado.</p>";
    exit;
}

$id = intval($_GET['id']);

// Atualização (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = preg_replace('/\D/', '', $_POST['cpf']); // Remove pontos e traços
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if (!empty($senha)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE professor SET nome='$nome', cpf='$cpf', email='$email', senha='$senha_hash' WHERE id=$id";
    } else {
        $sql = "UPDATE professor SET nome='$nome', cpf='$cpf', email='$email' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Dados atualizados com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao atualizar: " . $conn->error . "</p>";
    }
}

// Recupera dados do professor
$result = $conn->query("SELECT * FROM professor WHERE id = $id");
if ($result->num_rows != 1) {
    echo "<p style='color: red;'>Professor não encontrado.</p>";
    exit;
}
$prof = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Professor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../estilos/registrar.css">
</head>
<body>
<nav class="header">Biblioteca M.V.C
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

    <div class="sidebar" id="mySidebar">
        <ul>
            <li><a href="relatorios.php">Relatórios</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

<div class="mt-3 text-start">
    <a href="ver_professores.php" class="link-back">< Voltar</a>
</div>

<div class="container">
    <h2 class="text-center">Editar Professor</h2>

    <form id="editarForm" method="POST" novalidate>
        <div class="mb-3">
            <label for="nome" class="form-label">Nome e Sobrenome:</label>
            <input type="text" name="nome" id="nome" class="form-control" required value="<?= htmlspecialchars($prof['nome']) ?>">
        </div>

        <div class="mb-3">
            <label for="cpf" class="form-label">CPF:</label>
            <input type="text" name="cpf" id="cpf" class="form-control" required
                   pattern="\d{3}\.\d{3}\.\d{3}-\d{2}"
                   value="<?= htmlspecialchars(preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $prof['cpf'])) ?>"
                   placeholder="000.000.000-00">
            <div class="invalid-feedback">Informe um CPF válido no formato 000.000.000-00.</div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control" required value="<?= htmlspecialchars($prof['email']) ?>">
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label">Nova Senha (opcional):</label>
            <input type="text" name="senha" id="senha" class="form-control"
                   pattern="[a-z0-9]{8,16}" minlength="8" maxlength="16"
                   title="A senha deve conter apenas letras minúsculas e números, entre 8 e 16 caracteres.">
            <div class="invalid-feedback">Use apenas letras minúsculas e números (8-16 caracteres).</div>
        </div>

        <button type="submit" class="btn btn-gradient w-100">Salvar Alterações</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const cpfInput = document.getElementById("cpf");

        cpfInput.addEventListener("input", function () {
            let value = cpfInput.value.replace(/\D/g, "");
            if (value.length > 11) value = value.slice(0, 11);
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            cpfInput.value = value;
        });

        const form = document.getElementById("editarForm");
        form.addEventListener("submit", function (e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                form.classList.add("was-validated");
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>