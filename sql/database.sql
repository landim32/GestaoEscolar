create table escola (
id_escola int not null auto_increment,
nome varchar(50) not null,
cod_situacao int not null default 1,
primary key(id_escola)
);

create table usuario (
id_usuario int not null auto_increment,
id_escola int not null,
nome varchar(50) not null,
email varchar(150) not null,
senha varchar(30) not null,
foto varchar(40),
cod_situacao tinyint not null default 1,
primary key(id_usuario),
foreign key (id_escola) REFERENCES escola(id_escola)
);

create table curso (
id_curso int not null auto_increment,
id_escola int not null,
data_inclusao datetime not null,
ultima_alteracao datetime not null,
nome varchar(30) not null,
cod_situacao tinyint not null default 1,
primary key(id_curso),
foreign key (id_escola) REFERENCES escola(id_escola)
);

create table turma (
id_turma int not null auto_increment,
id_curso int not null,
data_inclusao datetime not null,
ultima_alteracao datetime not null,
turno char(1) not null default 'm',
nome varchar(30) not null,
cod_situacao tinyint not null default 1,
primary key(id_turma),
foreign key (id_curso) REFERENCES curso(id_curso)
);

create table pessoa (
id_pessoa int not null auto_increment,
id_escola int not null,
data_inclusao datetime not null,
ultima_alteracao datetime not null,
nome varchar(50) not null,
data_nascimento datetime,
genero char(1),
telefone1 varchar(15),
telefone2 varchar(15),
telefone3 varchar(15),
telefone4 varchar(15),
email1 varchar(160),
email2 varchar(160),
email3 varchar(160),
email4 varchar(160),
endereco varchar(60),
complemento varchar(30),
bairro varchar(30),
cidade varchar(30),
uf char(2),
cod_situacao int not null default 1,
primary key(id_pessoa),
foreign key (id_escola) REFERENCES escola(id_escola)
);

create table aluno (
id_pessoa int not null,
id_turma int not null,
primary key(id_pessoa, id_turma),
foreign key (id_pessoa) REFERENCES pessoa(id_pessoa),
foreign key (id_turma) REFERENCES turma(id_turma)
);

create table aluno_responsavel (
id_aluno int not null,
id_responsavel int not null,
ordem tinyint not null default 1,
primary key(id_aluno, id_responsavel),
foreign key (id_aluno) REFERENCES pessoa(id_pessoa),
foreign key (id_responsavel) REFERENCES pessoa(id_pessoa)
);

create table movimento_tipo (
id_tipo int not null auto_increment,
id_escola int not null,
nome varchar(40) not null,
cod_situacao tinyint not null default 1,
primary key(id_tipo),
foreign key (id_escola) REFERENCES escola(id_escola)
);

create table movimento (
id_movimento int not null auto_increment,
id_escola int not null,
id_pessoa int not null,
cod_tipo int not null,
id_aluno int,
tipo char(1) not null default 'c',
data_inclusao datetime not null,
ultima_alteracao datetime not null,
data_vencimento datetime not null,
credito double,
debito double,
cod_situacao tinyint not null default 1,
primary key(id_movimento),
foreign key (id_escola) REFERENCES escola(id_escola),
foreign key (id_pessoa) REFERENCES pessoa(id_pessoa),
foreign key (id_aluno) REFERENCES pessoa(id_pessoa)
);

ALTER TABLE pessoa ADD CONSTRAINT FOREIGN KEY (id_turma) REFERENCES turma(id_turma);
ALTER TABLE pessoa ADD CONSTRAINT FOREIGN KEY (id_filho) REFERENCES pessoa(id_pessoa);