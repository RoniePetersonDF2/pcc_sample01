-- SCRIPT DE DDL - DATA DEFINITION LANGUAGE
-- apaga o banco de dados escolabd, se ele existir.
DROP SCHEMA IF EXISTS pccsampledb;

-- cria um banco de dados chamado escolabd.
CREATE SCHEMA pccsampledb;

-- cria uma tabela chamada perfil com os campos: 
-- id, nome.
CREATE TABLE pccsampledb.perfis (
    id              INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome            VARCHAR(60) NOT NULL,
    sigla           CHAR(3) NOT NULL
);

-- cria uma tabela chamada usuarios com os campos: 
-- id, email, password, nome, status, perfil e data_cadastro.
CREATE TABLE pccsampledb.usuarios (
    id              INTEGER PRIMARY KEY AUTO_INCREMENT,
    email           VARCHAR(255) NOT NULL UNIQUE,
    password        VARCHAR(60) NOT NULL,
    nome            VARCHAR(40) NOT NULL,
    status          BOOLEAN NOT NULL DEFAULT TRUE,  
    data_cadastro   DATETIME NOT NULL DEFAULT NOW(),
    perfil_id       INTEGER,
    CONSTRAINT fk_perfil FOREIGN KEY (perfil_id) 
                         REFERENCES perfis(id)
);

-- cria uma tabela chamada categorias com os campos: 
-- id, nome e status.
CREATE TABLE pccsampledb.categorias (
    id              INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome            VARCHAR(40) NOT NULL,
    status          BOOLEAN NOT NULL DEFAULT TRUE,
    tipo            CHAR(3) NOT NULL DEFAULT 'ART' COMMENT 'ART=Artigo\nCUR=Curso' 
);

-- cria uma tabela chamada artigos com os campos: 
-- id, titulo, texto, status, data_publicacao, categoria e usuario.
CREATE TABLE pccsampledb.artigos (
    id                  INTEGER PRIMARY KEY AUTO_INCREMENT,
    titulo              VARCHAR(100) NOT NULL,
    texto               TEXT,
    status              BOOLEAN NOT NULL DEFAULT FALSE,
    data_publicacao     DATETIME DEFAULT NOW(),
    imagem              VARCHAR(255) NULL,
    imagem_externa      BOOLEAN NOT NULL DEFAULT TRUE,
    categoria_id        INTEGER NOT NULL,
    usuario_id          INTEGER NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES pccsampledb.categorias(id),
    FOREIGN KEY (usuario_id) REFERENCES pccsampledb.usuarios(id)
);

-- SCRIPT DE DML - DATA MANIPULATION LANGUAGE
-- inserir dados na tabela de perfil. 
INSERT INTO pccsampledb.perfis (id, nome, sigla) 
VALUES
(1, 'Administrador', 'ADM'),
(2, 'Gerente', 'GER'),
(3, 'Editor', 'EDI'),
(4, 'Usuário', 'USU');
-- SCRIPT DE DML - DATA MANIPULATION LANGUAGE
-- inserir dados na tabela de usuario. 
INSERT INTO pccsampledb.usuarios (id, email, password, nome, perfil_id, status) 
VALUES 
(101, 'admin@email.com', md5('123'), 'Admin', 1, 1), 
(102, 'gerente@email.com', md5('1234'), 'Gerente', 2, 0), 
(103, 'editor@email.com', md5('12345'), 'Editor', 3, 0), 
(104, 'usuario@email.com', md5('123456'), 'Usuário', 4, 1); 


-- inserir dados na tabela de usuario. 
INSERT INTO pccsampledb.categorias (id, nome, status, tipo)
VALUES 
(101, 'Banco de dados', true, 'ART'),
(102, 'Redes', true, 'ART'),
(103, 'Desenvolvimento', true, 'ART'),
(104, 'Jogos', true, 'ART'),
(105, 'HTML5 & CSS3', true, 'CUR');

-- inserir dados na tabela de artigos.
INSERT INTO pccsampledb.artigos (id, titulo, status, data_publicacao, categoria_id, usuario_id, texto, imagem, imagem_externa) 
VALUES
(1001, 'O poder do PHP', 1, '2023-05-01 09:35:00', 103, 103, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', true),
(1002, 'SQL sem mistério', 1, '2023-05-02 10:40:00', 101, 103, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://plus.unsplash.com/premium_photo-1671017840486-fe1b90916049?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=678&q=80', true),
(1003, 'Aprenda JavaScript', 1, '2023-05-22 11:00:00', 103, 103, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://images.unsplash.com/photo-1484807352052-23338990c6c6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', true),
(1004, 'Você tem medo de redes', 1, '2023-05-22 09:00:00', 102, 104, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://images.unsplash.com/photo-1507297230445-ff678f10b524?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', true),
(1005, 'Tour por Minecraft', 1, '2023-05-23 10:00:00', 104, 104, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://images.unsplash.com/photo-1522346513757-54c552451fdc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1167&q=80', true),
(1006, 'Jogue com PS5', 1, '2023-05-23 10:20:00', 104, 104, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=647&q=80', true),
(1007, 'PacMan', 1, '2023-05-23 10:35:00', 104, 104, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://images.unsplash.com/photo-1579373903781-fd5c0c30c4cd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80', true),
(1008, 'XBox para PS', 1, '2023-05-23 17:22:00', 104, 104, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://images.unsplash.com/photo-1600861194942-f883de0dfe96?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1169&q=80', true),
(1009, 'Sites em HTML 5 e CSS 3', 1, '2023-05-23 18:00:00', 105, 103, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", '', false);
