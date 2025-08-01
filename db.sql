-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 01/08/2025 às 12:52
-- Versão do servidor: 8.0.42-0ubuntu0.24.04.2
-- Versão do PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `crud_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `aluno`
--

CREATE TABLE `aluno` (
  `id` int NOT NULL,
  `nome` varchar(90) COLLATE utf8mb4_general_ci NOT NULL,
  `serie` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `anotacoes`
--

CREATE TABLE `anotacoes` (
  `id` int NOT NULL,
  `id_professor` int NOT NULL,
  `texto` text COLLATE utf8mb4_general_ci NOT NULL,
  `data` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `emprestimo`
--

CREATE TABLE `emprestimo` (
  `id` int NOT NULL,
  `id_aluno` int NOT NULL,
  `id_professor` int NOT NULL,
  `id_livro` int NOT NULL,
  `data_emprestimo` date NOT NULL,
  `data_devolucao` date NOT NULL,
  `status` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `livro`
--

CREATE TABLE `livro` (
  `id` int NOT NULL,
  `nome_livro` varchar(190) COLLATE utf8mb4_general_ci NOT NULL,
  `nome_autor` varchar(130) COLLATE utf8mb4_general_ci NOT NULL,
  `isbn` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `professor`
--

CREATE TABLE `professor` (
  `id` int NOT NULL,
  `nome` varchar(90) COLLATE utf8mb4_general_ci NOT NULL,
  `cpf` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token_recuperacao` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `token_expiracao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professor`
--

INSERT INTO `professor` (`id`, `nome`, `cpf`, `email`, `senha`, `token_recuperacao`, `token_expiracao`) VALUES
(1, 'Professor', '00000000000', 'professor@email.com', '$2y$10$9wsKRk73Ak7JUVY88kKfM.fXP1c5t9aMP/o2J3IxJ/AsaVrCEpjZq', NULL, NULL);
COMMIT;

-- Ajustando as tabelas com chaves primárias, auto incremento e relacionamentos

-- Tabela aluno
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `aluno`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

-- Tabela professor
ALTER TABLE `professor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`);
ALTER TABLE `professor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

-- Tabela livro
ALTER TABLE `livro`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `livro`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

-- Tabela anotacoes
ALTER TABLE `anotacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_anotacoes_professor` (`id_professor`);
ALTER TABLE `anotacoes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

-- Tabela emprestimo
ALTER TABLE `emprestimo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_emprestimo_aluno` (`id_aluno`),
  ADD KEY `fk_emprestimo_professor` (`id_professor`),
  ADD KEY `fk_emprestimo_livro` (`id_livro`);
ALTER TABLE `emprestimo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

-- Criando os relacionamentos (FOREIGN KEYS)

ALTER TABLE `anotacoes`
  ADD CONSTRAINT `fk_anotacoes_professor`
    FOREIGN KEY (`id_professor`) REFERENCES `professor` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `emprestimo`
  ADD CONSTRAINT `fk_emprestimo_aluno`
    FOREIGN KEY (`id_aluno`) REFERENCES `aluno` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_emprestimo_professor`
    FOREIGN KEY (`id_professor`) REFERENCES `professor` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_emprestimo_livro`
    FOREIGN KEY (`id_livro`) REFERENCES `livro` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;