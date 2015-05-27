
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


alter table veiculo_posicao add primary key (id);
alter table veiculo_estacionamento add primary key (id);

alter table veiculo_posicao add foreign key (id_veiculo) references veiculo(id);
alter table veiculo_estacionamento add foreign key (id_veiculo) references veiculo(id);
alter table veiculo_estacionamento add foreign key (id_estacionamento) references estacionamento(id);

select addgeometrycolumn('public', 'veiculo_posicao', 'ponto', 4326, 'POINT', 2);
select addgeometrycolumn('public', 'estacionamento', 'poligono', 4326, 'POLYGON', 2);




/* Trigger da tabela veiculo_posicao */
CREATE OR REPLACE FUNCTION monitoraPosicao () RETURNS trigger as $$

DECLARE
	ponto_anterior_esta_dentro boolean;
        aux record;
        aux2 integer := 0;
BEGIN
/* Entrou: */
    /* Busca por um estacionamento que o ponto esteja dentro do poligono */
    FOR aux IN SELECT poligono as poligono_estacionamento, id as id_estacionamento, valor as valor_estacionamento  FROM estacionamento WHERE st_within(NEW.ponto, poligono) = true LOOP   
        /* Encontru estacionamento na posicao atual*/
        RAISE NOTICE 'Esta Dentro';
        /* Verifica se a ultima posicao desse veículo foi dentro desse estacionamento*/
        SELECT INTO ponto_anterior_esta_dentro st_within(ponto, aux.poligono_estacionamento) FROM veiculo_posicao WHERE id_veiculo = NEW.id_veiculo ORDER BY id DESC LIMIT 1;
        IF ponto_anterior_esta_dentro THEN
            RAISE NOTICE 'Anterior Esta Dentro';
        ELSE
            RAISE NOTICE 'Anterior Esta Fora';
            /* Hora de estacionar*/
            INSERT INTO veiculo_estacionamento (id_veiculo, id_estacionamento, data_entrada, valor) VALUES (NEW.id_veiculo, aux.id_estacionamento, NOW(), aux.valor_estacionamento);
        END IF;
    END LOOP;

    /* Busca por estacionamentos pendentes e verifica se o carro ainda está dentro. */
    FOR aux IN SELECT ve.id as id_estacionamento FROM estacionamento e INNER JOIN veiculo_estacionamento ve ON ve.id_estacionamento = e.id WHERE ve.id_veiculo = NEW.id_veiculo AND ve.data_saida IS NULL AND st_within(NEW.ponto, e.poligono) = false LOOP   
        /* Encontrou um estacionamento pendente para esse veiculo e que não está mais estacionado */
        RAISE NOTICE 'Esta Fora';
        UPDATE veiculo_estacionamento SET data_saida = NOW() WHERE id = aux.id_estacionamento;
    END LOOP;
	
	RETURN NEW;
END;

$$ LANGUAGE plpgsql;

CREATE TRIGGER monitoraPosicao BEFORE INSERT OR UPDATE ON veiculo_posicao FOR EACH ROW EXECUTE PROCEDURE monitoraPosicao();


INSERT INTO veiculo_posicao (id_veiculo, data, ponto) VALUES (1, now(), st_geomfromtext('POINT(-23.553441 -46.543114)', 4326))