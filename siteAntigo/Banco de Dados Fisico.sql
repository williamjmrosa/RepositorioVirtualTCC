-- Geração de Modelo físico
-- Sql ANSI 2003 - brModelo.



CREATE TABLE adm (
email varchar(60) PRIMARY KEY NOT NULL,
nome varchar(60) NOT NULL,
senha varchar(32) NOT NULL
);

CREATE TABLE favoritos (
idFavorito bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
idTCC bigint NOT NULL
);

CREATE TABLE favorito_visitante (
email varchar(60) NOT NULL,
idFavorito bigint NOT NULL,
FOREIGN KEY(idFavorito) REFERENCES Favoritos (idFavorito)
);

CREATE TABLE visitante (
email varchar(60) PRIMARY KEY NOT NULL,
nome varchar(60) NOT NULL,
senha varchar(32) NOT NULL
);

CREATE TABLE favorito_aluno (
matricula bigint NOT NULL,
idFavorito bigint NOT NULL,
FOREIGN KEY(idFavorito) REFERENCES Favoritos (idFavorito)
);

CREATE TABLE favorito_professor (
matricula bigint NOT NULL,
idFavorito bigint NOT NULL,
FOREIGN KEY(idFavorito) REFERENCES Favoritos (idFavorito)
);

CREATE TABLE nomealternativo (
idNomeAlternativo bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
idCategoria bigint NOT NULL,
nomeAlternativo varchar(60) NOT NULL
);

CREATE TABLE indicacao (
idIndicacao bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
idCurso bigint NOT NULL,
idInstituicao bigint NOT NULL,
matricula bigint NOT NULL,
idTCC bigint NOT NULL
);

CREATE TABLE tcc (
idTCC bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
titulo varchar(400) NOT NULL,
descricao varchar(600) NOT NULL,
localPDF varchar(150) NOT NULL,
idCurso bigint NOT NULL,
idCampus bigint NOT NULL,
matricula bigint NOT NULL
);

CREATE TABLE curso (
idCurso bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome varchar(60) NOT NULL,
ensino bigint NOT NULL,
ativo int NULL
);

CREATE TABLE campus_curso (
idCampus bigint NOT NULL,
idCurso bigint NOT NULL,
FOREIGN KEY(idCurso) REFERENCES Curso (idCurso)
);

CREATE TABLE campus (
idCampus bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome varchar(60) NOT NULL,
ativo int NULL
);

CREATE TABLE bibliotecario (
email varchar(60) PRIMARY KEY NOT NULL,
nome varchar(60) NOT NULL,
senha varchar(32) NOT NULL
);

CREATE TABLE categoria (
idCategoria bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome varchar(60) NOT NULL,
eSub boolean NOT NULL,
categoriaPrincipal bigint NULL
);

CREATE TABLE categorias (
idCategoria bigint NOT NULL,
idTCC bigint NOT NULL,
FOREIGN KEY(idCategoria) REFERENCES Categoria (idCategoria),
FOREIGN KEY(idTCC) REFERENCES TCC (idTCC)
);

CREATE TABLE aluno (
matricula bigint PRIMARY KEY NOT NULL,
nome varchar(60) NOT NULL,
rg varchar(15) NOT NULL,
cpf varchar(15) NOT NULL,
telefone varchar(15) NOT NULL,
email varchar(60) NOT NULL,
senha varchar(32) NOT NULL,
ativo int NULL,
idEndereco bigint NOT NULL,
campus bigint NOT NULL,
curso bigint NOT NULL,
FOREIGN KEY(campus) REFERENCES Campus (idCampus),
FOREIGN KEY(curso) REFERENCES Curso (idCurso)
);

CREATE TABLE professor (
matricula bigint PRIMARY KEY NOT NULL,
nome varchar(60) NOT NULL,
rg varchar(15) NOT NULL,
cpf varchar(15) NOT NULL,
telefone varchar(15) NOT NULL,
email varchar(60) NOT NULL,
senha varchar(32) NOT NULL,
ativo int NULL,
idEndereco bigint NOT NULL
);

CREATE TABLE endereco (
idEndereco bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
bairro varchar(60) NOT NULL,
logradouro varchar(60) NOT NULL,
cep varchar(15) NOT NULL,
uf varchar(2) NOT NULL,
cidade varchar(60) NOT NULL,
complemento varchar(60) NOT NULL
);

CREATE TABLE orientador (
matricula bigint NOT NULL,
idTCC bigint NOT NULL,
FOREIGN KEY(matricula) REFERENCES Professor (matricula),
FOREIGN KEY(idTCC) REFERENCES TCC (idTCC)
);

CREATE TABLE indica_para_aluno (
idIndicaAluno bigint PRIMARY KEY AUTO_INCREMENT,
idIndicacao bigint,
matricula bigint,
FOREIGN KEY(idIndicacao) REFERENCES indicacao (idIndicacao),
FOREIGN KEY(matricula) REFERENCES Aluno (matricula)
);

ALTER TABLE favoritos ADD FOREIGN KEY(idTCC) REFERENCES tcc (idTCC);
ALTER TABLE favorito_visitante ADD FOREIGN KEY(email) REFERENCES visitante (email);
ALTER TABLE favorito_aluno ADD FOREIGN KEY(matricula) REFERENCES aluno (matricula);
ALTER TABLE favorito_professor ADD FOREIGN KEY(matricula) REFERENCES professor (matricula);
ALTER TABLE nomealternativo ADD FOREIGN KEY(idCategoria) REFERENCES categoria (idCategoria);
ALTER TABLE indicacao ADD FOREIGN KEY(idCurso) REFERENCES curso (idCurso);
ALTER TABLE indicacao ADD FOREIGN KEY(idInstituicao) REFERENCES campus (idCampus);
ALTER TABLE indicacao ADD FOREIGN KEY(matricula) REFERENCES professor (matricula);
ALTER TABLE indicacao ADD FOREIGN KEY(idTCC) REFERENCES tcc (idTCC);
ALTER TABLE tcc ADD FOREIGN KEY(idCurso) REFERENCES curso (idCurso);
ALTER TABLE tcc ADD FOREIGN KEY(idCampus) REFERENCES campus (idCampus);
ALTER TABLE tcc ADD FOREIGN KEY(matricula) REFERENCES aluno (matricula);
ALTER TABLE campus_curso ADD FOREIGN KEY(idCampus) REFERENCES campus (idCampus);
ALTER TABLE categoria ADD FOREIGN KEY(categoriaPrincipal) REFERENCES categoria (idCategoria);
ALTER TABLE aluno ADD FOREIGN KEY(idEndereco) REFERENCES endereco (idEndereco);
ALTER TABLE professor ADD FOREIGN KEY(idEndereco) REFERENCES endereco (idEndereco);