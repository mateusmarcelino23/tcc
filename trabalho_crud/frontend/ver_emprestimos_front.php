<?php
include '../backend/ver_emprestimos.php'; // Inclui o script de backend para buscar empréstimos
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Lista de Empréstimos</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="../estilos/style.css">
    <link rel="stylesheet" href="../estilos/ver.css">
</head>

<body>

    <!-- Link para voltar ao painel -->
    <div class="mt-3 text-start">
        <a href="../../" class="link-back">&lt; Voltar para o painel</a>
    </div>

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

    <!-- Botão para registrar empréstimo -->
    <a href="registrar_emprestimo.php" class="link-registrar">Registrar Empréstimo</a>

    <!-- Conteúdo principal -->
    <div class="container mt-4">
        <h2 class="text-center">Lista de Empréstimos</h2>

        <div class="text-end mb-3">
            <a href="registrar_emprestimo_front.php" class="link-registrar">Registrar Empréstimo</a>
        </div>

        <div class="table-container">
            <table id="emprestimosTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Livro</th>
                        <th>Retirada</th>
                        <th>Devolução</th>
                        <th>Professor</th>
                        <th>Status</th>
                        <th></th> <!-- Confirmar devolução ou cancelar -->
                        <th></th> <!-- Editar -->
                        <th></th> <!-- Remover -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Data atual
                    $hoje = date('Y-m-d');

                    // Itera sobre todos os empréstimos retornados pela consulta
                    while ($emprestimo = $result->fetch_assoc()):
                        // Determina o status textual e a classe CSS
                        $status = '';
                        $classeStatus = '';

                        // Define o status com base na coluna status do banco
                        switch ($emprestimo['status']) {
                            case 2:
                                $status = 'Devolvido';
                                $classeStatus = 'status-devolvido';
                                break;
                            case 1:
                                $status = 'Pendente';
                                $classeStatus = 'status-atrasado';
                                break;
                            default:
                                if ($emprestimo['data_devolucao'] <= $hoje) {
                                    $status = 'Pendente';
                                    $classeStatus = 'status-atrasado';
                                } else {
                                    $status = 'Em andamento';
                                    $classeStatus = 'status-andamento';
                                }
                        }

                        // Define se está devolvido com base no status
                        $estaDevolvido = ($emprestimo['status'] == 2);
                    ?>
                        <tr id="linha-<?php echo $emprestimo['id']; ?>">
                            <!-- Nome do aluno -->
                            <td class="scrollable-cell"><?php echo htmlspecialchars($emprestimo['aluno_nome']); ?></td>

                            <!-- Nome do livro -->
                            <td class="scrollable-cell"><?php echo htmlspecialchars($emprestimo['nome_livro']); ?></td>

                            <!-- Data de empréstimo formatada -->
                            <td class="scrollable-cell"><?php echo date("d/m/Y", strtotime($emprestimo['data_emprestimo'])); ?></td>

                            <!-- Data de devolução formatada ou "-" se não tiver -->
                            <td class="scrollable-cell">
                                <?php
                                echo (!empty($emprestimo['data_devolucao']) && $emprestimo['data_devolucao'] != '0000-00-00')
                                    ? date("d/m/Y", strtotime($emprestimo['data_devolucao']))
                                    : "-";
                                ?>
                            </td>

                            <!-- Nome do professor -->
                            <td class="scrollable-cell"><?php echo htmlspecialchars($emprestimo['professor_nome']); ?></td>

                            <!-- Status com badge -->
                            <td class="scrollable-cell"><span class="badge <?php echo $classeStatus; ?>"><?php echo $status; ?></span></td>

                            <!-- Botão de devolução ou cancelar devolução, dependendo do status atual -->
                            <td class="scrollable-cell">
                                <button class="status-entregue"
                                    data-devolvido="<?php echo $estaDevolvido ? 'true' : 'false'; ?>"
                                    onclick="toggleDevolucao(this, <?php echo $emprestimo['id']; ?>)">
                                    <?php echo $estaDevolvido ? 'Cancelar devolução' : 'Confirmar devolução'; ?>
                                </button>
                            </td>

                            <!-- Botão de edição -->
                            <td class="scrollable-cell">
                                <button class="edit-link" onclick="location.href='editar_emprestimo_front.php?id=<?php echo $emprestimo['id']; ?>'">
                                    Editar
                                </button>
                            </td>

                            <!-- Link para remover com confirmação -->
                            <td class="scrollable-cell">
                                <a href="?remover=<?php echo $emprestimo['id']; ?>"
                                    class="delete-link"
                                    onclick="return confirm('Tem certeza de que deseja remover este empréstimo?')">
                                    Remover
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts de dependências -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        // Inicializa DataTables com idioma em português
        $(document).ready(function() {
            $('#emprestimosTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
                }
            });
        });

        // Função para alternar status de devolução ou cancelar devolução
        function toggleDevolucao(botao, id) {
            // Obtém o estado atual do botão
            const devolvido = botao.getAttribute('data-devolvido') === 'true';

            if (!devolvido) {
                // Se não está devolvido, confirma a devolução
                if (confirm('Confirmar devolução do empréstimo?')) {
                    fetch('../backend/devolver_emprestimo.php?id=' + id)
                        .then(response => response.text())
                        .then(data => {
                            if (data.trim() === 'ok') {
                                // Atualiza visualmente o status para devolvido
                                const linha = document.getElementById('linha-' + id);
                                const statusCell = linha.querySelector('td:nth-child(6) span');
                                statusCell.className = 'badge status-devolvido';
                                statusCell.textContent = 'Devolvido';
                                // Atualiza o botão para cancelar
                                botao.textContent = 'Cancelar devolução';
                                botao.setAttribute('data-devolvido', 'true');
                            } else {
                                alert('Erro ao devolver empréstimo: ' + data);
                            }
                        })
                        .catch(error => alert('Erro na requisição: ' + error));
                }
            } else {
                // Se está devolvido, confirma o cancelamento
                if (confirm('Deseja cancelar a devolução?')) {
                    fetch('../backend/cancelar_devolucao.php?id=' + id)
                        .then(response => response.text())
                        .then(data => {
                            if (data.trim() === 'ok') {
                                const linha = document.getElementById('linha-' + id);
                                const statusCell = linha.querySelector('td:nth-child(6) span');

                                // Pega a data de devolução do texto (formato dd/mm/yyyy)
                                const dataDevolucaoStr = linha.querySelector('td:nth-child(4)').textContent.trim();

                                if (dataDevolucaoStr === '-' || dataDevolucaoStr === '') {
                                    // Sem data de devolução definida, assume andamento
                                    statusCell.className = 'badge status-andamento';
                                    statusCell.textContent = 'Em andamento';
                                } else {
                                    // Converte para objeto Date
                                    const partes = dataDevolucaoStr.split('/');
                                    const dataDev = new Date(partes[2], partes[1] - 1, partes[0]); // Ano, mês (0-based), dia
                                    const hoje = new Date();
                                    hoje.setHours(0, 0, 0, 0); // Zera horas para só comparar data

                                    if (dataDev < hoje) {
                                        // Se a data de devolução já passou, marca como atrasado
                                        statusCell.className = 'badge status-atrasado';
                                        statusCell.textContent = 'Atrasado';
                                    } else {
                                        // Senão, ainda está dentro do prazo
                                        statusCell.className = 'badge status-andamento';
                                        statusCell.textContent = 'Em andamento';
                                    }
                                }

                                // Atualiza botão
                                botao.textContent = 'Confirmar devolução';
                                botao.setAttribute('data-devolvido', 'false');
                            } else {
                                alert('Erro ao cancelar devolução: ' + data);
                            }
                        })

                        .catch(error => alert('Erro na requisição: ' + error));
                }
            }
        }
    </script>

    <!-- Link para arquivos JS -->
    <script src="../interatividade/script.js"></script>
    <script src="../interatividade/devtools_block.js"></script>
    <script src="../interatividade/logout.js"></script>

</body>

</html>