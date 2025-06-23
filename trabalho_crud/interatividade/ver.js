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