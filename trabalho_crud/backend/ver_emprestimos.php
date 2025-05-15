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
    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link para o CSS do DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- Link para o CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="../frontend/ver.css">

<div class="mt-3 text-start">
<a href="../../index.php" class="link-back">< Voltar para o painel</a>
</div>

</head>

<body>
    <!-- Cabeçalho -->
    <nav class="header">Biblioteca M.V.C
            <!-- Botão para abrir/fechar o menu lateral -->
            <span id="toggleSidebar" class="openbtn" onclick="toggleNav()">&#9776;</span>


            <script>
                function toggleNav() {
                    const sidebar = document.getElementById("mySidebar");
                    const toggleBtn = document.getElementById("toggleSidebar");

                    if (sidebar.classList.contains("open")) {
                        sidebar.classList.remove("open");
                        toggleBtn.innerHTML = "&#9776;"; // ícone de abrir
                    } else {
                        sidebar.classList.add("open");
                        toggleBtn.innerHTML = "&times;"; // ícone de fechar
                    }
                }
            </script>

    </nav>

    <!-- Menu lateral -->
    <div class="sidebar" id="mySidebar">
        <ul>
            <li><a href="relatorios.php">Relatórios</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

<a href="registrar_emprestimo.php" class="link-registrar">Registrar Empréstimo</a>

<div class="container mt-4">
  <h2 class="text-center">Lista de Empréstimos</h2>
  <div class="text-end mb-3">
    <a href="registrar_emprestimo.php" class="link-registrar">Registrar Empréstimo</a>
  </div>

  <div class="table-container">
    <table id="emprestimosTable" class="table table-striped">
      <thead>
        <tr>
          <th>Aluno</th>
          <th>Livro</th>
          <th>Empréstimo</th>
          <th>Devolução</th>
          <th>Professor</th>
          <th>Status</th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        $hoje = date('Y-m-d');
        while ($emprestimo = $result->fetch_assoc()):
          $status = '';
          $classeStatus = '';

          if ($emprestimo['data_devolucao'] < $hoje) {
            $status = 'Atrasado';
            $classeStatus = 'status-atrasado';
          } else {
            $status = 'Em andamento';
            $classeStatus = 'status-andamento';
          }
        ?>
          <tr>
            <td><?php echo $emprestimo['aluno_nome']; ?></td>
            <td><?php echo $emprestimo['nome_livro']; ?></td>
            <td><?php echo date("d/m/Y", strtotime($emprestimo['data_emprestimo'])); ?></td>
            <td>
              <?php
              echo !empty($emprestimo['data_devolucao']) && $emprestimo['data_devolucao'] !== '0000-00-00'
                ? date("d/m/Y", strtotime($emprestimo['data_devolucao']))
                : "-";
              ?>
            </td>
            <td><?php echo $emprestimo['professor_nome']; ?></td>
            <!-- COLUNA DE STATUS (SEM DEVOLVIDO) -->
            <td><span class="badge <?php echo $classeStatus; ?>"><?php echo $status; ?></span></td>
            <td>
            <button class="status-entregue" onclick="devolverEmprestimo(<?php echo $emprestimo['id']; ?>)">
                Confirmar devolução
            </button>
            </td>
            <td>
              <button class="edit-link" onclick="location.href='editar_emprestimo.php?id=<?php echo $emprestimo['id']; ?>'">
                Editar
              </button>
            </td>
            <td>
              <a href="?remover=<?php echo $emprestimo['id']; ?>" class="delete-link" onclick="return confirm('Tem certeza de que deseja remover este empréstimo?')">
                Remover
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#emprestimosTable').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
      }
    });
  });

  function devolverEmprestimo(id) {
    if (confirm('Confirmar devolução do empréstimo')) {
      fetch('devolver_emprestimo.php?id=' + id)
        .then(response => response.text())
        .then(data => {
          if (data === 'ok') {
            location.reload();
          } else {
            alert('Erro ao devolver empréstimo!');
          }
        });
    }
  }
</script>

</body>
</html>