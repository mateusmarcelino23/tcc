<?php
include '../backend/editar_emprestimo.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Empréstimo</title>
    <link rel="icon" href="../imagens/1748908346791.png" type="image/x-icon">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="../estilos/style.css">
    <link rel="stylesheet" href="../estilos/registrar.css">
</head>

<body>
    <!-- Cabeçalho -->
    <nav class="header">
        <a href="../../" class="header-link">
            <img src="../imagens/1748908346791.png" alt="Logo" class="header-logo" />
            <span class="header-text">Biblioteca M.V.C </span>
        </a>
        <span id="toggleSidebar" class="openbtn" onclick="toggleNav()">&#9776;</span>
    </nav>

    <!-- Menu lateral -->
    <div class="sidebar" id="mySidebar">
        <ul>
            <li><a href="relatorios_front.php">Relatórios</a></li>
            <li><a href="../backend/logout.php" id="logoutLink">Logout</a></li>
        </ul>
    </div>

    <div class="mt-3 text-start">
        <a href="ver_emprestimos_front.php" class="link-back">&lt; Voltar</a>
    </div>

    <!-- Mensagem de feedback -->
    <div class="mensagem">
        <?php
        if (isset($_SESSION['mensagem_editar_emprestimo'])) {
            echo $_SESSION['mensagem_editar_emprestimo'];
            unset($_SESSION['mensagem_editar_emprestimo']);
        }
        ?>
    </div>

    <div class="container mt-4">
        <h2 class="text-center">Editar Empréstimo</h2>
        <form action="../backend/editar_emprestimo.php?id=<?php echo $id_emprestimo; ?>" method="POST">
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
                <label for="data_emprestimo" class="form-label">Data de Retirada:</label>
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
        $(document).ready(function() {
            function initSelect2(selector, url, placeholderText) {
                $(selector).select2({
                    placeholder: placeholderText,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 1,
                    language: {
                        noResults: () => "Nenhum resultado encontrado"
                    }
                });
            }

            initSelect2('#id_aluno', '../backend/buscar_alunos.php', 'Digite o nome do aluno');
            initSelect2('#id_livro', '../backend/buscar_livros.php', 'Digite o nome do livro');

            flatpickr("#data_emprestimo", {
                dateFormat: "d/m/Y",
                locale: "pt"
            });

            flatpickr("#data_devolucao", {
                dateFormat: "d/m/Y",
                locale: "pt"
            });

            // Verificação antes de enviar o formulário
            $('form').on('submit', function(e) {
                const dataEmprestimoStr = $('#data_emprestimo').val();
                const dataDevolucaoStr = $('#data_devolucao').val();

                const [diaEmp, mesEmp, anoEmp] = dataEmprestimoStr.split('/');
                const [diaDev, mesDev, anoDev] = dataDevolucaoStr.split('/');

                const dataEmprestimo = new Date(`${anoEmp}-${mesEmp}-${diaEmp}`);
                const dataDevolucao = new Date(`${anoDev}-${mesDev}-${diaDev}`);

                if (dataDevolucao < dataEmprestimo) {
                    e.preventDefault(); // Impede o envio do formulário
                    alert("A data de devolução não pode ser anterior à data de retirada.");
                    return false;
                }
            });
        });
    </script>

    <!-- Link para arquivos JS -->
    <script src="../interatividade/script.js"></script>
    <script src="../interatividade/devtools_block.js"></script>
    <script src="../interatividade/logout.js"></script>

</body>

</html>