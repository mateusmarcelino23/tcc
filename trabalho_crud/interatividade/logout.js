// quando o usuário clicar no link de logout, pergunta se ele tem certeza
document.getElementById('logoutLink').addEventListener('click', function(event) {
    event.preventDefault(); // Evita o logout imediato

    const confirmed = confirm('Você tem certeza que deseja sair?');

    if (confirmed) {
        // Se confirmou, vai para logout
        window.location.href = this.href;
    } else {
        // Se cancelou, fecha a sidebar removendo a classe 'open'
        const sidebar = document.getElementById("mySidebar");
        sidebar.classList.remove("open");

        // Também atualiza o botão de toggle para mostrar o ícone correto
        const toggleBtn = document.getElementById("toggleSidebar");
        if (toggleBtn) {
            toggleBtn.innerHTML = "&#9776;";
        }
    }
});