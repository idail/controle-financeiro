-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04-Out-2023 às 16:26
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `financeiro`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_conta_pagar`
--

CREATE TABLE `tbl_conta_pagar` (
  `id_conta_pagar` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_pagar` date NOT NULL,
  `pago` tinyint(4) NOT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_empresa`
--

CREATE TABLE `tbl_empresa` (
  `id_empresa` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tbl_empresa`
--

INSERT INTO `tbl_empresa` (`id_empresa`, `nome`) VALUES
(1, 'Microsoft'),
(2, 'Apple'),
(3, 'Honda');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tbl_conta_pagar`
--
ALTER TABLE `tbl_conta_pagar`
  ADD PRIMARY KEY (`id_conta_pagar`),
  ADD KEY `chave_estrangeira_conta_pagar_empresa` (`id_empresa`);

--
-- Índices para tabela `tbl_empresa`
--
ALTER TABLE `tbl_empresa`
  ADD PRIMARY KEY (`id_empresa`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbl_conta_pagar`
--
ALTER TABLE `tbl_conta_pagar`
  MODIFY `id_conta_pagar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_empresa`
--
ALTER TABLE `tbl_empresa`
  MODIFY `id_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `tbl_conta_pagar`
--
ALTER TABLE `tbl_conta_pagar`
  ADD CONSTRAINT `chave_estrangeira_conta_pagar_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `tbl_empresa` (`id_empresa`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
