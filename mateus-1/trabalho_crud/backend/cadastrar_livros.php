<?php
// Função para buscar livros na API do Google Books
function buscarLivros($termo) {
    $url = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($termo);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $resposta = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($resposta, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $termo = $_POST['termo_busca'];
    $resultados = buscarLivros($termo);
    
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
    <title>Cadastrar Livros - Sistema da Biblioteca</title>

    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Link para a fonte do Google Fonts (exemplo: Roboto) -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <!-- Vinculando o CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="../frontend/registrar.css">

</head>
<body>

    <!-- Cabeçalho -->
    <header>
        <h1>Biblioteca M.V.C</h1>
    </header>

    <div class="container">
        <h2 class="text-center">Cadastrar Livros</h2>

        <!-- Formulário de busca -->
        <form action="cadastrar_livros.php" method="POST">
            <div class="mb-3">
                <label for="termo_busca" class="form-label">Digite o título, autor ou ISBN:</label>
                <input type="text" name="termo_busca" id="termo_busca" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-gradient w-100">Buscar</button>
        </form>

        <br>

        <a href="painel.php" class="btn btn-primary mt-3 w-100">Voltar para o Painel</a>

        <!-- Exibição dos resultados -->
        <?php if (isset($resultados)): ?>
            <h2>Resultados:</h2>
            <?php if (isset($resultados['items']) && count($resultados['items']) > 0): ?>
                <ul>
                    <?php foreach ($resultados['items'] as $index => $livro): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($livro['volumeInfo']['title']); ?></strong><br>
                            <em><?php echo isset($livro['volumeInfo']['authors']) ? implode(', ', $livro['volumeInfo']['authors']) : 'Autor desconhecido'; ?></em><br>
                            <strong>ISBN:</strong> <?php echo isset($livro['volumeInfo']['industryIdentifiers'][0]['identifier']) ? $livro['volumeInfo']['industryIdentifiers'][0]['identifier'] : 'ISBN não disponível'; ?><br>
                            <a href="<?php echo $livro['volumeInfo']['infoLink']; ?>" target="_blank">Mais informações</a><br>
                            <form action="cadastrar_livros.php" method="POST">
                                <input type="hidden" name="termo_busca" value="<?php echo htmlspecialchars($termo); ?>">
                                <input type="hidden" name="adicionar_livro_id" value="<?php echo $index; ?>">
                                <button type="submit" class="btn btn-gradient">Adicionar Livro</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Nenhum resultado encontrado.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Script do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>