<?php
session_start();

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../conexao.php';

    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $erro = "E-mail inválido.";
    } else {
        // Verifica se existe professor com esse e-mail
        $sql = "SELECT id, nome FROM professor WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $professor = $result->fetch_assoc();

            // Gera token e expiração (1 hora de validade)
            $token = bin2hex(random_bytes(16));
            $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Atualiza token e expiração no banco
            $sql_update = "UPDATE professor SET token_recuperacao = ?, token_expiracao = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ssi", $token, $expiracao, $professor['id']);
            $stmt_update->execute();

            // Enviar e-mail com link de recuperação
            $link = "http://seu_dominio.com/resetar_senha.php?token=$token";

            $assunto = "Recuperação de Senha";
            $mensagem = "Olá " . htmlspecialchars($professor['nome']) . ",\n\n";
            $mensagem .= "Recebemos um pedido para redefinir sua senha. Clique no link abaixo para criar uma nova senha:\n\n";
            $mensagem .= $link . "\n\n";
            $mensagem .= "Esse link é válido por 1 hora.\n\n";
            $mensagem .= "Se você não pediu essa alteração, ignore este e-mail.";

            $headers = "From: sistema@seu_dominio.com\r\nReply-To: sistema@seu_dominio.com";

            if (mail($email, $assunto, $mensagem, $headers)) {
                $sucesso = "Um e-mail com instruções foi enviado para $email.";
            } else {
                $erro = "Falha ao enviar o e-mail. Tente novamente mais tarde.";
            }
        } else {
            $erro = "E-mail não cadastrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="body">
<div class="container" style="max-width: 400px; margin-top: 50px;">
    <h2 class="text-center mb-4">Recuperar Senha</h2>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($sucesso); ?></div>
    <?php endif; ?>

    <form action="esqueci_senha.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Informe seu e-mail cadastrado</label>
            <input type="email" id="email" name="email" class="form-control" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary w-100">Enviar Link</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>