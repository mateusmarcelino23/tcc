<?php
// Habilitar relatórios de erro do MySQLi para facilitar a depuração
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'crud_db';

try {
    // Criando a conexão
    $conn = new mysqli($host, $usuario, $senha, $banco);
    $conn->set_charset('utf8mb4'); // Define o charset para evitar problemas com acentuação
} catch (Exception $e) {
    // Se houver erro, exibe a mensagem sem expor informações sensíveis
    die('Erro ao conectar ao banco de dados. Tente novamente mais tarde.');
}
?>