-- Geração de Modelo físico
-- Sql ANSI 2003 - brModelo.



CREATE TABLE Adm (
email varchar(60) PRIMARY KEY,
nome varchar(60) NOT NULL,
senha varchar(20) NOT NULL
);

CREATE TABLE Favoritos (
idFavorito bigint PRIMARY KEY AUTO_INCREMENT,
idTCC bigint NOT NULL
);

CREATE TABLE favorito_visitante (
email varchar(60) NOT NULL,
idFavorito bigint NOT NULL,
FOREIGN KEY(idFavorito) REFERENCES Favoritos (idFavorito)
);

CREATE TABLE Visitante (
email varchar(60) PRIMARY KEY,
nome varchar(60) NOT NULL,
senha varchar(20) NOT NULL
);

CREATE TABLE favorito_aluno (
matricula bigint NOT NULL,
idFavorito bigint NOT NULL,
FOREIGN KEY(idFavorito) REFERENCES Favoritos (idFavorito)
);

CREATE TABLE favorito_professor (
matricula varchar(10) NOT NULL,
idFavorito bigint NOT NULL,
FOREIGN KEY(idFavorito) REFERENCES Favoritos (idFavorito)
);

CREATE TABLE Aluno (
matricula bigint PRIMARY KEY,
nome varchar(60) NOT NULL,
rg varchar(15) NOT NULL,
cpf varchar(15) NOT NULL,
telefone varchar(15) NOT NULL,
email varchar(60) NOT NULL UNIQUE,
senha varchar(20) NOT NULL,
idEndereco bigint NOT NULL
);

CREATE TABLE nomeAlternativo (
idCategoria bigint PRIMARY KEY NOT NULL,
nomeAlternativo varchar(60) NOT NULL
);

CREATE TABLE Categoria (
idCategoria bigint PRIMARY KEY AUTO_INCREMENT,
nome varchar(60) NOT NULL,
eSub boolean NOT NULL,
categoriaPrincipal bigint NULL
);

CREATE TABLE Endereco (
idEndereco bigint PRIMARY KEY AUTO_INCREMENT,
bairro varchar(20) NOT NULL,
logradouro varchar(60) NOT NULL,
cep varchar(15) NOT NULL,
uf varchar(20) NOT NULL,
cidade varchar(20) NOT NULL,
complemento varchar(60)
);

CREATE TABLE Professor (
matricula varchar(10) PRIMARY KEY NOT NULL,
nome varchar(60) UNIQUE NOT NULL,
rg varchar(15) NOT NULL,
cpf varchar(15) NOT NULL,
telefone varchar(15) NOT NULL,
email varchar(60) UNIQUE NOT NULL,
senha varchar(20) NOT NULL,
idEndereco bigint NOT NULL,
FOREIGN KEY(idEndereco) REFERENCES Endereco (idEndereco)
);

CREATE TABLE indicacao (
idIndicacao bigint PRIMARY KEY AUTO_INCREMENT,
idCurso bigint NOT NULL,
idInstituicao bigint NOT NULL,
matricula varchar(10) NOT NULL,
idTCC bigint NOT NULL,
FOREIGN KEY(matricula) REFERENCES Professor (matricula)
);

CREATE TABLE Bibliotecario (
email varchar(60) PRIMARY KEY NOT NULL,
nome varchar(60) NOT NULL,
senha varchar(20) NOT NULL
);

CREATE TABLE Orientador (
matricula varchar(10) NOT NULL,
idTCC bigint NOT NULL,
FOREIGN KEY(matricula) REFERENCES Professor (matricula)
);

CREATE TABLE TCC (
idTCC bigint PRIMARY KEY AUTO_INCREMENT,
titulo varchar(60) NOT NULL,
descricao varchar(600) NOT NULL,
localPDF varchar(100) NOT NULL,
idCurso bigint NOT NULL,
idCampus bigint NOT NULL,
matricula bigint NOT NULL,
FOREIGN KEY(matricula) REFERENCES Aluno (matricula)
);

CREATE TABLE Categorias (
idCategoria bigint NOT NULL,
idTCC bigint NOT NULL,
FOREIGN KEY(idCategoria) REFERENCES Categoria (idCategoria),
FOREIGN KEY(idTCC) REFERENCES TCC (idTCC)
);

CREATE TABLE Curso (
idCurso bigint PRIMARY KEY AUTO_INCREMENT,
nome varchar(60) NOT NULL,
ensino bigint NOT NULL
);

CREATE TABLE Campus_Curso (
idCampus bigint NOT NULL,
idCurso bigint NOT NULL,
FOREIGN KEY(idCurso) REFERENCES Curso (idCurso)
);

CREATE TABLE Campus (
idCampus bigint PRIMARY KEY AUTO_INCREMENT,
nome varchar(60) NOT NULL
);

ALTER TABLE Favoritos ADD FOREIGN KEY(idTCC) REFERENCES TCC (idTCC);
ALTER TABLE favorito_visitante ADD FOREIGN KEY(email) REFERENCES Visitante (email);
ALTER TABLE favorito_aluno ADD FOREIGN KEY(matricula) REFERENCES Aluno (matricula);
ALTER TABLE favorito_professor ADD FOREIGN KEY(matricula) REFERENCES Professor (matricula);
ALTER TABLE Aluno ADD FOREIGN KEY(idEndereco) REFERENCES Endereco (idEndereco);
ALTER TABLE nomeAlternativo ADD FOREIGN KEY(idCategoria) REFERENCES Categoria (idCategoria);
ALTER TABLE Categoria ADD FOREIGN KEY(categoriaPrincipal) REFERENCES Categoria (idCategoria);
ALTER TABLE indicacao ADD FOREIGN KEY(idCurso) REFERENCES Curso (idCurso);
ALTER TABLE indicacao ADD FOREIGN KEY(idInstituicao) REFERENCES Campus (idCampus);
ALTER TABLE indicacao ADD FOREIGN KEY(idTCC) REFERENCES TCC (idTCC);
ALTER TABLE Orientador ADD FOREIGN KEY(idTCC) REFERENCES TCC (idTCC);
ALTER TABLE TCC ADD FOREIGN KEY(idCurso) REFERENCES Curso (idCurso);
ALTER TABLE TCC ADD FOREIGN KEY(idCampus) REFERENCES Campus (idCampus);
ALTER TABLE Campus_Curso ADD FOREIGN KEY(idCampus) REFERENCES Campus (idCampus);
