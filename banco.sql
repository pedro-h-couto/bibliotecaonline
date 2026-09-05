-- Remove o schema se existir
DROP SCHEMA IF EXISTS `biblioteca`;

-- Cria o schema novamente
CREATE SCHEMA IF NOT EXISTS `biblioteca` DEFAULT CHARACTER SET utf8;
USE `biblioteca`;

-- Remove tabelas (ordem importa por causa das FK)
DROP TABLE IF EXISTS `Emprestimo`;
DROP TABLE IF EXISTS `Livro`;
DROP TABLE IF EXISTS `Leitor`;
DROP TABLE IF EXISTS `Categoria`;
DROP TABLE IF EXISTS `Autor`;
DROP TABLE IF EXISTS `Usuario`;

-- =====================================
-- TABELA USUARIO (NOVO)
-- =====================================

CREATE TABLE IF NOT EXISTS `Usuario` (
    `idUsuario` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `senha` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`idUsuario`)
) ENGINE=InnoDB;

-- =====================================
-- TABELA AUTOR
-- =====================================

CREATE TABLE IF NOT EXISTS `Autor` (
    `idAutor` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(100) NOT NULL,
    `nacionalidade` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`idAutor`)
) ENGINE=InnoDB;

-- =====================================
-- TABELA CATEGORIA
-- =====================================

CREATE TABLE IF NOT EXISTS `Categoria` (
    `idCategoria` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nomeCategoria` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`idCategoria`)
) ENGINE=InnoDB;

-- =====================================
-- TABELA LIVRO
-- =====================================

CREATE TABLE IF NOT EXISTS `Livro` (
    `idLivro` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `titulo` VARCHAR(150) NOT NULL,
    `anoPublicacao` INT NOT NULL,
    `quantidade` INT NOT NULL,
    `Autor_idAutor` INT UNSIGNED NOT NULL,
    `Categoria_idCategoria` INT UNSIGNED NOT NULL,

    PRIMARY KEY (`idLivro`),

    CONSTRAINT `fk_Livro_Autor`
        FOREIGN KEY (`Autor_idAutor`)
        REFERENCES `Autor` (`idAutor`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,

    CONSTRAINT `fk_Livro_Categoria`
        FOREIGN KEY (`Categoria_idCategoria`)
        REFERENCES `Categoria` (`idCategoria`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
) ENGINE=InnoDB;

-- =====================================
-- TABELA LEITOR
-- =====================================

CREATE TABLE IF NOT EXISTS `Leitor` (
    `idLeitor` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(100) NOT NULL,
    `telefone` VARCHAR(20),
    `email` VARCHAR(100),

    PRIMARY KEY (`idLeitor`)
) ENGINE=InnoDB;

-- =====================================
-- TABELA EMPRESTIMO
-- =====================================

CREATE TABLE IF NOT EXISTS `Emprestimo` (
    `idEmprestimo` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `Livro_idLivro` INT UNSIGNED NOT NULL,
    `Leitor_idLeitor` INT UNSIGNED NOT NULL,
    `dataEmprestimo` DATE NOT NULL,
    `dataDevolucao` DATE,
    `status` VARCHAR(20) NOT NULL DEFAULT 'ativo',

    PRIMARY KEY (`idEmprestimo`),

    CONSTRAINT `fk_Emprestimo_Livro`
        FOREIGN KEY (`Livro_idLivro`)
        REFERENCES `Livro` (`idLivro`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,

    CONSTRAINT `fk_Emprestimo_Leitor`
        FOREIGN KEY (`Leitor_idLeitor`)
        REFERENCES `Leitor` (`idLeitor`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
) ENGINE=InnoDB;

-- =====================================
-- DADOS INICIAIS
-- =====================================

INSERT INTO Usuario (nome, email, senha) VALUES
('Administrador', 'admin@biblioteca.com', '$2y$10$HfPjSrtHJ8HH0o1V4WvuBOC8.dp1VsgWhnfYQVkIVhXHkieY6O5cu'),
('Bibliotecario', 'bib@biblioteca.com', '$2y$10$HfPjSrtHJ8HH0o1V4WvuBOC8.dp1VsgWhnfYQVkIVhXHkieY6O5cu');

INSERT INTO Autor (nome, nacionalidade) VALUES
('Machado de Assis', 'Brasileira'),
('Monteiro Lobato', 'Brasileira'),
('J. K. Rowling', 'Britanica');

INSERT INTO Categoria (nomeCategoria) VALUES
('Romance'),
('Fantasia'),
('Infantil'),
('Aventura');

INSERT INTO Livro
(titulo, anoPublicacao, quantidade, Autor_idAutor, Categoria_idCategoria)
VALUES
('Dom Casmurro', 1899, 5, 1, 1),
('Memorias Postumas de Bras Cubas', 1881, 3, 1, 1),
('Harry Potter e a Pedra Filosofal', 1997, 7, 3, 2),
('Harry Potter e a Camara Secreta', 1998, 6, 3, 2),
('Sitio do Picapau Amarelo', 1920, 4, 2, 3);

INSERT INTO Leitor (nome, telefone, email) VALUES
('Pedro Henrique', '12999999999', 'pedro@gmail.com'),
('Maria Silva', '12988888888', 'maria@gmail.com'),
('Joao Souza', '12977777777', 'joao@gmail.com');

INSERT INTO Emprestimo
(Livro_idLivro, Leitor_idLeitor, dataEmprestimo, dataDevolucao, status)
VALUES
(1, 1, '2026-05-01', '2026-05-08', 'devolvido'),
(3, 2, '2026-05-03', '2026-05-10', 'ativo'),
(5, 3, '2026-05-05', NULL, 'ativo');
