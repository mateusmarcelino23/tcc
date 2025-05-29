<?php
session_start();
require '../conexao.php'; // conexão com banco

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; // carregando o composer e PHPMailer

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $erro = "E-mail inválido.";
    } elseif (strlen($cpf) != 11) {
        $erro = "CPF inválido.";
    } else {
        // Verifica se existe professor com CPF e email
        $sql = "SELECT * FROM professor WHERE cpf = ? AND email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $cpf, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $professor = $result->fetch_assoc();

            // Gera token seguro
            $token = bin2hex(random_bytes(32));
            $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Salva token no banco
            $sqlInsert = "INSERT INTO professor_reset_senha (professor_id, token, expiracao) VALUES (?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("iss", $professor['id'], $token, $expiracao);
            $stmtInsert->execute();

            // Monta link de reset
            $url = "http://localhost/mateus-1/professor/nova_senha.php?token=$token";

            // Envia e-mail
            $mail = new PHPMailer(true);

            try {
                // Configuração SMTP (exemplo Gmail, ajuste conforme seu servidor)
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'seu_email@gmail.com';
                $mail->Password = 'sua_senha_de_app'; // use senha de app se for Gmail
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('seu_email@gmail.com', 'Sua Aplicação');
                $mail->addAddress($professor['email'], $professor['nome']);

                $mail->isHTML(true);
                $mail->Subject = 'Recuperação de Senha';
                $mail->Body = "Olá, <br> Clique no link abaixo para redefinir sua senha: <br><a href='$url'>$url</a><br>O link expira em 1 hora.";

                $mail->send();

                $sucesso = "Um e-mail para redefinição de senha foi enviado para $email.";
            } catch (Exception $e) {
                $erro = "Erro ao enviar e-mail: {$mail->ErrorInfo}";
            }

        } else {
            $erro = "CPF ou e-mail não encontrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Esqueci a Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="body">
<div class="container mt-5">
    <h1 class="mb-4">Recuperar Senha</h1>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($sucesso); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="cpf" class="form-label">CPF</label>
            <input
                type="text"
                id="cpf"
                name="cpf"
                class="form-control"
                required
                maxlength="14"
                oninput="formatCPF(this)"
                placeholder="000.000.000-00"
            />
            <script>
                function formatCPF(input) {
                    let value = input.value.replace(/\D/g, ''); // remove tudo que não for número
                    if (value.length > 11) value = value.slice(0, 11);

                    // Aplica máscara do CPF
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

                    input.value = value;
                }
            </script>

        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" id="email" name="email" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary">Enviar link para redefinir</button>
    </form>
</div>
</body>
</html>