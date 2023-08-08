-- SCRIPT DE DDL - DATA DEFINITION LANGUAGE
-- apaga o banco de dados escolabd, se ele existir.
DROP SCHEMA IF EXISTS pccsampledb;

-- cria um banco de dados chamado escolabd.
CREATE SCHEMA pccsampledb;

-- cria uma tabela chamada usuarios com os campos: 
-- id, email, password, nome, status, perfil e data_cadastro.
CREATE TABLE pccsampledb.usuarios (
    id              INTEGER PRIMARY KEY AUTO_INCREMENT,
    email           VARCHAR(255) NOT NULL UNIQUE,
    password        VARCHAR(60) NOT NULL,
    nome            VARCHAR(40) NOT NULL,
    perfil          CHAR(3) NOT NULL DEFAULT 'USU' COMMENT 'ADM=Administrador\nGER=Gerente\nEDI=Editor\nUSU=Usuario', 
    status          BOOLEAN NOT NULL DEFAULT TRUE,  
    data_cadastro   DATETIME NOT NULL DEFAULT NOW()
);

-- cria uma tabela chamada categorias com os campos: 
-- id, nome e status.
CREATE TABLE pccsampledb.categorias (
    id              INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome            VARCHAR(40) NOT NULL,
    status          BOOLEAN NOT NULL DEFAULT TRUE 
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
-- inserir dados na tabela de usuario. 
INSERT INTO pccsampledb.usuarios (id, email, password, nome, perfil, status) 
    VALUES (1, 'admin@email.com', md5('123'), 'Admin', 'ADM', 1), 
    (2, 'gerente@email.com', md5('1234'), 'Gerente', 'GER', 0), 
    (3, 'editor@email.com', md5('12345'), 'Editor', 'EDI', 0), 
    (4, 'usuario@email.com', md5('123456'), 'Usuário', 'USU', 1); 


-- inserir dados na tabela de usuario. 
INSERT INTO pccsampledb.categorias (id, nome, status)
    VALUES (1, 'Banco de dados', true),
            (2, 'Redes', true),
            (3, 'Desenvolvimento', true),
            (4, 'Jogos', true),
            (5, 'HTML5 & CSS3', true);

-- inserir dados na tabela de artigos.
INSERT INTO pccsampledb.artigos (titulo, status, data_publicacao, categoria_id, usuario_id, texto, imagem, imagem_externa) 
    VALUES('O poder do PHP', 1, '2023-05-01 09:35:00', 3, 3, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', true),
    ('SQL sem mistério', 1, '2023-05-02 10:40:00', 1, 3, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://plus.unsplash.com/premium_photo-1671017840486-fe1b90916049?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=678&q=80', true),
    ('Aprenda JavaScript', 1, '2023-05-22 11:00:00', 3, 3, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://images.unsplash.com/photo-1484807352052-23338990c6c6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', true),
    ('Você tem medo de redes', 1, '2023-05-22 09:00:00', 2, 4, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://images.unsplash.com/photo-1507297230445-ff678f10b524?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', true),
    ('Tour por Minecraft', 1, '2023-05-23 10:00:00', 4, 4, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://images.unsplash.com/photo-1522346513757-54c552451fdc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1167&q=80', true),
    ('Jogue com PS5', 1, '2023-05-23 10:20:00', 4, 4, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=647&q=80', true),
    ('PacMan', 1, '2023-05-23 10:35:00', 4, 4, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://images.unsplash.com/photo-1579373903781-fd5c0c30c4cd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80', true),
    ('XBox para PS', 1, '2023-05-23 17:22:00', 4, 4, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", 'https://images.unsplash.com/photo-1600861194942-f883de0dfe96?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1169&q=80', true),
    ('Sites em HTML 5 e CSS 3', 1, '2023-05-23 18:00:00', 5, 3, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search", '', false);
