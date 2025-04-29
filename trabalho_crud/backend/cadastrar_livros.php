<?php
// Função para buscar livros na API do Google Books
function buscarLivros($termo) {
    $url = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($termo);
    
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
    
    return json_decode($resposta, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $termo = $_POST['termo_busca'];
    $resultados = buscarLivros($termo);
    
    // Verifica se há resultados e se deve abrir o modal
    $modalAberto = isset($resultados['items']) && !empty($resultados['items']);
    
    $conn = new mysqli('localhost', 'root', '', 'crud_db');
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }
    
    if (isset($_POST['adicionar_livro_id'])) {
        $livro_id = $_POST['adicionar_livro_id'];
        $livro = $resultados['items'][$livro_id]['volumeInfo'];
        
        $titulo = $livro['title'];
        $autor = isset($livro['authors']) ? implode(', ', $livro['authors']) : 'Autor desconhecido';
        $isbn = isset($livro['industryIdentifiers'][0]['identifier']) ? $livro['industryIdentifiers'][0]['identifier'] : 'ISBN não disponível';
        
        $sql = "INSERT INTO livro (nome_livro, nome_autor, isbn) VALUES ('$titulo', '$autor', '$isbn')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>Livro adicionado com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao adicionar o livro: " . $conn->error . "</p>";
        }
    }
    
    $conn->close();
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
    <link rel="stylesheet" type="text/css" href="../frontend/registrar.css">
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
            <li><a href="info_prof.php">Informações do professor</a></li>
            <li><a href="configuracoes.php">Configurações</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li><a href="ver_professores.php">Professores</a></li>
        </ul>
    </div>

        <!-- Voltar -->
        <div class="mt-3 text-start">
        <a href="ver_livros.php" class="link-back">< Voltar</a>
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
    <div class="modal fade" id="resultadosModal" tabindex="-1" aria-labelledby="resultadosModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultadosModalLabel">Resultados da Busca</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul>
                        <?php if (isset($resultados) && !empty($resultados['items'])): ?>
                            <?php foreach ($resultados['items'] as $index => $item): ?>
                                <li>
                                    <strong><?php echo $item['volumeInfo']['title']; ?></strong><br>
                                    Autor: <?php echo isset($item['volumeInfo']['authors']) ? implode(', ', $item['volumeInfo']['authors']) : 'Autor desconhecido'; ?><br>
                                    ISBN: <?php echo isset($item['volumeInfo']['industryIdentifiers'][0]['identifier']) ? $item['volumeInfo']['industryIdentifiers'][0]['identifier'] : 'ISBN não disponível'; ?><br>
                                    <img src="<?php echo isset($item['volumeInfo']['imageLinks']['thumbnail']) ? $item['volumeInfo']['imageLinks']['thumbnail'] : 'Imagem não disponível'; ?>" alt="Capa do Livro" style="max-width: 100px; max-height: 150px;"><br>
                                    <form method="POST" action="">
                                        <input type="hidden" name="adicionar_livro_id" value="<?php echo $index; ?>">
                                        <button type="submit" class="btn">Adicionar Livro</button>
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
        <?php if (isset($modalAberto) && $modalAberto): ?>
            var myModal = new bootstrap.Modal(document.getElementById('resultadosModal'));
            myModal.show();
        <?php endif; ?>
    </script>

</body>
</html>