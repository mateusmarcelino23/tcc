<?php
session_start(); // Inicia a sessão

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    // Redireciona para a página de login se não estiver logado
    header("Location: ../frontend/login_front.php");
    exit();
}

// Exibir mensagens de feedback armazenadas na sessão
if (isset($_SESSION['mensagem_livro'])) {
    echo $_SESSION['mensagem_livro'];
    unset($_SESSION['mensagem_livro']); // para mostrar só uma vez
}

// Função para buscar livros na API do Google Books
function buscarLivros($termo) {
    // Limpa espaços extras
    $termo = trim($termo);

    // Limpa todos os caracteres não numéricos ou X/x
    $termo_limpo = preg_replace('/[^0-9Xx]/', '', $termo);

    // Decide se busca por ISBN ou título
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
        echo 'Curl error: ' . curl_error($ch);
        return null;
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        echo "Erro ao buscar livros: HTTP $http_code";
        return null;
    }

    $resultado = json_decode($resposta, true);
    if (isset($resultado['totalItems']) && $resultado['totalItems'] == 0) {
        echo "Nenhum resultado encontrado para o termo fornecido.";
        return null;
    }

    return $resultado;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['termo_busca'])) {
        $termo = $_POST['termo_busca'];
        $resultados = buscarLivros($termo);

        if ($resultados !== null) {
            $_SESSION['resultados'] = $resultados;
            $_SESSION['modalAberto'] = true;
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();

    } elseif (isset($_POST['adicionar_livro_id'])) {

        if (isset($_SESSION['resultados']) && isset($_SESSION['resultados']['items'])) {

            $livro_id = $_POST['adicionar_livro_id'];

            if (isset($_SESSION['resultados']['items'][$livro_id])) {

                $livro = $_SESSION['resultados']['items'][$livro_id]['volumeInfo'];

                include '../conexao.php';

                if ($conn->connect_error) {
                    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
                }

                $titulo = isset($livro['title']) ? $conn->real_escape_string($livro['title']) : 'Título não disponível';

                if (isset($livro['authors'])) {
                    $autor = implode(', ', array_map([$conn, 'real_escape_string'], $livro['authors']));
                } else {
                    $autor = 'Autor desconhecido';
                }

                $isbn = '';
                if (isset($livro['industryIdentifiers']) && count($livro['industryIdentifiers']) > 0) {
                    $isbn = $conn->real_escape_string($livro['industryIdentifiers'][0]['identifier']);
                }

                if (!empty($isbn)) {
                    $sql_check = "SELECT * FROM livro WHERE isbn = '$isbn' LIMIT 1";
                } else {
                    $sql_check = "SELECT * FROM livro WHERE nome_livro = '$titulo' AND nome_autor = '$autor' LIMIT 1";
                }

                $result_check = $conn->query($sql_check);

                if ($result_check->num_rows > 0) {
                    $_SESSION['mensagem_livro'] = "<p style='color: orange;'>Este livro já está cadastrado.</p>";
                } else {
                    $sql_insert = "INSERT INTO livro (nome_livro, nome_autor, isbn) VALUES ('$titulo', '$autor', '$isbn')";

                    if ($conn->query($sql_insert) === TRUE) {
                        $_SESSION['mensagem_livro'] = "<p style='color: green;'>Livro adicionado com sucesso!</p>";
                    } else {
                        $_SESSION['mensagem_livro'] = "<p style='color: red;'>Erro ao adicionar o livro: " . $conn->error . "</p>";
                    }
                }

                $conn->close();

            } else {
                $_SESSION['mensagem_livro'] = "<p style='color: red;'>Livro não encontrado nos resultados.</p>";
            }

        } else {
            $_SESSION['mensagem_livro'] = "<p style='color: red;'>Resultados da busca não estão disponíveis.</p>";
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    elseif (isset($_POST['manual_titulo']) && isset($_POST['manual_autor'])) {

        include '../conexao.php';

        if ($conn->connect_error) {
            die("Falha na conexão com o banco de dados: " . $conn->connect_error);
        }

        $titulo = $conn->real_escape_string(trim($_POST['manual_titulo']));
        $autor = $conn->real_escape_string(trim($_POST['manual_autor']));
        $isbn = ''; // ISBN não é obrigatório no manual

        // Verifica se já existe esse livro
        $sql_check = "SELECT * FROM livro WHERE nome_livro = '$titulo' AND nome_autor = '$autor' LIMIT 1";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows > 0) {
            $_SESSION['mensagem_livro'] = "<p style='color: orange;'>Este livro já está cadastrado.</p>";
        } else {
            $sql_insert = "INSERT INTO livro (nome_livro, nome_autor, isbn) VALUES ('$titulo', '$autor', '$isbn')";

            if ($conn->query($sql_insert) === TRUE) {
                $_SESSION['mensagem_livro'] = "<p style='color: green;'>Livro adicionado com sucesso!</p>";
            } else {
                $_SESSION['mensagem_livro'] = "<p style='color: red;'>Erro ao adicionar o livro: " . $conn->error . "</p>";
            }
        }

        $conn->close();

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

}
?>
