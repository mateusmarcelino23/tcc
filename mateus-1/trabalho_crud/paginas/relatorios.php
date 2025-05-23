<?php
// Conexão com o banco
include '../conexao.php';
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$paginaAnterior = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../index.php';

// Verifica se o usuário está logado
session_start();
if (!isset($_SESSION['professor_id'])) {
    header("Location: login.php");
    exit();
}

// Consulta 1: Alunos que mais leram
$sqlAlunos = "SELECT a.nome AS aluno_nome, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN aluno a ON e.id_aluno = a.id
              WHERE e.status = 'Devolvido'
              GROUP BY e.id_aluno
              ORDER BY total DESC
              LIMIT 10";
$resultAlunos = $conn->query($sqlAlunos);
$temAlunos = $resultAlunos->num_rows > 0;

// Consulta 2: Livros mais lidos
$sqlLivros = "SELECT l.nome_livro, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN livro l ON e.id_livro = l.id
              WHERE e.status = 'Devolvido'
              GROUP BY e.id_livro
              ORDER BY total DESC
              LIMIT 10";
$resultLivros = $conn->query($sqlLivros);
$temLivros = $resultLivros->num_rows > 0;

// Consulta 3: Séries que mais leram
$sqlSeries = "SELECT a.serie, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN aluno a ON e.id_aluno = a.id
              WHERE e.status = 'Devolvido'
              GROUP BY a.serie
              ORDER BY total DESC
              LIMIT 10";
$resultSeries = $conn->query($sqlSeries);
$temSeries = $resultSeries->num_rows > 0;

// Consulta para observações
$sqlNotas = "SELECT texto, data FROM anotacoes ORDER BY data DESC";
$resultNotas = $conn->query($sqlNotas);

?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Relatórios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../estilos/relatorios.css">
  <script src="https://www.gstatic.com/charts/loader.js"></script>

  <script>
    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawCharts);

    const temAlunos = <?php echo $temAlunos ? 'true' : 'false'; ?>;
    const temLivros = <?php echo $temLivros ? 'true' : 'false'; ?>;
    const temSeries = <?php echo $temSeries ? 'true' : 'false'; ?>;

    function drawCharts() {
      if (temAlunos) drawAlunosChart();
      if (temLivros) drawLivrosChart();
      if (temSeries) drawSeriesChart();
    }

    function drawAlunosChart() {
      var data = google.visualization.arrayToDataTable([
        ['Aluno', 'Livros Lidos'],
        <?php
          if ($resultAlunos->num_rows > 0) {
            while ($row = $resultAlunos->fetch_assoc()) {
              echo "['" . addslashes($row['aluno_nome']) . "', " . (int)$row['total'] . "],";
            }
          }
        ?>
      ]);

      var options = {
        title: 'Alunos que mais leram',
        legend: { position: 'none' },
        chartArea: { width: '70%' },
        height: 400,
        hAxis: {
          minValue: 0,
          textStyle: { color: 'transparent' }, // oculta os números
          gridlines: { color: 'transparent' }  // oculta as linhas de grade
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('graficoAlunos'));
      chart.draw(data, options);
    }

    function drawLivrosChart() {
      var data = google.visualization.arrayToDataTable([
        ['Livro', 'Leituras'],
        <?php
          if ($resultLivros->num_rows > 0) {
            while ($row = $resultLivros->fetch_assoc()) {
              echo "['" . addslashes($row['nome_livro']) . "', " . (int)$row['total'] . "],";
            }
          }
        ?>
      ]);

      var options = {
        title: 'Livros mais lidos',
        legend: { position: 'none' },
        chartArea: { width: '70%' },
        height: 400,
        hAxis: {
          minValue: 0,
          textStyle: { color: 'transparent' }, // oculta os números
          gridlines: { color: 'transparent' }  // oculta as linhas de grade
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('graficoLivros'));
      chart.draw(data, options);
    }

    function drawSeriesChart() {
      var data = google.visualization.arrayToDataTable([
        ['Série', 'Leituras'],
        <?php
          if ($resultSeries->num_rows > 0) {
            while ($row = $resultSeries->fetch_assoc()) {
              echo "['" . addslashes($row['serie']) . "', " . (int)$row['total'] . "],";
            }
          }
        ?>
      ]);

      var options = {
        title: 'Turmas que mais leram',
        legend: { position: 'none' },
        chartArea: { width: '70%' },
        height: 400,
        hAxis: {
          minValue: 0,
          textStyle: { color: 'transparent' }, // oculta os números
          gridlines: { color: 'transparent' }  // oculta as linhas de grade
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('graficoSeries'));
      chart.draw(data, options);
    }
  </script>
</head>
<body>
  <nav class="header">Biblioteca M.V.C</nav>

  <!-- Voltar para a página de antes com $paginaAnterior -->
  <div class="mt-3 text-start">
    <a href="<?php echo $paginaAnterior; ?>" class="link-back">< Voltar</a>
  </div>

  <div class="d-flex">
    <div class="relatorios-container flex-grow-1">
      <div id="graficoAlunos-container">
        <div id="graficoAlunos" class="grafico"></div>
        <?php if (!$temAlunos): ?>
          <div id="semDadosAlunos" class="alert alert-warning">Nenhum dado de empréstimo devolvido encontrado para alunos.</div>
        <?php endif; ?>
      </div>

      <div id="graficoLivros-container">
        <div id="graficoLivros" class="grafico"></div>
        <?php if (!$temLivros): ?>
          <div id="semDadosLivros" class="alert alert-warning">Nenhum dado de empréstimo devolvido encontrado para livros.</div>
        <?php endif; ?>
      </div>

      <div id="graficoSeries-container">
        <div id="graficoSeries" class="grafico"></div>
        <?php if (!$temSeries): ?>
          <div id="semDadosSeries" class="alert alert-warning">Nenhum dado de empréstimo devolvido encontrado para séries.</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- BARRA LATERAL -->
    <div class="sidebar">
      <h5 class="mt-3">Observações dos Professores</h5>
      <div class="lista-observacoes">
        <?php if ($resultNotas->num_rows > 0): ?>
          <?php while ($nota = $resultNotas->fetch_assoc()): ?>
            <div class="mb-2">
              <small><strong><?php echo date('d/m/Y', strtotime($nota['data'])); ?></strong></small><br>
              <p><?php echo nl2br(htmlspecialchars($nota['texto'])); ?></p>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="text-muted">Nenhuma observação registrada.</p>
        <?php endif; ?>
      </div>
      <button onclick="document.getElementById('novaAnotacao').style.display='block'" class="btn btn-primary w-100 mt-3">Nova Anotação</button>

      <div id="novaAnotacao" class="nova-anotacao mt-3" style="display: none;">
        <form method="POST" action="salvar_anotacao.php">
          <textarea name="texto" class="form-control mb-2" rows="4" placeholder="Escreva sua observação..." required></textarea>
          <button type="submit" class="btn btn-success w-100">Salvar</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>

<?php
$conn->close();
?>