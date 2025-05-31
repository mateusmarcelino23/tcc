<?php
session_start();

$erro = ''; // Variável para armazenar a mensagem de erro

// Verifica se o professor já está logado
if (isset($_SESSION['professor_id'])) {
    header("Location: ../../");
    exit();
}

// Processa o login quando o formulário é enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conecta ao banco de dados
    include '../conexao.php';

    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . htmlspecialchars($conn->connect_error));
    }

    // Recebe os dados do formulário
    $cpf = preg_replace('/\D/', '', $_POST['cpf']); // Sanitiza CPF
    $senha = $_POST['senha']; // Pegando a senha

    // Validação adicional
    if (strlen($cpf) != 11 || !ctype_digit($cpf)) {
        $erro = "CPF inválido.";
    } elseif (strlen($senha) < 6) { // Recomendado mínimo de segurança
        $erro = "Senha muito curta.";
    } else {
        // Consulta para buscar o professor pelo CPF
        $sql = "SELECT id, nome, senha FROM professor WHERE cpf = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            die("Erro na preparação da consulta: " . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $professor = $result->fetch_assoc();

            // Verifica a senha usando password_verify
            if (password_verify($senha, $professor['senha'])) {
                // Regenera o ID da sessão para evitar fixation
                session_regenerate_id(true);

                $_SESSION['professor_id'] = $professor['id'];
                $_SESSION['professor_nome'] = $professor['nome'];

                header("Location: ../../");
                exit();
            }
        }

        // Delay anti-brute-force
        usleep(500000); // meio segundo

        $erro = "CPF ou senha incorretos.";

        $stmt->close();
    }

    $conn->close();
}
?>