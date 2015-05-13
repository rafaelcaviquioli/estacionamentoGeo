
CREATE TABLE estacionamento
(
  id serial NOT NULL,
  nome character varying(255) NOT NULL,
  valor numeric(6,2) NOT NULL,
  CONSTRAINT estacionamento_pkey PRIMARY KEY (id)
)

CREATE TABLE usuario
(
  id serial NOT NULL,
  ativo boolean NOT NULL,
  datacadastro date NOT NULL,
  operadorcadastro character varying(255) NOT NULL,
  nome character varying(255) NOT NULL,
  sobrenome character varying(255) NOT NULL,
  login character varying(255) NOT NULL,
  senha character varying(255) NOT NULL,
  CONSTRAINT usuario_pkey PRIMARY KEY (id)
)