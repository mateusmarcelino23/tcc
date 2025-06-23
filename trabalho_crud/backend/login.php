<?php
session_start(); // Inicia a sessão

// Verifica se professor já está logado, redireciona se estiver
if (isset($_SESSION['professor_id'])) {
    header("Location: ../../");
    exit();
}

// Inicializa variável de sessão para mensagem de login
$_SESSION['mensagem_login'] = '';

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inclui arquivo de conexão com o banco de dados
    include '../conexao.php';

    // Verifica erro na conexão
    if ($conn->connect_error) {
        // Define mensagem de erro genérica na sessão
        $_SESSION['mensagem_login'] = "Erro interno no sistema. Tente novamente mais tarde.";
        header("Location: ../frontend/login_front.php");
        exit();
    }

    // Recebe e sanitiza CPF enviado pelo formulário
    $cpf_raw = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
    // Recebe senha do formulário (sem filtragem para manter caracteres especiais)
    $senha = filter_input(INPUT_POST, 'senha', FILTER_UNSAFE_RAW);

    // Remove tudo que não for dígito do CPF
    $cpf = preg_replace('/\D/', '', $cpf_raw);

    // Valida se CPF tem exatamente 11 dígitos numéricos
    if (strlen($cpf) !== 11 || !ctype_digit($cpf)) {
        // Mensagem de CPF inválido na sessão e redireciona
        $_SESSION['mensagem_login'] = "CPF inválido.";
        header("Location: ../frontend/login_front.php");
        exit();
    }

    // Verifica se senha tem pelo menos 6 caracteres
    if (strlen($senha) < 6) {
        // Mensagem de senha curta na sessão e redireciona
        $_SESSION['mensagem_login'] = "Senha muito curta.";
        header("Location: ../frontend/login_front.php");
        exit();
    }

    // Prepara a consulta SQL para buscar professor pelo CPF
    $sql = "SELECT id, nome, senha FROM professor WHERE cpf = ?";
    $stmt = $conn->prepare($sql);

    // Verifica se preparação da query falhou
    if ($stmt === false) {
        // Mensagem genérica de erro na sessão e redireciona
        $_SESSION['mensagem_login'] = "Erro interno no sistema. Tente novamente mais tarde.";
        header("Location: ../frontend/login_front.php");
        exit();
    }

    // Liga o parâmetro CPF à consulta preparada
    $stmt->bind_param("s", $cpf);
    // Executa a consulta
    $stmt->execute();
    // Obtém o resultado da consulta
    $result = $stmt->get_result();

    // Verifica se não encontrou nenhum usuário com o CPF informado
    if ($result->num_rows === 0) {
        // Delay para dificultar ataques de força bruta
        usleep(500000);
        // Mensagem de usuário não encontrado na sessão e redireciona
        $_SESSION['mensagem_login'] = "Usuário não encontrado.";
        $stmt->close();
        $conn->close();
        header("Location: ../frontend/login_front.php");
        exit();
    }

    // Busca os dados do professor encontrado
    $professor = $result->fetch_assoc();

    // Verifica se a senha informada corresponde ao hash armazenado
    if (!password_verify($senha, $professor['senha'])) {
        // Delay para dificultar ataques de força bruta
        usleep(500000);
        // Mensagem de erro de autenticação na sessão e redireciona
        $_SESSION['mensagem_login'] = "CPF ou senha incorretos.";
        $stmt->close();
        $conn->close();
        header("Location: ../frontend/login_front.php");
        exit();
    }

    // Login bem-sucedido: regenera ID da sessão para segurança
    session_regenerate_id(true);
    // Armazena dados do professor na sessão
    $_SESSION['professor_id'] = $professor['id'];
    $_SESSION['professor_nome'] = $professor['nome'];
    // Limpa mensagem de erro
    $_SESSION['mensagem_login'] = '';

    // Fecha statement e conexão
    $stmt->close();
    $conn->close();

    // Redireciona para página principal após login
    header("Location: ../../");
    exit();
}
?>