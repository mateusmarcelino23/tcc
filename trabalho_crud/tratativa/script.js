// Função para redirecionar
function redirect() {
    window.location.href = '/tcc/trabalho_crud/tratativa/tratativa.html';
}

// Bloquear botão direito do mouse
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
});

// Bloquear teclas comuns para abrir DevTools
document.addEventListener('keydown', function(e) {
    if (
        e.key === 'F12' ||
        (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) ||
        (e.ctrlKey && e.key.toLowerCase() === 'u')
    ) {
        e.preventDefault();
        redirect();
    }
});

// Detectar DevTools aberto por diferença de tempo
let devtoolsOpen = false;
const threshold = 160;

setInterval(function() {
    const start = new Date();
    debugger;
    const end = new Date();
    if (end - start > threshold) {
        if (!devtoolsOpen) {
            devtoolsOpen = true;
            redirect();
        }
    } else {
        devtoolsOpen = false;
    }
}, 1000);

// função de tratamento de erro
function tratarErro(error, mensagemUsuario = "Algo deu errado. Por favor, tente novamente.") {
    console.error("Erro:", error);
    alert(mensagemUsuario);
  }
  
  // tratamento global de erros JS
  window.addEventListener('error', function(event) {
    tratarErro(event.error || event.message);
  });
  
  window.addEventListener('unhandledrejection', function(event) {
    tratarErro(event.reason);
  });