<?php
session_start(); // Inicia a sessão

// Função para buscar livros na API do Google Books
function buscarLivros($termo) {
    // Verifica se o termo parece um ISBN (apenas dígitos e talvez alguns conectores como '-' ou 'X' no final)
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
        return null; // Retorna null em caso de erro HTTP
    }

    $resultado = json_decode($resposta, true);
    if (isset($resultado['totalItems']) && $resultado['totalItems'] == 0) {
        echo "Nenhum resultado encontrado para o termo fornecido.";
        return null; // Retorna null se não houver itens encontrados
    }

    return $resultado;
}

// Verifica se o formulário de busca foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['termo_busca'])) {
        // Realiza a busca e armazena na sessão
        $termo = isset($_POST['termo_busca']) ? $_POST['termo_busca'] : '';
        $resultados = buscarLivros($termo);

        if ($resultados !== null) {
            $_SESSION['resultados'] = $resultados;
            $_SESSION['modalAberto'] = true; // Sinaliza que o modal deve ser aberto
        }
    } elseif (isset($_POST['adicionar_livro_id'])) {
        // Verifica se a sessão de resultados ainda está válida
        if (isset($_SESSION['resultados']) && isset($_SESSION['resultados']['items'])) {
            $livro_id = $_POST['adicionar_livro_id'];

            if (isset($_SESSION['resultados']['items'][$livro_id])) {
                $livro = $_SESSION['resultados']['items'][$livro_id]['volumeInfo'];
                
                // Conexão com o banco de dados
                include '../conexao.php';
                if ($conn->connect_error) {
                    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
                }

                $titulo = isset($livro['title']) ? $conn->real_escape_string($livro['title']) : 'Título não disponível';
                $autor = isset($livro['authors']) ? implode(', ', array_map(array($conn, 'real_escape_string'), $livro['authors'])) : 'Autor desconhecido';
                $isbn = isset($livro['industryIdentifiers'][0]['identifier']) ? $conn->real_escape_string($livro['industryIdentifiers'][0]['identifier']) : 'ISBN não disponível';

                $sql = "INSERT INTO livro (nome_livro, nome_autor, isbn) VALUES ('$titulo', '$autor', '$isbn')";

                if ($conn->query($sql) === TRUE) {
                    echo "<p style='color: green;'>Livro adicionado com sucesso!</p>";
                } else {
                    echo "<p style='color: red;'>Erro ao adicionar o livro: " . $conn->error . "</p>";
                }

                $conn->close();
            } else {
                echo "<p style='color: red;'>Livro não encontrado nos resultados.</p>";
            }
        } else {
            echo "<p style='color: red;'>Resultados da busca não estão disponíveis.</p>";
        }
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
                                    <strong><?php echo $item['volumeInfo']['title']; ?></strong><br>
                                    Autor: <?php echo isset($item['volumeInfo']['authors']) ? implode(', ', $item['volumeInfo']['authors']) : 'Autor desconhecido'; ?><br>
                                    ISBN: <?php echo isset($item['volumeInfo']['industryIdentifiers'][0]['identifier']) ? $item['volumeInfo']['industryIdentifiers'][0]['identifier'] : 'ISBN não disponível'; ?><br>
                                    <img src="<?php echo isset($item['volumeInfo']['imageLinks']['thumbnail']) ? $item['volumeInfo']['imageLinks']['thumbnail'] : 'Imagem não disponível'; ?>" alt="Capa do Livro"><br>
                                    <form method="POST" action="">
                                        <input type="hidden" name="adicionar_livro_id" value="<?php echo $index; ?>">
                                        <button type="submit" class="modal-btn">Adicionar Livro</button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>Nenhum resultado encontrado.</li>
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