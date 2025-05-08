<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: login.php");
    exit();
}

// Conecta com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'crud_db');
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o ID do aluno foi passado
if (!isset($_GET['id'])) {
    echo "ID do aluno não fornecido.";
    exit();
}

$aluno_id = $_GET['id'];

// Atualiza os dados se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $ano = $_POST['ano'];
    $sala = $_POST['sala'];
    $email = $_POST['email'];
    $serie = $ano . 'º Ano ' . $sala;

    $sql = "UPDATE aluno SET nome='$nome', serie='$serie', email='$email' WHERE id=$aluno_id";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Aluno atualizado com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao atualizar: " . $conn->error . "</p>";
    }
}

// Busca os dados do aluno
$sql = "SELECT * FROM aluno WHERE id=$aluno_id";
$result = $conn->query($sql);
if ($result->num_rows != 1) {
    echo "Aluno não encontrado.";
    exit();
}

$aluno = $result->fetch_assoc();

// Extrai ano e sala da série
preg_match('/(\d+)º Ano\s*(\w)/', $aluno['serie'], $match);
$ano = $match[1] ?? '';
$sala = $match[2] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Aluno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../frontend/registrar.css">
</head>
<body>

<nav class="header">Biblioteca M.V.C
    <span id="toggleSidebar" class="openbtn" onclick="toggleNav()">&#9776;</span>
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
</nav>

<div class="sidebar" id="mySidebar">
    <ul>
        <li><a href="info_prof.php">Informações do professor</a></li>
        <li><a href="configuracoes.php">Configurações</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="mt-3 text-start">
    <a href="ver_alunos.php" class="link-back">< Voltar</a>
</div>

<div class="container">
    <h2 class="text-center">Editar Aluno</h2>

    <form action="editar_aluno.php?id=<?= $aluno_id ?>" method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($aluno['nome']) ?>" required>
        </div>

        <div class="mb-3 d-flex">
            <div class="flex-grow-1 me-2">
                <label for="ano" class="form-label">Ano:</label>
                <select name="ano" id="ano" class="form-select" required>
                    <option value="">Selecione o ano</option>
                    <?php
                    $anos = [6, 7, 8, 9, 1, 2, 3];
                    foreach ($anos as $a) {
                        $selected = ($a == $ano) ? "selected" : "";
                        echo "<option value='$a' $selected>$aº Ano</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="flex-shrink-1">
                <label for="sala" class="form-label">Sala:</label>
                <input type="text" name="sala" id="sala" class="form-control" maxlength="1" value="<?= htmlspecialchars($sala) ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($aluno['email']) ?>" required>
        </div>

        <button type="submit" class="btn btn-gradient w-100">Atualizar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>