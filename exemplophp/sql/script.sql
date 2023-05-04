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
    nome            VARCHAR(40) NOT NULL UNIQUE,
    perfil          CHAR(3) NOT NULL DEFAULT 'USU' COMMENT 'ADM=Administrador\nGER=Gerente\nEDI=Editor\nUSU=Usuario', 
    status          BOOLEAN NOT NULL DEFAULT TRUE,  
    data_cadastro   DATETIME NOT NULL DEFAULT NOW()
);

-- SCRIPT DE DML - DATA MANIPULATION LANGUAGE
-- inserir dados na tabela de usuario. 
INSERT INTO pccsampledb.usuarios (email, password, nome, perfil, status) 
    VALUES ('admin@email.com', md5('123'), 'Admin', 'ADM', 1), 
    ('gerente@email.com', md5('1234'), 'Gerente', 'GER', 0), 
    ('editor@email.com', md5('12345'), 'Editor', 'EDI', 0), 
    ('usuario@email.com', md5('123456'), 'Usuário', 'USU', 1); 
