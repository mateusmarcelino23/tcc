function toggleDevolucao(botao, id) {
    const devolvido = botao.getAttribute('data-devolvido') === 'true';

    if (!devolvido) {
        if (confirm('Confirmar devolução do empréstimo?')) {
            fetch('../backend/devolver_emprestimo.php?id=' + id)
                .then(res => res.text())
                .then(data => {
                    if (data.trim() === 'ok') {
                        const linha = document.getElementById(id);
                        const statusCell = linha.querySelector('td:nth-child(6) span');
                        statusCell.className = 'badge status-devolvido';
                        statusCell.textContent = 'Devolvido';
                        botao.textContent = 'Cancelar devolução';
                        botao.setAttribute('data-devolvido', 'true');
                    } else {
                        alert('Erro: ' + data);
                    }
                }).catch(e => alert('Erro na requisição: ' + e));
        }
    } else {
        if (confirm('Deseja cancelar a devolução?')) {
            fetch('../backend/cancelar_devolucao.php?id=' + id)
                .then(res => res.text())
                .then(data => {
                    if (data.trim() === 'ok') {
                        const linha = document.getElementById(id);
                        const statusCell = linha.querySelector('td:nth-child(6) span');
                        const dataDevolucaoStr = linha.querySelector('td:nth-child(4)').textContent.trim();

                        if (dataDevolucaoStr === '-' || dataDevolucaoStr === '') {
                            statusCell.className = 'badge status-andamento';
                            statusCell.textContent = 'Em andamento';
                        } else {
                            const partes = dataDevolucaoStr.split('/');
                            const dataDev = new Date(partes[2], partes[1] - 1, partes[0]);
                            const hoje = new Date(); hoje.setHours(0,0,0,0);

                            if (dataDev < hoje) {
                                statusCell.className = 'badge status-atrasado';
                                statusCell.textContent = 'Atrasado';
                            } else {
                                statusCell.className = 'badge status-andamento';
                                statusCell.textContent = 'Em andamento';
                            }
                        }

                        botao.textContent = 'Confirmar devolução';
                        botao.setAttribute('data-devolvido', 'false');
                    } else {
                        alert('Erro: ' + data);
                    }
                }).catch(e => alert('Erro na requisição: ' + e));
        }
    }
}