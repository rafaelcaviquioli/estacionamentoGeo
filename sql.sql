
CREATE TABLE estacionamento
(
  id serial NOT NULL,
  nome character varying(255) NOT NULL,
  valor numeric(6,2) NOT NULL,
  CONSTRAINT estacionamento_pkey PRIMARY KEY (id)
);

CREATE SEQUENCE estacionamento_id_seq
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;

ALTER SEQUENCE estacionamento_id_seq owned by estacionamento.id;





CREATE TABLE usuario
(
  id serial NOT NULL,
  login character varying(255) NOT NULL,
  senha character varying(255) NOT NULL,
  CONSTRAINT usuario_pkey PRIMARY KEY (id)
);
CREATE SEQUENCE usuario_id_seq
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;

ALTER SEQUENCE usuario_id_seq owned by usuario.id;






CREATE TABLE veiculo (
	id serial,
	placa char(7),
        CONSTRAINT veiculo_pkey PRIMARY KEY (id)
);
CREATE SEQUENCE veiculo_id_seq
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;

ALTER SEQUENCE veiculo_id_seq owned by veiculo.id;

