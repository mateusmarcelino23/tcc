// SALVAR ANOTAÇÃO
const formNovaAnotacao = document.getElementById('formNovaAnotacao');

formNovaAnotacao.addEventListener('submit', function(e) {
  e.preventDefault();

  const formData = new FormData(formNovaAnotacao);

  fetch('../backend/salvar_anotacao.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      location.reload();
    }
  })
  .catch(err => {
    console.error('Erro ao salvar anotação:', err);
  });
});

// EXCLUIR ANOTAÇÃO
document.querySelectorAll('.btn-excluir').forEach(btn => {
  btn.addEventListener('click', function() {
    const id = this.dataset.id;

    if (confirm('Tem certeza que deseja excluir esta anotação?')) {
      const formData = new URLSearchParams();
      formData.append('id', id);

      fetch('../backend/excluir_anotacao.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          location.reload();
        }
      })
      .catch(err => {
        console.error('Erro ao excluir anotação:', err);
      });
    }
  });
});