<?php
session_start(); // Inicia a sessão

// Exibir mensagens de feedback armazenadas na sessão
if (isset($_SESSION['mensagem'])) {
    echo $_SESSION['mensagem'];
    unset($_SESSION['mensagem']); // para mostrar só uma vez
}

// Função para buscar livros na API do Google Books
function buscarLivros($termo) {
    if (preg_match('/^\d{9}[\dX]{1}$/', $termo) || preg_match('/^\d{12}[\dX]{1}$/', $termo)) {
        $url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" . urlencode($termo);
    } else {
        $url = "https://www.googleapis.com/books/v1/volumes?q=intitle:" . urlencode($termo);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $resposta = curl_exec($ch);
    if ($resposta === false) {
        echo 'Curl error: ' . curl_error($ch);
        return null; // Retorna null em caso de erro
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

    // Se veio termo para buscar
    if (isset($_POST['termo_busca'])) {
        $termo = isset($_POST['termo_busca']) ? $_POST['termo_busca'] : '';
        $resultados = buscarLivros($termo);

        if ($resultados !== null) {
            $_SESSION['resultados'] = $resultados;
            $_SESSION['modalAberto'] = true;
        }

        // Redireciona para evitar reenvio do formulário
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();

    // Se veio pedido para adicionar livro
    } elseif (isset($_POST['adicionar_livro_id'])) {

        if (isset($_SESSION['resultados']) && isset($_SESSION['resultados']['items'])) {

            $livro_id = $_POST['adicionar_livro_id'];

            if (isset($_SESSION['resultados']['items'][$livro_id])) {

                $livro = $_SESSION['resultados']['items'][$livro_id]['volumeInfo'];

                include '../conexao.php';

                if ($conn->connect_error) {
                    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
                }

                // PEGAR DADOS DO LIVRO (E ESCAPAR PARA SQL)
                $titulo = isset($livro['title']) ? $conn->real_escape_string($livro['title']) : 'Título não disponível';

                if (isset($livro['authors'])) {
                    $autor = implode(', ', array_map(array($conn, 'real_escape_string'), $livro['authors']));
                } else {
                    $autor = 'Autor desconhecido';
                }

                // PEGAR ISBN — se não existir, fica vazio para impedir cadastro inválido
                $isbn = '';
                if (isset($livro['industryIdentifiers']) && count($livro['industryIdentifiers']) > 0) {
                    $isbn = $conn->real_escape_string($livro['industryIdentifiers'][0]['identifier']);
                }

                // ==== AQUI VEM A VERIFICAÇÃO E INSERÇÃO NO BANCO ==== 

                if (empty($isbn)) {
                    // Se não tem ISBN válido, não adiciona
                    $_SESSION['mensagem'] = "<p style='color: red;'>ISBN não disponível. Não é possível adicionar este livro.</p>";
                } else {
                    // Verifica se já existe pelo ISBN
                    $sql_check = "SELECT * FROM livro WHERE isbn = '$isbn' LIMIT 1";
                    $result_check = $conn->query($sql_check);

                    if ($result_check->num_rows > 0) {
                        // Já existe
                        $_SESSION['mensagem'] = "<p style='color: orange;'>Este livro já está cadastrado.</p>";
                    } else {
                        // Insere livro
                        $sql_insert = "INSERT INTO livro (nome_livro, nome_autor, isbn) VALUES ('$titulo', '$autor', '$isbn')";

                        if ($conn->query($sql_insert) === TRUE) {
                            $_SESSION['mensagem'] = "<p style='color: green;'>Livro adicionado com sucesso!</p>";
                        } else {
                            $_SESSION['mensagem'] = "<p style='color: red;'>Erro ao adicionar o livro: " . $conn->error . "</p>";
                        }
                    }
                }

                $conn->close();

            } else {
                $_SESSION['mensagem'] = "<p style='color: red;'>Livro não encontrado nos resultados.</p>";
            }

        } else {
            $_SESSION['mensagem'] = "<p style='color: red;'>Resultados da busca não estão disponíveis.</p>";
        }

        // Redireciona para evitar reenvio
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Livros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../estilos/registrar.css">
</head>

<body>
    <!-- Cabeçalho -->
    <nav class="header">Biblioteca M.V.C
        <!-- Botão para abrir/fechar o menu lateral -->
        <span id="toggleSidebar" class="openbtn" onclick="toggleNav()">&#9776;</span>

        <script>
            function toggleNav() {
                const sidebar = document.getElementById("mySidebar");
                const toggleBtn = document.getElementById("toggleSidebar");

                if (sidebar.classList.contains("open")) {
                    sidebar.classList.remove("open");
                    toggleBtn.innerHTML = "&#9776;"; // ícone de abrir
                } else {
                    sidebar.classList.add("open");
                    toggleBtn.innerHTML = "&times;"; // ícone de fechar
                }
            }
        </script>
    </nav>

    <!-- Menu lateral -->
    <div class="sidebar" id="mySidebar">
        <ul>
            <li><a href="relatorios.php">Relatórios</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Voltar -->
    <div class="mt-3 text-start">
        <a href="../../index.php" class="link-back">< Voltar para o painel</a>
    </div>

    <!-- Container para o formulário -->
    <div class="container">
        <h2>Buscar Livros</h2>
        <form method="POST" action="">
            <label for="termo_busca">Digite o Nome ou ISBN do Livro:</label>
            <input type="text" id="termo_busca" name="termo_busca" required>
            <button type="submit" class="btn">Buscar</button>
        </form>
    </div>

<!-- Modal -->
<div class="modal" id="resultadosModal" tabindex="-1" aria-labelledby="resultadosModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultadosModalLabel">Resultados da Busca</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul>
                    <?php if (isset($_SESSION['resultados']) && !empty($_SESSION['resultados']['items'])): ?>
                        <?php foreach ($_SESSION['resultados']['items'] as $index => $item): ?>
                            <li>
                                <img src="<?php echo isset($item['volumeInfo']['imageLinks']['thumbnail']) ? $item['volumeInfo']['imageLinks']['thumbnail'] : 'Imagem não disponível'; ?>" alt="Capa do Livro">
                                <div>
                                    <strong><?php echo $item['volumeInfo']['title']; ?></strong>
                                    <p>Autor: <?php echo isset($item['volumeInfo']['authors']) ? implode(', ', $item['volumeInfo']['authors']) : 'Autor desconhecido'; ?></p>
                                    <p>ISBN: <?php echo isset($item['volumeInfo']['industryIdentifiers'][0]['identifier']) ? $item['volumeInfo']['industryIdentifiers'][0]['identifier'] : 'ISBN não disponível'; ?></p>
                                    <form method="POST" action="">
                                        <input type="hidden" name="adicionar_livro_id" value="<?php echo $index; ?>">
                                        <button type="submit" class="modal-btn">Adicionar Livro</button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>
                            <p>Nenhum resultado encontrado.</p>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>


    <script>
        // Verifica se o modal deve ser aberto
        <?php if (isset($_SESSION['modalAberto']) && $_SESSION['modalAberto']): ?>
            var myModal = new bootstrap.Modal(document.getElementById('resultadosModal'));
            myModal.show();
            // Limpa a flag da sessão após abrir o modal
            <?php unset($_SESSION['modalAberto']); ?>
        <?php endif; ?>
    </script>

    <!-- Link para a tratativa do JS -->
    <script src="../tratativa/script.js"></script>

</body>
</html>