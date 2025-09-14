<?php
session_start(); // Inicia a sessão do PHP para gerenciar login

header('Content-Type: application/json'); // Define que a resposta será em JSON

// Verifica se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include '../conexao.php';

// Checa se houve erro na conexão
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro interno no sistema.']);
    exit();
}

// Recebe os dados enviados pelo formulário
$cpf_raw = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING); // Sanitiza o CPF
$senha = filter_input(INPUT_POST, 'senha', FILTER_UNSAFE_RAW); // Senha pode ter caracteres especiais

// Remove tudo que não for número do CPF
$cpf = preg_replace('/\D/', '', $cpf_raw);

// Valida o CPF (deve ter 11 dígitos)
if (strlen($cpf) !== 11 || !ctype_digit($cpf)) {
    echo json_encode(['success' => false, 'message' => 'CPF inválido.']);
    exit();
}

// Verifica se a senha tem pelo menos 6 caracteres
if (strlen($senha) < 6) {
    echo json_encode(['success' => false, 'message' => 'Senha muito curta.']);
    exit();
}

// Prepara a consulta SQL para buscar o professor pelo CPF
$sql = "SELECT id, nome, senha FROM professor WHERE cpf = ?";
$stmt = $conn->prepare($sql);

// Verifica se a preparação da query falhou
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Erro interno no sistema.']);
    exit();
}

// Liga o parâmetro CPF à consulta preparada
$stmt->bind_param("s", $cpf);
$stmt->execute(); // Executa a consulta
$result = $stmt->get_result(); // Obtém o resultado

// Se não encontrou usuário com o CPF informado
if ($result->num_rows === 0) {
    usleep(500000); // Delay para dificultar ataques de força bruta
    echo json_encode(['success' => false, 'message' => 'CPF ou senha incorretos.']);
    exit();
}

// Busca os dados do professor encontrado
$professor = $result->fetch_assoc();

// Verifica se a senha informada corresponde ao hash armazenado
if (!password_verify($senha, $professor['senha'])) {
    usleep(500000); // Delay para dificultar ataques de força bruta
    echo json_encode(['success' => false, 'message' => 'CPF ou senha incorretos.']);
    exit();
}

// Login bem-sucedido: regenera ID da sessão para segurança
session_regenerate_id(true);

// Armazena dados do professor na sessão
$_SESSION['professor_id'] = $professor['id'];
$_SESSION['professor_nome'] = $professor['nome'];

// Retorna sucesso em JSON
echo json_encode(['success' => true]);

// Fecha statement e conexão
$stmt->close();
$conn->close();
exit();
?>