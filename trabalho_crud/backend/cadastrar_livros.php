<?php
session_start();
header('Content-Type: application/json');

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuário não está logado.']);
    exit();
}

// Função para buscar livros na API do Google Books
function buscarLivros($termo)
{
    $termo = trim($termo);
    $termo_limpo = preg_replace('/[^0-9Xx]/', '', $termo);

    if (preg_match('/^\d{9}[\dXx]{1}$/', $termo_limpo) || preg_match('/^\d{13}$/', $termo_limpo)) {
        $consulta = "isbn:" . strtoupper($termo_limpo);
    } else {
        $consulta = "intitle:" . urlencode($termo);
    }

    $url = "https://www.googleapis.com/books/v1/volumes?q=" . $consulta;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $resposta = curl_exec($ch);
    if ($resposta === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Curl error: ' . curl_error($ch)]);
        return null;
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        http_response_code($http_code);
        echo json_encode(['error' => "Erro ao buscar livros: HTTP $http_code"]);
        return null;
    }

    $resultado = json_decode($resposta, true);
    if (isset($resultado['totalItems']) && $resultado['totalItems'] == 0) {
        echo json_encode(['message' => 'Nenhum resultado encontrado.']);
        return null;
    }

    return $resultado;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    include '../conexao.php';
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'Falha na conexão com o banco de dados.']);
        exit();
    }

    // Buscar livros
    if (isset($_POST['termo_busca'])) {
        $termo = $_POST['termo_busca'];
        $resultados = buscarLivros($termo);
        if ($resultados !== null) {
            echo json_encode(['success' => true, 'resultados' => $resultados]);
        }
        exit();
    }

    // Adicionar livro via resultado da busca
    if (isset($_POST['adicionar_livro_id'])) {
        $livro_id = $_POST['adicionar_livro_id'];

        if (!isset($_POST['resultados'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Resultados da busca não fornecidos.']);
            exit();
        }

        $resultados = json_decode($_POST['resultados'], true);
        if (!isset($resultados['items'][$livro_id])) {
            http_response_code(404);
            echo json_encode(['error' => 'Livro não encontrado nos resultados.']);
            exit();
        }

        $livro = $resultados['items'][$livro_id]['volumeInfo'];
        $titulo = isset($livro['title']) ? $conn->real_escape_string($livro['title']) : 'Título não disponível';
        $autor = isset($livro['authors']) ? implode(', ', array_map([$conn, 'real_escape_string'], $livro['authors'])) : 'Autor desconhecido';
        $isbn = isset($livro['industryIdentifiers'][0]['identifier']) ? $conn->real_escape_string($livro['industryIdentifiers'][0]['identifier']) : '';

        $sql_check = !empty($isbn)
            ? "SELECT * FROM livro WHERE isbn = '$isbn' LIMIT 1"
            : "SELECT * FROM livro WHERE nome_livro = '$titulo' AND nome_autor = '$autor' LIMIT 1";

        $result_check = $conn->query($sql_check);
        if ($result_check->num_rows > 0) {
            echo json_encode(['message' => 'Este livro já está cadastrado.', 'status' => 'warning']);
        } else {
            $sql_insert = "INSERT INTO livro (nome_livro, nome_autor, isbn) VALUES ('$titulo', '$autor', '$isbn')";
            if ($conn->query($sql_insert) === TRUE) {
                echo json_encode(['message' => 'Livro adicionado com sucesso!', 'status' => 'success']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Erro ao adicionar o livro: ' . $conn->error]);
            }
        }
        $conn->close();
        exit();
    }

    // Adicionar livro manualmente
    if (isset($_POST['manual_titulo']) && isset($_POST['manual_autor'])) {
        $titulo = $conn->real_escape_string(trim($_POST['manual_titulo']));
        $autor = $conn->real_escape_string(trim($_POST['manual_autor']));
        $isbn = '';

        $sql_check = "SELECT * FROM livro WHERE nome_livro = '$titulo' AND nome_autor = '$autor' LIMIT 1";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows > 0) {
            echo json_encode(['message' => 'Este livro já está cadastrado.', 'status' => 'warning']);
        } else {
            $sql_insert = "INSERT INTO livro (nome_livro, nome_autor, isbn) VALUES ('$titulo', '$autor', '$isbn')";
            if ($conn->query($sql_insert) === TRUE) {
                echo json_encode(['message' => 'Livro adicionado com sucesso!', 'status' => 'success']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Erro ao adicionar o livro: ' . $conn->error]);
            }
        }

        $conn->close();
        exit();
    }
}

http_response_code(400);
echo json_encode(['error' => 'Requisição inválida.']);
exit();
?>