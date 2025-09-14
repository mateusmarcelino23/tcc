// Checa se o professor está logado
$.ajax({
    url: '../backend/verifica_sessao.php',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
        if (!response.logged_in) {
            // Redireciona para a página inicial ou login
            window.location.href = '../';
        }
    },
    error: function() {
        // Caso haja erro na requisição, também pode redirecionar
        window.location.href = '../';
    }
});