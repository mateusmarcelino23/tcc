-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/05/2025 às 18:35
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
  `nome` varchar(90) NOT NULL,
  `serie` varchar(12) NOT NULL,
  `email` varchar(200) NOT NULL
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
  `id_professor` int(11) NOT NULL,
  `texto` text NOT NULL,
  `data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `anotacoes`
--

INSERT INTO `anotacoes` (`id`, `id_professor`, `texto`, `data`) VALUES
(56, 11, 'bom dia lindo dia', '2025-05-29 03:24:07'),
(57, 11, '34543', '2025-05-30 18:35:12');

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
(285, 36, 20, 29, '2025-05-04', '2025-05-13', 1),
(286, 34, 20, 26, '2025-05-05', '2025-05-22', 0),
(287, 77, 20, 29, '2025-05-06', '2025-05-15', 1),
(288, 33, 20, 26, '2025-05-07', '2025-05-16', 0),
(289, 34, 20, 29, '2025-05-08', '2025-05-17', 1),
(290, 35, 20, 26, '2025-05-09', '2025-05-18', 0),
(291, 36, 20, 29, '2025-05-10', '2025-05-19', 0),
(292, 37, 20, 26, '2025-05-11', '2025-05-20', 2),
(293, 77, 20, 29, '2025-05-12', '2025-05-21', 1),
(294, 33, 20, 26, '2025-05-13', '2025-05-22', 1),
(295, 34, 20, 29, '2025-05-14', '2025-05-23', 0),
(296, 35, 20, 26, '2025-05-15', '2025-05-24', 0),
(297, 36, 20, 29, '2025-05-16', '2025-05-25', 0),
(298, 37, 20, 26, '2025-05-17', '2025-05-26', 1),
(299, 77, 20, 29, '2025-05-18', '2025-05-27', 0),
(300, 33, 20, 26, '2025-05-19', '2025-05-28', 1),
(301, 34, 20, 29, '2025-05-20', '2025-05-29', 0),
(302, 35, 20, 26, '2025-05-21', '2025-05-30', 0),
(303, 36, 20, 29, '2025-05-22', '2025-05-31', 0),
(304, 37, 20, 26, '2025-05-23', '2025-06-01', 0),
(305, 34, 20, 29, '2025-05-24', '2025-06-02', 0),
(306, 33, 20, 26, '2025-05-25', '2025-06-03', 0),
(307, 34, 20, 29, '2025-05-26', '2025-06-04', 0),
(308, 35, 20, 26, '2025-05-27', '2025-06-05', 0),
(309, 36, 20, 29, '2025-05-28', '2025-06-06', 0),
(310, 37, 20, 26, '2025-05-29', '2025-06-07', 0),
(311, 34, 20, 26, '2025-05-30', '2025-06-08', 0),
(312, 33, 20, 26, '2025-05-31', '2025-06-09', 0),
(313, 34, 20, 29, '2025-06-01', '2025-06-10', 0),
(314, 35, 20, 26, '2025-06-02', '2025-06-11', 0),
(315, 36, 20, 29, '2025-06-03', '2025-06-12', 0),
(316, 37, 20, 26, '2025-06-04', '2025-06-13', 0),
(317, 77, 20, 29, '2025-06-05', '2025-06-14', 0),
(318, 33, 20, 26, '2025-06-06', '2025-06-15', 0),
(319, 34, 20, 29, '2025-06-07', '2025-06-16', 0),
(320, 35, 20, 26, '2025-06-08', '2025-06-17', 0),
(321, 36, 20, 29, '2025-06-09', '2025-06-18', 0),
(322, 34, 11, 26, '2025-05-24', '2025-06-07', 0),
(323, 33, 20, 90, '2025-05-24', '2025-05-25', 0),
(324, 33, 19, 97, '2025-05-27', '2025-05-31', 0),
(325, 33, 20, 102, '2025-05-28', '2025-05-31', 0),
(326, 33, 11, 104, '2025-05-29', '2025-06-07', 0),
(327, 33, 11, 106, '2025-05-30', '2025-08-15', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `livro`
--

CREATE TABLE `livro` (
  `id` int(11) NOT NULL,
  `nome_livro` varchar(190) NOT NULL,
  `nome_autor` varchar(130) NOT NULL,
  `isbn` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `livro`
--

INSERT INTO `livro` (`id`, `nome_livro`, `nome_autor`, `isbn`) VALUES
(26, 'It: A coisa', 'Stephen King', '9788581051529'),
(29, 'Dom Casmurro', 'Machado de Assis', 'ISBN não disponível'),
(88, 'O Pequeno Príncipe', 'Antoine De  Saint-exupéry', '9781539839897'),
(89, 'História geral da civilização brasileira', 'Sérgio Buarque de Holanda', 'UVA:X001023965'),
(90, 'Harry Potter e a Pedra Filosofal', 'J.K. Rowling', '9781781103685'),
(91, 'Uma Vez Desbravador, Sempre Desbravador', 'Maksym Krupskyi', '9788534534864'),
(92, 'Meio ambiente – E eu com isso?', 'Nurit Bensusan', '9788575966426'),
(94, 'Homem-Aranha: Aranhaverso', 'Dan Slott', '9786525909400'),
(95, 'O Paladar não Retrocede', 'Carlos Ferreirinha', '9788582892282'),
(96, 'Consagrado no Gramado', 'Roberto Assaf', '9786500380491'),
(97, 'Homem-Aranha: História de Vida', 'Chip Zdarsky', '9786555128741'),
(98, 'Espetacular Homem-Aranha: Corrente', 'Jonathan Hickman, Gerry Duggan', '9786559608492'),
(99, 'Ultimate Homem-Aranha', 'Brian Michael Bendis', '9786525901121'),
(100, 'Hotel Magnifique', 'Emily J. Taylor', '9786559814350'),
(101, 'Coleção Histórica Marvel: O Homem-Aranha', 'Stan Lee', '9786555120608'),
(102, 'ONLY YOU', 'Rosa Kane', ''),
(103, 'Ian não está mais aqui', 'Luiz Antonio Aguiar', '9788506080238'),
(104, 'O colecionador de lágrimas', 'Augusto Cury', '8576658089'),
(105, 'Estudar, Eu Tô Ligado!', 'Fernando Targino Da Cunha', 'PKEY:CLDEAU6915'),
(106, 'Às Suas Ordens: Usando o Poder do “Eu Sou”', 'Neville Goddard', '9788822828569');

-- --------------------------------------------------------

--
-- Estrutura para tabela `professor`
--

CREATE TABLE `professor` (
  `id` int(11) NOT NULL,
  `nome` varchar(90) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `token_recuperacao` varchar(64) DEFAULT NULL,
  `token_expiracao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professor`
--

INSERT INTO `professor` (`id`, `nome`, `cpf`, `email`, `senha`, `token_recuperacao`, `token_expiracao`) VALUES
(11, 'João', '00000000000', 'joaozinho@gmail.com', '$2y$10$9wsKRk73Ak7JUVY88kKfM.fXP1c5t9aMP/o2J3IxJ/AsaVrCEpjZq', 'f2b963317e507895ac80d711bff85641', '2025-05-29 14:00:34'),
(19, 'Fernando', '11111111111', 'umavezflamengo@gmail.com', '$2y$10$TIIcdDCgLOD.GUhE3AIc4eQE/0LIdsP/UQBb54zAVruMoh1AW8L.m', NULL, NULL),
(20, 'Gabriel', '22222222222', 'gabrielhistoria@gmail.com', '$2y$10$2r4IOHUmBrhQK6cLTReere6B4jbvqf2O4.ERO75MLL7HTFY3kug2q', NULL, NULL),
(21, 'Mateus', '99999999999', 'mateusmarcelino1023@gmail.com', '$2y$10$omg9HaFXflrenC5kPWNQSu/yqXElxBdIGPsibJ7GYlGZUpZutKpL.', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `professor_reset_senha`
--

CREATE TABLE `professor_reset_senha` (
  `id` int(11) NOT NULL,
  `professor_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expiracao` datetime NOT NULL,
  `usado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professor_reset_senha`
--

INSERT INTO `professor_reset_senha` (`id`, `professor_id`, `token`, `expiracao`, `usado`) VALUES
(1, 11, '848aea743ef53330ca595b0841b0fb3f8827edaa0d6ef804a36fb8cf289e8fae', '2025-05-29 14:33:24', 0),
(2, 11, 'd4c715cbbbb8f44431d0a1e928ac03b550a20b1c5df1033167d396359f0ae3ed', '2025-05-29 14:38:07', 0),
(3, 21, 'd4aca50fe2641f012d5361139033dfc7ba3cb275098d885d9ec3e190fea896e3', '2025-05-29 14:39:37', 0),
(4, 21, '563533a9faaff29bd7460b04718e048c6fd25d81e026c3813ac60617f04f8e22', '2025-05-29 14:39:55', 0),
(5, 21, 'a0a41096c838d22791f9f2885db1451f1a151b0d2249a0117a477e25ed439626', '2025-05-29 14:39:58', 0),
(6, 21, '98efdfa9f5d13aec85905e8517f012192e6d2911380b6f3b474314dc4c9acddd', '2025-05-29 14:40:57', 0),
(7, 21, '60422c8fd603bfa2515316329844c9bce37791ca1600858f3ae5b66260036bcb', '2025-05-29 14:47:52', 0),
(8, 21, '8cdcee8efe70d86bdabb608c0385e02081ad4dff847dccde4195548d6f343d01', '2025-05-29 14:55:04', 0),
(9, 21, '616b44b052dc10db6cbe7e5d3494a86e0da0a82a43d2be2ca36bb34f2d05e47d', '2025-05-29 14:57:10', 0),
(10, 21, '5346907e686c7771c2bb09d8025f7faad6e5b5423f3f571c51ab896500d58644', '2025-05-29 15:34:48', 0);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_professor` (`id_professor`);

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
-- Índices de tabela `professor_reset_senha`
--
ALTER TABLE `professor_reset_senha`
  ADD PRIMARY KEY (`id`),
  ADD KEY `professor_id` (`professor_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aluno`
--
ALTER TABLE `aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de tabela `anotacoes`
--
ALTER TABLE `anotacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=328;

--
-- AUTO_INCREMENT de tabela `livro`
--
ALTER TABLE `livro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT de tabela `professor`
--
ALTER TABLE `professor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `professor_reset_senha`
--
ALTER TABLE `professor_reset_senha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `anotacoes`
--
ALTER TABLE `anotacoes`
  ADD CONSTRAINT `anotacoes_ibfk_1` FOREIGN KEY (`id_professor`) REFERENCES `professor` (`id`);

--
-- Restrições para tabelas `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD CONSTRAINT `fk_id_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `aluno` (`id`),
  ADD CONSTRAINT `fk_id_livro` FOREIGN KEY (`id_livro`) REFERENCES `livro` (`id`),
  ADD CONSTRAINT `fk_id_professor` FOREIGN KEY (`id_professor`) REFERENCES `professor` (`id`);

--
-- Restrições para tabelas `professor_reset_senha`
--
ALTER TABLE `professor_reset_senha`
  ADD CONSTRAINT `professor_reset_senha_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `professor` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
