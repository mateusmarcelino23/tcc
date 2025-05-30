<?php
// Conexão com o banco
include '../conexao.php';
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Verifica se o usuário está logado
session_start();
if (!isset($_SESSION['professor_id'])) {
    header("Location: login.php");
    exit();
}

// fuso horário
date_default_timezone_set('America/Sao_Paulo');


// Consulta 1: Alunos que mais leram
$sqlAlunos = "SELECT a.nome AS aluno_nome, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN aluno a ON e.id_aluno = a.id
              WHERE e.status = 2
              GROUP BY e.id_aluno
              ORDER BY total DESC
              LIMIT 5";
$resultAlunos = $conn->query($sqlAlunos);
$temAlunos = $resultAlunos->num_rows > 0;

// Consulta 2: Livros mais lidos
$sqlLivros = "SELECT l.nome_livro, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN livro l ON e.id_livro = l.id
              WHERE e.status = 2
              GROUP BY e.id_livro
              ORDER BY total DESC
              LIMIT 5";
$resultLivros = $conn->query($sqlLivros);
$temLivros = $resultLivros->num_rows > 0;

// Consulta 3: Séries que mais leram
$sqlSeries = "SELECT a.serie, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN aluno a ON e.id_aluno = a.id
              WHERE e.status = 2
              GROUP BY a.serie
              ORDER BY total DESC
              LIMIT 5";
$resultSeries = $conn->query($sqlSeries);
$temSeries = $resultSeries->num_rows > 0;

// Consulta para observações
$sqlNotas = "SELECT n.id, n.texto, n.data, p.nome AS professor_nome, CONVERT_TZ(data, '+00:00', '-05:00') AS data_corrigida
             FROM anotacoes n
             JOIN professor p ON n.id_professor = p.id
             ORDER BY n.data DESC";
$resultNotas = $conn->query($sqlNotas);
$temNotas = $resultNotas->num_rows > 0;

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
        title: 'Top 5 Alunos que mais leram',
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
        title: 'Top 5 Livros mais lidos',
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
        title: 'Top 5 Turmas que mais leram',
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
    <a href="../../" class="link-back">&lt; Voltar</a>
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
  <div class="lista-observacoes">
    <?php if ($resultNotas->num_rows > 0): ?>
      <?php while ($nota = $resultNotas->fetch_assoc()): ?>
        <div class="card">
          <button class="btn-excluir" data-id="<?php echo $nota['id']; ?>">x</button>
          <div class="card-header">
            <strong><?php echo htmlspecialchars($nota['professor_nome']); ?></strong>
            <span class="data-anotacao">
              <?php echo date('d/m/Y - H:i', strtotime($nota['data_corrigida'])); ?>
            </span>
          </div>
          <div class="card-body">
            <p class="card-text"><?php echo nl2br(htmlspecialchars($nota['texto'])); ?></p>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="alert alert-warning">Nenhuma anotação encontrada.</div>
    <?php endif; ?>
  </div>

  <button id="btnNovaAnotacao" class="btn">Nova Anotação</button>

  <div id="novaAnotacao" class="nova-anotacao" style="display: none;">
    <form method="POST" action="salvar_anotacao.php">
      <textarea name="texto" class="form-control mb-2" rows="4" placeholder="Escreva sua observação..." required></textarea>
      <div class="btn-group-center">
        <button type="submit" class="btn-sal">Salvar</button>
        <button type="button" id="btnCancelar" class="btn-can">Cancelar</button>
      </div>
    </form>
  </div>

</div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.getElementById('btnNovaAnotacao').addEventListener('click', function() {
    document.getElementById('novaAnotacao').style.display = 'block';
    this.style.display = 'none';
  });

  document.getElementById('btnCancelar').addEventListener('click', function() {
    document.getElementById('novaAnotacao').style.display = 'none';
    document.getElementById('btnNovaAnotacao').style.display = 'inline-block';
  });

    document.querySelectorAll('.btn-excluir').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.dataset.id;
      console.log('Tentando excluir anotação ID:', id);  // Debug no console
      if (confirm('Tem certeza que deseja excluir esta anotação?')) {
        fetch('excluir_anotacao.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: 'id=' + encodeURIComponent(id)
        })
        .then(response => response.text())
        .then(result => {
          console.log('Resposta do servidor:', result); // Debug resposta
          location.reload();
        })
        .catch(err => {
          alert('Erro ao excluir anotação.');
          console.error(err);
        });
      }
    });
  });
</script>

<!-- Link para a tratativa do JS -->
<script src="../tratativa/script.js"></script>

</html>

<?php
$conn->close();
?>