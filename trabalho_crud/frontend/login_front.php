<?php
include '../backend/login.php';
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

        <form action="../backend/login.php" method="POST">
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF:</label>
                <input type="text" name="cpf" id="cpf" class="form-control shadow-sm" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" name="senha" id="senha" class="form-control shadow-sm" autocomplete="off" required>
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