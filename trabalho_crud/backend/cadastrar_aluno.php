<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: login.php"); // Redireciona para o login se não estiver logado
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conecta com o banco de dados
    $conn = new mysqli('localhost', 'root', '', 'crud_db');

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $serie = $_POST['serie'];
    $email = $_POST['email'];

    // Consulta para inserir o aluno no banco de dados
    $sql = "INSERT INTO aluno (nome, serie, email) VALUES ('$nome', '$serie', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Aluno cadastrado com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao cadastrar aluno: " . $conn->error . "</p>";
    }

    // Fecha a conexão
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Aluno</title>

    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Vinculando o CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="../frontend/registrar.css">

</head>
<body>

    <!-- Cabeçalho -->
    <header>
        <div class="header">Biblioteca M.V.C</div>
    </header>

    <!-- Voltar -->
    <div class="mt-3 text-start">
        <a href="ver_alunos.php" class="link-back">< Voltar</a>
    </div>

    <div class="container">
        <h2 class="text-center">Cadastrar Aluno</h2>

        <form action="cadastrar_aluno.php" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" name="nome" id="nome" class="form-control" required>
            </div>

            <div class="mb-3 d-flex">
                <div class="flex-grow-1 me-2">
                    <label for="ano" class="form-label">Ano:</label>
                    <select name="ano" id="ano" class="form-select" required>
                        <option value="">Selecione o ano</option>
                        <option value="1">6º Ano</option>
                        <option value="2">7º Ano</option>
                        <option value="3">8º Ano</option>
                        <option value="4">9º Ano</option>
                        <option value="1">1º Ano</option>
                        <option value="2">2º Ano</option>
                        <option value="3">3º Ano</option>
                    </select>
                </div>
                <div class="flex-shrink-1">
                    <label for="letra" class="form-label">Sala:</label>
                    <input type="text" name="letra" id="letra" class="form-control" maxlength="1" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-gradient w-100">Cadastrar</button>
        </form>
    </div>

    <!-- Link para o JavaScript do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js
    /bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>