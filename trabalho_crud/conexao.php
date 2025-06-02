<?php

# Requer o arquivo load_env.php para carregar as variáveis de ambiente
require_once 'load_env.php';

#local do arquivo .env
loadEnv(__DIR__ . '/.env');

// Habilitar relatórios de erro do MySQLi para facilitar a depuração
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = $_ENV['HOST'];
$usuario = $_ENV['USUARIO'];
$senha = '';
$banco = $_ENV['BANCO'];

try {
    // Criando a conexão
    $conn = new mysqli($host, $usuario, $senha, $banco);
    $conn->set_charset('utf8mb4'); // Define o charset para evitar problemas com acentuação
} catch (Exception $e) {
    // Se houver erro, exibe a mensagem sem expor informações sensíveis
    die('Erro ao conectar ao banco de dados. Tente novamente mais tarde.');
}
?>