<?php
session_start();

$erro = ''; // Variável para armazenar a mensagem de erro

// Verifica se o professor já está logado
if (isset($_SESSION['professor_id'])) {
    header("Location: ../../index.php"); // Redireciona para o painel se já estiver logado
    exit();
}

// Processa o login quando o formulário é enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conecta ao banco de dados
    include '../conexao.php';

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Recebe os dados do formulário (CPF e senha)
    $cpf = preg_replace('/\D/', '', $_POST['cpf']); // Remove pontos e traços
    $senha = $_POST['senha']; // Pegando a senha

    // Valida CPF
    if (strlen($cpf) != 11) {
        $erro = "CPF inválido.";
    } else {
        // Consulta para buscar o professor pelo CPF
        $sql = "SELECT * FROM professor WHERE cpf = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $professor = $result->fetch_assoc();

            // Verifica a senha
            if (password_verify($senha, $professor['senha'])) {
                $_SESSION['professor_id'] = $professor['id'];
                $_SESSION['professor_nome'] = $professor['nome'];
                header("Location: ../../index.php");
                exit();
            }
        }

        // Se falhar, exibe erro
        $erro = "CPF ou senha incorretos.";

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../estilos/login.css">
</head>
<body class="body">
    <div class="container">
        <div class="text-center">
            <h1 class="h1">Login</h1>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF:</label>
                <input type="text" name="cpf" id="cpf" class="form-control shadow-sm" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" name="senha" id="senha" class="form-control shadow-sm" required>
            </div>
            <button type="submit" class="login-container button">Entrar</button>
        </form>

        <!-- <p class="mt-3 esqueceu-senha-texto">
            <span class="texto-esqueci-senha">Esqueceu a Senha?</span>
            <a href="esqueci_senha.php" class="link-esqueci-senha">Clique Aqui para Recuperar</a>
        </p> -->
        
    </div>

    <footer style="text-align: center; padding: 10px; color: white;">
        <p>&copy; 2025 Mateus Marcelino.</p>
    </footer>

    <script>
    document.getElementById('cpf').addEventListener('input', function (e) {
        let cpf = e.target.value.replace(/\D/g, ''); // Remove tudo que não for número
        cpf = cpf.replace(/^(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
        cpf = cpf.replace(/\.(\d{3})(\d)/, '.$1-$2');
        e.target.value = cpf.substring(0, 14); // Limita a 14 caracteres (XXX.XXX.XXX-XX)
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Link para a tratativa do JS -->
    <script src="../tratativa/script.js"></script>
</body>
</html>