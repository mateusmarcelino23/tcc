CREATE DATABASE IF NOT EXISTS crud_db;
USE crud_db;

CREATE TABLE IF NOT EXISTS aluno (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(90) NOT NULL,
  serie VARCHAR(12) NOT NULL,
  email VARCHAR(200) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS professor (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(90) NOT NULL,
  cpf CHAR(11) NOT NULL UNIQUE,
  email VARCHAR(50) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  token_recuperacao VARCHAR(64) DEFAULT NULL,
  token_expiracao DATETIME DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS livro (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  nome_livro VARCHAR(190) NOT NULL,
  nome_autor VARCHAR(130) NOT NULL,
  isbn VARCHAR(20) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS anotacoes (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  id_professor INT(11) NOT NULL,
  texto TEXT NOT NULL,
  data TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_anotacao_professor FOREIGN KEY (id_professor) REFERENCES professor(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS emprestimo (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  id_aluno INT(11) NOT NULL,
  id_professor INT(11) NOT NULL,
  id_livro INT(11) NOT NULL,
  data_emprestimo DATE NOT NULL,
  data_devolucao DATE NOT NULL,
  status TINYINT(2) NOT NULL,
  CONSTRAINT fk_emprestimo_aluno FOREIGN KEY (id_aluno) REFERENCES aluno(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_emprestimo_professor FOREIGN KEY (id_professor) REFERENCES professor(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_emprestimo_livro FOREIGN KEY (id_livro) REFERENCES livro(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Dados iniciais
INSERT INTO professor (nome, cpf, email, senha) VALUES
('Professor', '00000000000', 'professor@email.com', '$2y$10$9wsKRk73Ak7JUVY88kKfM.fXP1c5t9aMP/o2J3IxJ/AsaVrCEpjZq');

INSERT INTO aluno (nome, serie, email) VALUES
('Aluno', '1º Ano EM A', 'aluno@gmail.com');