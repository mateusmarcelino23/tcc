<?php
// Configura o MySQLi para lançar exceções em caso de erro, facilitando o tratamento
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Obtém as variáveis de ambiente para conexão com o banco de dados
$host = 'localhost';       // Endereço do servidor de banco de dados
$usuario = 'host'; // Nome de usuário do banco
$senha = '';     // Senha do banco
$banco = 'crud_db';     // Nome do banco de dados

try {
    // Tenta estabelecer a conexão com o banco de dados
    $conn = new mysqli($host, $usuario, $senha, $banco);

    // Define o conjunto de caracteres da conexão para UTF-8 com suporte a emojis e acentuação
    $conn->set_charset('utf8mb4');

    // Conexão estabelecida com sucesso (não exibe mensagem para segurança e boas práticas)
} catch (Exception $e) {
    // Em caso de erro, registra a mensagem detalhada no log do servidor
    error_log($e->getMessage());

    // Exibe uma mensagem genérica ao usuário, sem revelar detalhes sensíveis
    die('Erro ao conectar ao banco de dados. Tente novamente mais tarde.');
}

?>