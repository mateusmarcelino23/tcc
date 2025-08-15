-- ================================
-- Configuração inicial
-- ================================
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Criando o banco de dados
CREATE DATABASE IF NOT EXISTS `crud_db` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_general_ci;

USE `crud_db`;

-- ================================
-- Tabela: professor
-- ================================
CREATE TABLE `professor` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(90) NOT NULL,
  `cpf` CHAR(11) NOT NULL UNIQUE,
  `email` VARCHAR(50) NOT NULL UNIQUE,
  `senha` VARCHAR(255) NOT NULL,
  `token_recuperacao` VARCHAR(64) DEFAULT NULL,
  `token_expiracao` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ================================
-- Tabela: aluno
-- ================================
CREATE TABLE `aluno` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(90) NOT NULL,
  `serie` VARCHAR(12) NOT NULL,
  `email` VARCHAR(200) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ================================
-- Tabela: livro
-- ================================
CREATE TABLE `livro` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome_livro` VARCHAR(190) NOT NULL,
  `nome_autor` VARCHAR(130) NOT NULL,
  `isbn` VARCHAR(20) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ================================
-- Tabela: anotacoes
-- ================================
CREATE TABLE `anotacoes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_professor` INT NOT NULL,
  `texto` TEXT NOT NULL,
  `data` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_anotacao_professor`
    FOREIGN KEY (`id_professor`) 
    REFERENCES `professor` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ================================
-- Tabela: emprestimo
-- ================================
CREATE TABLE `emprestimo` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_aluno` INT NOT NULL,
  `id_professor` INT NOT NULL,
  `id_livro` INT NOT NULL,
  `data_emprestimo` DATE NOT NULL,
  `data_devolucao` DATE NOT NULL,
  `status` ENUM('pendente','devolvido','atrasado') NOT NULL DEFAULT 'pendente',
  CONSTRAINT `fk_emprestimo_aluno` 
    FOREIGN KEY (`id_aluno`) REFERENCES `aluno` (`id`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_emprestimo_professor` 
    FOREIGN KEY (`id_professor`) REFERENCES `professor` (`id`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_emprestimo_livro` 
    FOREIGN KEY (`id_livro`) REFERENCES `livro` (`id`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ================================
-- Inserindo professor padrão
-- ================================
INSERT INTO `professor` (`nome`, `cpf`, `email`, `senha`) VALUES
('Professor', '00000000000', 'professor@email.com', 
 '$2y$10$9wsKRk73Ak7JUVY88kKfM.fXP1c5t9aMP/o2J3IxJ/AsaVrCEpjZq');

-- ================================
-- Inserindo aluno padrão
-- ================================
INSERT INTO `aluno` (`nome`, `serie`, `email`) VALUES
('Aluno', '1º Ano EM A', 'aluno@gmail.com');

COMMIT;
