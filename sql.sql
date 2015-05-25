
CREATE TABLE estacionamento
(
  id serial NOT NULL,
  nome character varying(255) NOT NULL,
  valor numeric(6,2) NOT NULL,
  CONSTRAINT estacionamento_pkey PRIMARY KEY (id)
);
ALTER SEQUENCE estacionamento_id_seq owned by estacionamento.id;





CREATE TABLE usuario
(
  id serial NOT NULL,
  login character varying(255) NOT NULL,
  senha character varying(255) NOT NULL,
  CONSTRAINT usuario_pkey PRIMARY KEY (id)
);
ALTER SEQUENCE usuario_id_seq owned by usuario.id;






CREATE TABLE veiculo (
	id serial,
	placa char(7),
        CONSTRAINT veiculo_pkey PRIMARY KEY (id)
);
ALTER SEQUENCE veiculo_id_seq owned by veiculo.id;






CREATE TABLE veiculo_posicao(
	id serial,
	id_veiculo integer,
	data timestamp
);
ALTER SEQUENCE veiculo_posicao_id_seq owned by veiculo_posicao.id;



CREATE TABLE veiculo_estacionamento(
	id serial,
	id_veiculo integer,
	id_estacionamento integer,
	data_entrada timestamp,
	data_saida timestamp,
	valor decimal(10,2)
);
ALTER SEQUENCE veiculo_estacionamento_id_seq owned by veiculo_estacionamento.id;


alter table veiculo add primary key (id);
alter table veiculo_posicao add primary key (id);
alter table estacionamento add primary key (id);
alter table veiculo_estacionamento add primary key (id);

alter table veiculo_posicao add foreign key (id_veiculo) references veiculo(id);
alter table veiculo_estacionamento add foreign key (id_veiculo) references veiculo(id);
alter table veiculo_estacionamento add foreign key (id_estacionamento) references estacionamento(id);

select addgeometrycolumn('public', 'veiculo_posicao', 'ponto', 4326, 'POINT', 2);
select addgeometrycolumn('public', 'estacionamento', 'poligono', 4326, 'POLYGON', 2);





CREATE OR REPLACE FUNCTION monitoraPosicao () RETURNS trigger as $$

DECLARE
	resultado decimal;
BEGIN
	SELECT st_within(st_geomfromtext(NEW.ponto, 4326), poligono) as ponto_dentro FROM estacionamento
	
END;

$$ LANGUAGE plpgsql;
