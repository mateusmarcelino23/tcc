<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    // Caso o professor não esteja logado, redireciona para a página de login
    header("Location: login.php");
    exit();
}

// Conectar com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'crud_db');

// Verifica a conexão com o banco de dados
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta para buscar todos os empréstimos com os dados relacionados
$sql = "SELECT e.id, e.data_emprestimo, e.data_devolucao, e.status, a.nome AS aluno_nome, l.nome_livro, l.nome_autor, p.nome AS professor_nome
        FROM emprestimo e
        JOIN aluno a ON e.id_aluno = a.id
        JOIN livro l ON e.id_livro = l.id
        JOIN professor p ON e.id_professor = p.id";
$result = $conn->query($sql);

// Verifica se a ação de remoção foi solicitada
if (isset($_GET['remover'])) {
    $id_emprestimo = $_GET['remover'];
    $sql_remover = "DELETE FROM emprestimo WHERE id = $id_emprestimo";
    if ($conn->query($sql_remover) === TRUE) {
        header("Location: ver_emprestimos.php");
        exit();
    } else {
        echo "Erro ao remover o empréstimo: " . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Empréstimos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../frontend/ver.css">

<div class="mt-3 text-start">
<a href="painel.php" class="link-back">< Voltar para o painel</a>
</div>

    
</head>
<body>
<div class="header">Biblioteca M.V.C</div>
</div>

<a href="registrar_emprestimo.php" class="link-registrar">Registrar Empréstimo</a>

<div class="container mt-4">
    <h2 class="text-center">Lista de Empréstimos</h2>
    <div class="text-end mb-2">
    
    <table id="emprestimosTable" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Aluno</th>
                <th>Livro</th>
                <th>Autor</th>
                <th>Empréstimo</th>
                <th>Devolução</th>
                <th>Professor</th>
                <th>Status</th>
                <th>Devolver</th>
                <th>Editar</th>
                <th>Remover</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($emprestimo = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $emprestimo['id']; ?></td>
                <td><?php echo $emprestimo['aluno_nome']; ?></td>
                <td><?php echo $emprestimo['nome_livro']; ?></td>
                <td><?php echo $emprestimo['nome_autor']; ?></td>
                <td><?php echo !empty($emprestimo['data_emprestimo']) && $emprestimo['data_emprestimo'] !== '0000-00-00' ? date("d/m/Y", strtotime($emprestimo['data_emprestimo'])) : "-"; ?></td>
                <td><?php echo !empty($emprestimo['data_devolucao']) && $emprestimo['data_devolucao'] !== '0000-00-00' ? date("d/m/Y", strtotime($emprestimo['data_devolucao'])) : "-"; ?></td>
                <td><?php echo $emprestimo['professor_nome']; ?></td>
                <td>
                    <?php if ($emprestimo['status'] == 0): ?>Pendente
                    <?php elseif ($emprestimo['status'] == 1): ?>Realizado
                    <?php elseif ($emprestimo['status'] == 2): ?>Devolvido
                    <?php elseif ($emprestimo['status'] == 3): ?>Atraso
                    <?php endif; ?>
                </td>
                <td><button class="status-entregue" onclick="devolverEmpréstimo(<?php echo $emprestimo['id']; ?>)">Devolvido</button></td>
            </div>

</div>
<script>
function devolverEmpréstimo(id) {
    if (confirm('Tem certeza de que deseja devolver este empréstimo?')) {
        fetch('devolver_emprestimo.php?id=' + id)
            .then(response => response.text())
            .then(data => {
                if (data == 'ok') {
                    alert('Empréstimo devolvido com sucesso!');
                    location.reload();
                } else {
                    alert('Erro ao devolver empréstimo!');
                }
            });
    }
}
</script>
                <td><button class="edit-link" onclick="location.href='editar_emprestimo.php?id=<?php echo $emprestimo['id']; ?>'">Editar</button></td>
                <td><a href="?remover=<?php echo $emprestimo['id']; ?>" class="delete-link" onclick="return confirm('Tem certeza de que deseja remover este empréstimo?')">Remover</a></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#emprestimosTable').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
        }
    });
});
</script>
</body>

</html>

