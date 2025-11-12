use site_receitas;

create table usuario (
id int primary key auto_increment,
nome varchar (100) not null,
email varchar (100) unique not null,
senha varchar (150) not null,
dataCadastro date not null,
tipo_usuario ENUM('comum', 'admin') DEFAULT 'comum'
);

create table receita (
id int primary key auto_increment,
titulo varchar (100) not null,
descricao text not null,
modoprep text not null,
rendimento varchar (50),
datacriacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

create table ingrediente (
id int primary key auto_increment,
nome varchar (50) not null
);

create table receita_ingrediente (
id int primary key auto_increment,
id_receita int not null, -- fk para receitas 
id_ingrediente int not null, -- fk para ingredientes
quantidade decimal (10,2) not null,
unidade varchar (50)
);

create table categoria (
id int primary key auto_increment,
nome varchar (25) not null
);

create table comentario (
id int primary key auto_increment,
id_usuario int not null,
id_receita int not null,
texto text not null,
dataComentario DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
