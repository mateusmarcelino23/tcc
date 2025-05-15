<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    // Caso o professor não esteja logado, redireciona para a página de login
    header("Location: login.php");
    exit();
}

// Conectar com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'crud_db');

// Verifica a conexão com o banco de dados
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Se o ID do empréstimo estiver na URL, buscamos as informações do empréstimo
if (isset($_GET['id'])) {
    $id_emprestimo = $_GET['id'];

    // Consulta para pegar os dados do empréstimo a ser editado
    $sql = "SELECT e.id, e.id_aluno, e.id_livro, e.data_emprestimo, e.data_devolucao, a.nome AS aluno_nome, l.nome_livro
            FROM emprestimo e
            JOIN aluno a ON e.id_aluno = a.id
            JOIN livro l ON e.id_livro = l.id
            WHERE e.id = $id_emprestimo";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $emprestimo = $result->fetch_assoc();
    } else {
        echo "Empréstimo não encontrado.";
        exit();
    }
} else {
    echo "ID do empréstimo não fornecido.";
    exit();
}

// Se o formulário de edição for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_aluno = $_POST['id_aluno'];
    $id_livro = $_POST['id_livro'];
    $data_emprestimo = $_POST['data_emprestimo'];
    $data_devolucao = $_POST['data_devolucao'];

    // Atualiza o empréstimo no banco de dados
    $sql_update = "UPDATE emprestimo
                   SET id_aluno = '$id_aluno', id_livro = '$id_livro', data_emprestimo = '$data_emprestimo', data_devolucao = '$data_devolucao'
                   WHERE id = $id_emprestimo";

    if ($conn->query($sql_update) === TRUE) {
        echo "<p style='color: green;'>Empréstimo atualizado com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao atualizar: " . $conn->error . "</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Empréstimo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" type="text/css" href="../frontend/registrar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
    <!-- Cabeçalho -->
    <nav class="header">Biblioteca M.V.C
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

    <div class="mt-3 text-start">
        <a href="ver_emprestimos.php" class="link-back">&lt; Voltar</a>
    </div>

    <div class="container">
        <h2 class="text-center">Editar Empréstimo</h2>
        <form action="editar_emprestimo.php?id=<?php echo $id_emprestimo; ?>" method="POST">
            <!-- Campo Aluno -->
            <div class="mb-3">
                <label for="id_aluno" class="form-label">Aluno:</label>
                <select name="id_aluno" id="id_aluno" class="form-select" required style="width: 100%;">
                    <option value="<?php echo $emprestimo['id_aluno']; ?>" selected><?php echo $emprestimo['aluno_nome']; ?></option>
                </select>
            </div>

            <!-- Campo Livro -->
            <div class="mb-3">
                <label for="id_livro" class="form-label">Livro:</label>
                <select name="id_livro" id="id_livro" class="form-select" required style="width: 100%;">
                    <option value="<?php echo $emprestimo['id_livro']; ?>" selected><?php echo $emprestimo['nome_livro']; ?></option>
                </select>
            </div>

            <!-- Datas  COM FLATPICKR -->
            <div class="mb-3">
                <label for="data_emprestimo" class="form-label">Data de Empréstimo:</label>
                <input type="text" name="data_emprestimo" id="data_emprestimo" class="form-control" value="<?php echo $emprestimo['data_emprestimo']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="data_devolucao" class="form-label">Data de Devolução:</label>
                <input type="text" name="data_devolucao" id="data_devolucao" class="form-control" value="<?php echo $emprestimo['data_devolucao']; ?>" required>
            </div>

            <!-- Botão -->
            <button type="submit" class="btn btn-gradient w-100">Atualizar Empréstimo</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function () {
            function initSelect2(selector, url, placeholderText) {
                $(selector).select2({
                    placeholder: placeholderText,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return { term: params.term };
                        },
                        processResults: function (data) {
                            return { results: data };
                        },
                        cache: true
                    },
                    minimumInputLength: 1,
                    language: {
                        noResults: function () {
                            return "Nenhum resultado encontrado";
                        }
                    }
                });
            }

            initSelect2('#id_aluno', 'buscar_alunos.php', 'Digite o nome do aluno');
            initSelect2('#id_livro', 'buscar_livros.php', 'Digite o nome do livro');
        });

        // Inicializar o flatpickr nos campos de data com formato brasileiro (DD/MM/YYYY) e idioma PT-BR
        flatpickr("#data_emprestimo", {
            dateFormat: "d/m/Y",
            locale: "pt-BR"
        });

        flatpickr("#data_devolucao", {
            dateFormat: "d/m/Y",
            locale: "pt-BR"
        });
    </script>
</body>
</html>