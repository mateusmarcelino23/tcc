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

// Função para abrir sidebar
function toggleNav() {
    const sidebar = document.getElementById("mySidebar");
    const toggleBtn = document.getElementById("toggleSidebar");
    sidebar.classList.toggle("open");
    toggleBtn.innerHTML = sidebar.classList.contains("open") ? "&times;" : "&#9776;";
  }
  
  window.addEventListener('load', () => {
    const sidebar = document.getElementById("mySidebar");
    const toggleBtn = document.getElementById("toggleSidebar");
    sidebar.classList.remove("open");
    toggleBtn.innerHTML = "&#9776;";
  });  

  function closeSidebarInstantly() {
    const sidebar = document.getElementById("mySidebar");
    const toggleBtn = document.getElementById("toggleSidebar");
  
    // Desliga a transição
    sidebar.style.transition = 'none';
  
    // Fecha o menu
    sidebar.classList.remove("open");
    toggleBtn.innerHTML = "&#9776;";
  
    // Força reflow pra garantir
    void sidebar.offsetWidth;
  
    // Se quiser, liga de novo a transição depois
    sidebar.style.transition = ''; // ou coloca 'transform 0.3s ease' se quiser
  }
  
  // Fecha o menu sempre que voltar pra página
  window.addEventListener('pageshow', (event) => {
    closeSidebarInstantly();
  });
   
// Mensagem que aparece após registro ou edição
window.onload = function() {
  var mensagemElement = document.querySelector('.mensagem');
  if (mensagemElement) {
      setTimeout(function() {
          mensagemElement.style.opacity = '0';
          setTimeout(function() {
              mensagemElement.style.display = 'none';
          }, 500); // tempo para a transição de opacidade
      }, 3000); // tempo que a mensagem ficará visível
  }
};