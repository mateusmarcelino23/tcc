<?php
// $alunos = [33, 34, 35, 36, 37, 77];
// $livros = [26, 29];
// $dataBase = strtotime('2025-05-01');
// $linhas = [];

// for ($i = 0; $i < 80; $i++) {
//     $id_aluno = $alunos[$i % count($alunos)];
//     $id_livro = $livros[$i % count($livros)];
//     $data_emprestimo = date('Y-m-d', strtotime("+$i days", $dataBase));
//     $data_devolucao = date('Y-m-d', strtotime("+".($i + 9)." days", $dataBase));
//     $linhas[] = "($id_aluno, 20, $id_livro, '$data_emprestimo', '$data_devolucao', '0')";
// }

// echo "INSERT INTO emprestimo (id_aluno, id_professor, id_livro, data_emprestimo, data_devolucao, status) VALUES\n";
// echo implode(",\n", $linhas) . ";";

$alunos = [33, 34, 35, 36, 37, 77];
$livros = [26, 29];
$dataBase = strtotime('2025-05-01');
$linhas = [];

for ($i = 0; $i < 80; $i++) {
    $id_aluno = $alunos[$i % count($alunos)];
    $id_livro = $livros[$i % count($livros)];
    $data_emprestimo = date('Y-m-d', strtotime("+$i days", $dataBase));
    $data_devolucao = date('Y-m-d', strtotime("+".($i + 9)." days", $dataBase));
    $linhas[] = "($id_aluno, 20, $id_livro, '$data_emprestimo', '$data_devolucao', '0')";
}

echo "INSERT INTO emprestimo (id_aluno, id_professor, id_livro, data_emprestimo, data_devolucao, status) VALUES\n";
echo implode(",\n", $linhas) . ";";
?>
