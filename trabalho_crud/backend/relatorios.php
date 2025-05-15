<?php
// Conexão com o banco
$conn = new mysqli('localhost', 'root', '', 'crud_db');
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$paginaAnterior = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../index.php';

// Consulta 1: Alunos que mais leram
$sqlAlunos = "SELECT a.nome AS aluno_nome, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN aluno a ON e.id_aluno = a.id
              WHERE e.status = 'Devolvido'
              GROUP BY e.id_aluno
              ORDER BY total DESC
              LIMIT 10";
$resultAlunos = $conn->query($sqlAlunos);

// Consulta 2: Livros mais lidos
$sqlLivros = "SELECT l.nome_livro, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN livro l ON e.id_livro = l.id
              WHERE e.status = 'Devolvido'
              GROUP BY e.id_livro
              ORDER BY total DESC
              LIMIT 10";
$resultLivros = $conn->query($sqlLivros);

// Consulta 3: Séries que mais leram
$sqlSeries = "SELECT a.serie, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN aluno a ON e.id_aluno = a.id
              WHERE e.status = 'Devolvido'
              GROUP BY a.serie
              ORDER BY total DESC
              LIMIT 10";
$resultSeries = $conn->query($sqlSeries);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../frontend/relatorios.css">
  <script src="https://www.gstatic.com/charts/loader.js"></script>

  <nav class="header">Biblioteca M.V.C</nav>
  <a href="<?php echo $paginaAnterior; ?>" class="link-back">< Voltar</a>

  <script>
    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
      drawAlunosChart();
      drawLivrosChart();
      drawSeriesChart();
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
  <div class="relatorios-container">
    <div class="grafico" id="graficoAlunos"></div>
    <div class="grafico" id="graficoLivros"></div>
    <div class="grafico" id="graficoSeries"></div>
  </div>
</body>
</html>

<?php
$conn->close();
?>