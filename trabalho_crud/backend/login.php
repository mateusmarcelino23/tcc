<?php
session_start();

$erro = ''; // Variável para armazenar a mensagem de erro

// Verifica se o professor já está logado
if (isset($_SESSION['professor_id'])) {
    header("Location: ../../"); // Redireciona para o painel se já estiver logado
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
                header("Location: ../../");
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