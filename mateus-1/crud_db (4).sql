-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/05/2025 às 12:37
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

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
  `id` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `serie` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `aluno`
--

INSERT INTO `aluno` (`id`, `nome`, `serie`, `email`) VALUES
(33, 'Mateus Marcelino', '3º Ano EM D', 'mateusmarcelino1023@gmail.com'),
(34, 'Gunnar', '3º Ano EM D', 'gunnarmaravilhoso@gmail.com'),
(35, 'João', '2º Ano EM A', 'joaozin@gmail.com'),
(36, 'Pedro', '8º Ano E', 'pedro@gmail.com'),
(37, 'Ana Beatriz Lima', '1º Ano EM A', '00ooo@mm.com'),
(77, 'flamengo', '2º Ano EM E', 'sfg4wetgesag@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `anotacoes`
--

CREATE TABLE `anotacoes` (
  `id` int(11) NOT NULL,
  `professor` varchar(100) DEFAULT NULL,
  `texto` text NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `anotacoes`
--

INSERT INTO `anotacoes` (`id`, `professor`, `texto`, `data`) VALUES
(1, NULL, 'primeira do dia - teste', '2025-05-15 14:10:16'),
(2, NULL, 'eu li primeiro', '2025-05-15 14:11:25'),
(3, NULL, 'eu li primeiro', '2025-05-15 14:11:29'),
(4, NULL, 'eu pooooosso cramaa-ar', '2025-05-15 14:39:30'),
(5, NULL, 'my name is thanos', '2025-05-20 13:19:09'),
(6, NULL, 'my name is thanos', '2025-05-20 13:19:15');

-- --------------------------------------------------------

--
-- Estrutura para tabela `emprestimo`
--

CREATE TABLE `emprestimo` (
  `id` int(11) NOT NULL,
  `id_aluno` int(11) NOT NULL,
  `id_professor` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `data_emprestimo` date NOT NULL,
  `data_devolucao` date NOT NULL,
  `status` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `emprestimo`
--

INSERT INTO `emprestimo` (`id`, `id_aluno`, `id_professor`, `id_livro`, `data_emprestimo`, `data_devolucao`, `status`) VALUES
(281, 33, 20, 26, '2025-05-20', '2025-06-07', 0),
(282, 33, 20, 26, '2025-05-01', '2025-05-10', 0),
(283, 34, 20, 29, '2025-05-02', '2025-05-11', 0),
(284, 35, 20, 26, '2025-05-03', '2025-05-12', 0),
(285, 36, 20, 29, '2025-05-04', '2025-05-13', 0),
(286, 34, 20, 26, '2025-05-05', '2025-05-22', 0),
(287, 77, 20, 29, '2025-05-06', '2025-05-15', 0),
(288, 33, 20, 26, '2025-05-07', '2025-05-16', 0),
(289, 34, 20, 29, '2025-05-08', '2025-05-17', 0),
(290, 35, 20, 26, '2025-05-09', '2025-05-18', 0),
(291, 36, 20, 29, '2025-05-10', '2025-05-19', 0),
(292, 37, 20, 26, '2025-05-11', '2025-05-20', 0),
(293, 77, 20, 29, '2025-05-12', '2025-05-21', 0),
(294, 33, 20, 26, '2025-05-13', '2025-05-22', 0),
(295, 34, 20, 29, '2025-05-14', '2025-05-23', 0),
(296, 35, 20, 26, '2025-05-15', '2025-05-24', 0),
(297, 36, 20, 29, '2025-05-16', '2025-05-25', 0),
(298, 37, 20, 26, '2025-05-17', '2025-05-26', 0),
(299, 77, 20, 29, '2025-05-18', '2025-05-27', 0),
(300, 33, 20, 26, '2025-05-19', '2025-05-28', 0),
(301, 34, 20, 29, '2025-05-20', '2025-05-29', 0),
(302, 35, 20, 26, '2025-05-21', '2025-05-30', 0),
(303, 36, 20, 29, '2025-05-22', '2025-05-31', 0),
(304, 37, 20, 26, '2025-05-23', '2025-06-01', 0),
(305, 77, 20, 29, '2025-05-24', '2025-06-02', 0),
(306, 33, 20, 26, '2025-05-25', '2025-06-03', 0),
(307, 34, 20, 29, '2025-05-26', '2025-06-04', 0),
(308, 35, 20, 26, '2025-05-27', '2025-06-05', 0),
(309, 36, 20, 29, '2025-05-28', '2025-06-06', 0),
(310, 37, 20, 26, '2025-05-29', '2025-06-07', 0),
(311, 77, 20, 29, '2025-05-30', '2025-06-08', 0),
(312, 33, 20, 26, '2025-05-31', '2025-06-09', 0),
(313, 34, 20, 29, '2025-06-01', '2025-06-10', 0),
(314, 35, 20, 26, '2025-06-02', '2025-06-11', 0),
(315, 36, 20, 29, '2025-06-03', '2025-06-12', 0),
(316, 37, 20, 26, '2025-06-04', '2025-06-13', 0),
(317, 77, 20, 29, '2025-06-05', '2025-06-14', 0),
(318, 33, 20, 26, '2025-06-06', '2025-06-15', 0),
(319, 34, 20, 29, '2025-06-07', '2025-06-16', 0),
(320, 35, 20, 26, '2025-06-08', '2025-06-17', 0),
(321, 36, 20, 29, '2025-06-09', '2025-06-18', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `livro`
--

CREATE TABLE `livro` (
  `id` int(11) NOT NULL,
  `nome_livro` varchar(40) NOT NULL,
  `nome_autor` varchar(40) NOT NULL,
  `isbn` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `livro`
--

INSERT INTO `livro` (`id`, `nome_livro`, `nome_autor`, `isbn`) VALUES
(26, 'It: A coisa', 'Stephen King', '9788581051529'),
(29, 'Dom Casmurro', 'Machado de Assis', 'ISBN não disponível'),
(88, 'O Pequeno Príncipe', 'Antoine De  Saint-exupéry', '9781539839897');

-- --------------------------------------------------------

--
-- Estrutura para tabela `professor`
--

CREATE TABLE `professor` (
  `id` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professor`
--

INSERT INTO `professor` (`id`, `nome`, `cpf`, `email`, `senha`) VALUES
(11, 'João', '00000000000', 'joaozinho@gmail.com', '$2y$10$9wsKRk73Ak7JUVY88kKfM.fXP1c5t9aMP/o2J3IxJ/AsaVrCEpjZq'),
(19, 'Fernando', '11111111111', 'umavezflamengo@gmail.com', '$2y$10$TIIcdDCgLOD.GUhE3AIc4eQE/0LIdsP/UQBb54zAVruMoh1AW8L.m'),
(20, 'Gabriel', '22222222222', 'gabrielhistoria@gmail.com', '$2y$10$2r4IOHUmBrhQK6cLTReere6B4jbvqf2O4.ERO75MLL7HTFY3kug2q');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `anotacoes`
--
ALTER TABLE `anotacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_aluno` (`id_aluno`),
  ADD KEY `fk_id_professor` (`id_professor`),
  ADD KEY `fk_id_livro` (`id_livro`);

--
-- Índices de tabela `livro`
--
ALTER TABLE `livro`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `professor`
--
ALTER TABLE `professor`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aluno`
--
ALTER TABLE `aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT de tabela `anotacoes`
--
ALTER TABLE `anotacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=322;

--
-- AUTO_INCREMENT de tabela `livro`
--
ALTER TABLE `livro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de tabela `professor`
--
ALTER TABLE `professor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD CONSTRAINT `fk_id_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `aluno` (`id`),
  ADD CONSTRAINT `fk_id_livro` FOREIGN KEY (`id_livro`) REFERENCES `livro` (`id`),
  ADD CONSTRAINT `fk_id_professor` FOREIGN KEY (`id_professor`) REFERENCES `professor` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
