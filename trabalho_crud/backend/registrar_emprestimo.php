<?php
session_start();
if (!isset($_SESSION['professor_id'])) {
    header("Location: login.php");
    exit();
}

include '../conexao.php';
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_aluno = $_POST['id_aluno'];
    $id_professor = $_SESSION['professor_id'];
    $id_livro = $_POST['id_livro'];
    $data_emprestimo = DateTime::createFromFormat('d/m/Y', $_POST['data_emprestimo'])->format('Y-m-d');
    $data_devolucao = DateTime::createFromFormat('d/m/Y', $_POST['data_devolucao'])->format('Y-m-d');

    if ($data_devolucao < $data_emprestimo) {
        echo "<p style='color: red;'>A data de devolução não pode ser anterior à data de empréstimo.</p>";
        exit();
    }

    $sql = "INSERT INTO emprestimo (id_aluno, id_professor, id_livro, data_emprestimo, data_devolucao)
            VALUES ('$id_aluno', '$id_professor', '$id_livro', '$data_emprestimo', '$data_devolucao')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Empréstimo registrado com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao registrar o empréstimo: " . $conn->error . "</p>";
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registrar Empréstimo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" type="text/css" href="../frontend/registrar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
    <!-- Cabeçalho -->
    <nav class="header">
        Biblioteca M.V.C
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
        <h2 class="text-center">Registrar Empréstimo</h2>
        <form action="registrar_emprestimo.php" method="POST">
            <!-- Campo Aluno -->
            <div class="mb-3">
                <label for="id_aluno" class="form-label">Aluno:</label>
                <select name="id_aluno" id="id_aluno" class="form-select" required style="width: 100%;"></select>
            </div>

            <!-- Campo Livro -->
            <div class="mb-3">
                <label for="id_livro" class="form-label">Livro:</label>
                <select name="id_livro" id="id_livro" class="form-select" required style="width: 100%;"></select>
            </div>

            <!-- Datas -->
            <div class="mb-3">
                <label for="data_emprestimo" class="form-label">Data de Empréstimo:</label>
                <input type="text" name="data_emprestimo" id="data_emprestimo" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="data_devolucao" class="form-label">Data de Devolução:</label>
                <input type="text" name="data_devolucao" id="data_devolucao" class="form-control" required>
            </div>

            <!-- Botão -->
            <button type="submit" class="btn btn-gradient w-100">Registrar Empréstimo</button>
        </form>
    </div>

    <!-- Scripts -->
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

            // Inicializar o Select2 nos campos
            initSelect2('#id_aluno', 'buscar_alunos.php', 'Digite o nome do aluno');
            initSelect2('#id_livro', 'buscar_livros.php', 'Digite o nome do livro');

            // Inicializar o flatpickr nos campos de data com formato brasileiro (DD/MM/YYYY) e idioma PT-BR
            flatpickr("#data_emprestimo", {
                allowInput: false, // Impede a digitação manual
                locale: 'pt',      // Idioma PT-BR
                dateFormat: 'd/m/Y' // Formato de data brasileiro
            });

            flatpickr("#data_devolucao", {
                allowInput: false, // Impede a digitação manual
                locale: 'pt',      // Idioma PT-BR
                dateFormat: 'd/m/Y' // Formato de data brasileiro
            });

            // Verificação antes de enviar o formulário
            $('form').on('submit', function (e) {
                const dataEmprestimoStr = $('#data_emprestimo').val();
                const dataDevolucaoStr = $('#data_devolucao').val();

                const [diaEmp, mesEmp, anoEmp] = dataEmprestimoStr.split('/');
                const [diaDev, mesDev, anoDev] = dataDevolucaoStr.split('/');

                const dataEmprestimo = new Date(`${anoEmp}-${mesEmp}-${diaEmp}`);
                const dataDevolucao = new Date(`${anoDev}-${mesDev}-${diaDev}`);

                if (dataDevolucao < dataEmprestimo) {
                    e.preventDefault(); // Impede o envio do formulário
                    alert("A data de devolução não pode ser anterior à data de empréstimo.");
                    return false;
                }
            });
        });
    </script>
</body>
</html>