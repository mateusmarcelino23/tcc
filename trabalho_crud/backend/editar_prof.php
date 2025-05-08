<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'crud_db');

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

$mensagem = '';

// Verifica se o ID foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Busca os dados do professor
    $sql = "SELECT * FROM professor WHERE id = $id";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $professor = $resultado->fetch_assoc();
    } else {
        die("Professor não encontrado.");
    }
} else {
    die("ID do professor não fornecido.");
}

// Atualiza os dados quando o formulário é enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if (!empty($senha)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE professor SET nome='$nome', cpf='$cpf', email='$email', senha='$senha_hash' WHERE id=$id";
    } else {
        $sql = "UPDATE professor SET nome='$nome', cpf='$cpf', email='$email' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Professor atualizado com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao atualizar: " . $conn->error . "</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Professor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../frontend/registrar.css">
</head>
<body>
    <nav class="header">Biblioteca M.V.C
        <span id="toggleSidebar" class="openbtn" onclick="toggleNav()">&#9776;</span>
    </nav>

    <div class="sidebar" id="mySidebar">
        <ul>
            <li><a href="info_prof.php">Informações do professor</a></li>
            <li><a href="configuracoes.php">Configurações</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <script>
        function toggleNav() {
            const sidebar = document.getElementById("mySidebar");
            const toggleBtn = document.getElementById("toggleSidebar");

            if (sidebar.classList.contains("open")) {
                sidebar.classList.remove("open");
                toggleBtn.innerHTML = "&#9776;";
            } else {
                sidebar.classList.add("open");
                toggleBtn.innerHTML = "&times;";
            }
        }
    </script>

    <div class="mt-3 text-start">
        <a href="ver_professores.php" class="link-back">< Voltar</a>
    </div>

    <div class="container">
        <h2 class="text-center">Editar Professor</h2>

        <?= $mensagem ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($professor['nome']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="cpf" class="form-label">CPF:</label>
                <input type="text" name="cpf" id="cpf" class="form-control" value="<?= htmlspecialchars($professor['cpf']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($professor['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Nova Senha (deixe em branco se não for alterar):</label>
                <input type="password" name="senha" id="senha" class="form-control">
            </div>

            <button type="submit" class="btn btn-gradient w-100">Salvar Alterações</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>