<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco
include '../conexao.php';
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Função para converter data de d/m/Y para Y-m-d
function converterDataParaBD($data) {
    $partes = explode('/', $data);
    return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
}

// Função para converter data de Y-m-d para d/m/Y
function converterDataParaBR($data) {
    $partes = explode('-', $data);
    return $partes[2] . '/' . $partes[1] . '/' . $partes[0];
}

if (!isset($_GET['id'])) {
    echo "ID do empréstimo não fornecido.";
    exit();
}

$id_emprestimo = intval($_GET['id']);

// Buscar dados do empréstimo
$stmt = $conn->prepare("SELECT e.id, e.id_aluno, e.id_livro, e.data_emprestimo, e.data_devolucao, a.nome AS aluno_nome, l.nome_livro
                        FROM emprestimo e
                        JOIN aluno a ON e.id_aluno = a.id
                        JOIN livro l ON e.id_livro = l.id
                        WHERE e.id = ?");
$stmt->bind_param("i", $id_emprestimo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Empréstimo não encontrado.";
    exit();
}
$emprestimo = $result->fetch_assoc();

// Se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_aluno = intval($_POST['id_aluno']);
    $id_livro = intval($_POST['id_livro']);
    $data_emprestimo = converterDataParaBD($_POST['data_emprestimo']);
    $data_devolucao = converterDataParaBD($_POST['data_devolucao']);

    // Atualiza o empréstimo no banco
    if ($data_devolucao < $data_emprestimo) {
        echo "<p style='color: red;'>A data de devolução não pode ser anterior à data de empréstimo.</p>";
        exit();
    }

    $stmt = $conn->prepare("UPDATE emprestimo SET id_aluno = ?, id_livro = ?, data_emprestimo = ?, data_devolucao = ? WHERE id = ?");
    $stmt->bind_param("iissi", $id_aluno, $id_livro, $data_emprestimo, $data_devolucao, $id_emprestimo);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Empréstimo atualizado com sucesso!</p>";
        // Atualiza dados na tela após submissão
        $emprestimo['id_aluno'] = $id_aluno;
        $emprestimo['id_livro'] = $id_livro;
        $emprestimo['data_emprestimo'] = $data_emprestimo;
        $emprestimo['data_devolucao'] = $data_devolucao;
    } else {
        echo "<p style='color: red;'>Erro ao atualizar: " . $stmt->error . "</p>";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="../frontend/registrar.css">
</head>

<body>
    <nav class="header">Biblioteca M.V.C
        <span id="toggleSidebar" class="openbtn" onclick="toggleNav()">&#9776;</span>
        <script>
            function toggleNav() {
                const sidebar = document.getElementById("mySidebar");
                const toggleBtn = document.getElementById("toggleSidebar");
                sidebar.classList.toggle("open");
                toggleBtn.innerHTML = sidebar.classList.contains("open") ? "&times;" : "&#9776;";
            }
        </script>
    </nav>

    <div class="sidebar" id="mySidebar">
        <ul>
            <li><a href="relatorios.php">Relatórios</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="mt-3 text-start">
        <a href="ver_emprestimos.php" class="link-back">&lt; Voltar</a>
    </div>

    <div class="container mt-4">
        <h2 class="text-center">Editar Empréstimo</h2>
        <form action="editar_emprestimo.php?id=<?php echo $id_emprestimo; ?>" method="POST">
            <!-- Campo Aluno -->
            <div class="mb-3">
                <label for="id_aluno" class="form-label">Aluno:</label>
                <select name="id_aluno" id="id_aluno" class="form-select" required>
                    <option value="<?php echo $emprestimo['id_aluno']; ?>" selected><?php echo htmlspecialchars($emprestimo['aluno_nome']); ?></option>
                </select>
            </div>

            <!-- Campo Livro -->
            <div class="mb-3">
                <label for="id_livro" class="form-label">Livro:</label>
                <select name="id_livro" id="id_livro" class="form-select" required>
                    <option value="<?php echo $emprestimo['id_livro']; ?>" selected><?php echo htmlspecialchars($emprestimo['nome_livro']); ?></option>
                </select>
            </div>

            <!-- Datas -->
            <div class="mb-3">
                <label for="data_emprestimo" class="form-label">Data de Empréstimo:</label>
                <input type="text" name="data_emprestimo" id="data_emprestimo" class="form-control" value="<?php echo converterDataParaBR($emprestimo['data_emprestimo']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="data_devolucao" class="form-label">Data de Devolução:</label>
                <input type="text" name="data_devolucao" id="data_devolucao" class="form-control" value="<?php echo converterDataParaBR($emprestimo['data_devolucao']); ?>" required>
            </div>

            <button type="submit" class="btn btn-gradient w-100">Atualizar Empréstimo</button>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
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
                        noResults: () => "Nenhum resultado encontrado"
                    }
                });
            }

            initSelect2('#id_aluno', 'buscar_alunos.php', 'Digite o nome do aluno');
            initSelect2('#id_livro', 'buscar_livros.php', 'Digite o nome do livro');

            flatpickr("#data_emprestimo", {
                dateFormat: "d/m/Y",
                locale: "pt"
            });

            flatpickr("#data_devolucao", {
                dateFormat: "d/m/Y",
                locale: "pt"
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