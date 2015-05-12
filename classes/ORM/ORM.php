<?php

/**
 * Description of Entidade
 * A Classe Entidade ira fixar alguns metodos padroes que todas as entidades devem conter.
 * @author Rafael
 */

namespace ORM;

use \Exception,
    \Connection;

abstract class ORM {
    /*
     * Indicador da chave primaria de cada classe herdada.
     */

    protected $primaryKey;
    /*
     * Nome  do objeto que est herdando a classe Entidade.
     */
    protected $nomeEntidade;

    /*
     * Tabela de armazenamento da entidade.
     */
    protected $tabelaEntidade;

    /*
     * Lista de atributos que participarão da persistência no banco de dados
     */
    protected $atributosPersistencia;
    //Cria exceo padro para objeto nula
    static $ExceptionEntidadeNula;

    public function __construct($nomeEntidade, $tabelaEntidade, $primaryKey) {
        //construtor da classe ConexaoRoot

        $this->nomeEntidade = $nomeEntidade;
        $this->setTabelaEntidade($tabelaEntidade);

        $this->primaryKey = $primaryKey;

        //Cria exceo padro para objeto nula
        self::$ExceptionEntidadeNula = new \Exception("Erro!, a objeto " . $this->nomeEntidade . " não foi carregada.");
    }

    /* beforeInsert
     *  Método chamado antes de qualquer insert.
     */

    protected function beforeInsert() {
        return true;
    }

    /* beforeSave
     *  Método chamado antes de qualquer save.
     */

    protected function beforeSave() {
        return true;
    }

    /* insert()
     * Metodo responsável por apenas criar o registro no banco de dados com a chave primária.
     */

    private function insert() {
        try {
            $this->beforeInsert();
        } catch (Exception $exIgnore) {

        }
        $primaryKey = $this->primaryKey;
        //Verifica se a objeto não está criada.
        if (!$this->isLoad()) {
            $sql = "INSERT INTO " . $this->tabelaEntidade . " (" . $this->primaryKey . ") VALUES (NULL)";
            $conexao = Connection::getConnection();
            if ($conexao->query($sql)) {
                $this->$primaryKey = $conexao->insert_id;

                try {
                    //Chama onInsert (CallBack).
                    $this->onInsert();
                } catch (Exception $e) {

                }

                return true;
            } else {
                throw new Exception("Erro ao criar objeto " . $this->nomeEntidade . " no banco de dados.");
            }
        } else {
            throw new Exception("Erro ao inserir! A objeto " . $this->nomeEntidade . " ja está criada.");
        }
    }

    /*
     * onInsert()
     * Método é executado toda vez que ocorre um insert.
     */

    protected function onInsert() {
        return true;
    }

    /* salve()
     * Método responsível por salvar todos os atributos da classe no banco de dados.
     */

    public function save($addSlaches = true) {
        $primaryKey = $this->primaryKey;

        try {
            $this->beforeSave();
        } catch (Exception $exIgnore) {

        }

        if ($this->validationSave()) {
            //Verifica se a objeto est criada.
            if ($this->isLoad()) {
                //Vali do objeto antes de salvar.
                //Prepara SET da SQL
                $set = NULL;
                foreach ($this->atributosPersistencia as $atributo) {
                    if ($addSlaches) {
                        $set .= $atributo . " = '" . addslashes($this->$atributo) . "',";
                    } else {
                        $set .= $atributo . " = '" . ($this->$atributo) . "',";
                    }
                }
                //remove virgula do fim da sql.
                $set = trim($set);
                $set = substr($set, 0, -1);
                $sql = "UPDATE " . $this->tabelaEntidade . " SET $set WHERE " . $this->primaryKey . " = '" . $this->$primaryKey . "'";
                $conexao = Connection::getConnection();
                if ($conexao->query($sql)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if ($this->insert()) {
                    $this->save();
                }
            }
        } else {
            throw new Exception("Erro ao salvar a objeto " . $this->nomeEntidade . ", falha na validação.");
        }
    }

    /* delete()
     * Mtodo responsvel por deletar o objeto do banco de dados.
     */

    public function delete() {
        $primaryKey = $this->primaryKey;
        //Verifica se a objeto est criada.
        if ($this->isLoad()) {
            //Chama o mtodo de validao da excluso.
            if ($this->validationDelete()) {
                $conexao = Connection::getConnection();
                if ($conexao->query("DELETE FROM " . $this->tabelaEntidade . " WHERE " . $this->primaryKey . " = '" . $this->$primaryKey . "'")) {
                    try {
                        $this->onDelete();
                    } catch (Exception $e) {

                    }
                    return true;
                } else {
                    throw new Exception("Erro ao excluir objeto " . $this->nomeEntidade . " código " . $this->$primaryKey . " do banco de dados.");
                }
            }
        } else {
            throw new Exception("Erro ao excluir! O objeto " . $this->nomeEntidade . " não está criado.");
        }
    }

    /*
     * validationDelete
     * To do objeto pode sobrescrever este mtodo com seu algoritmo para validar se uma excluso pode ser feita ou no.
     */

    protected function validationDelete() {
        return true;
    }

    /*
     * validationSave
     * To do objeto pode sobrescrever este mtodo com seu algoritmo para validar se uma objeto  pode ser cadastrada ou no.
     */

    protected function validationSave() {
        return true;
    }

    /*
     * load()
     * Mtodo responsvel por carregar o valor de todos os atributos da classe buscando os atributos que tem persistencia no banco de dados.
     */

    protected function load() {
        $primaryKey = $this->primaryKey;
        if (!is_null($this->$primaryKey)) {
            //Cria sql do select
            $select = implode(", ", $this->atributosPersistencia);
            $conexao = Connection::getConnection();

            $rs = $conexao->query("SELECT " . $this->primaryKey . ", $select FROM " . $this->tabelaEntidade . " WHERE " . $this->primaryKey . " = '" . $this->$primaryKey . "' LIMIT 1");
            if ($rs) {
                if ($rs->num_rows) {
                    $dados = $rs->fetch_assoc();

                    //Seta valor da chave primaria
                    $this->$primaryKey = $dados[$this->primaryKey];

                    //Percorre todos os atributos e preenche o valor.
                    foreach ($this->atributosPersistencia as $atributo) {
                        $this->$atributo = $dados[$atributo];
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                throw new Exception("Erro ao salvar a objeto " . $this->nomeEntidade . " no banco de dados.");
            }
        } else {
            throw new Exception("Erro ao carregar objeto " . $this->nomeEntidade . ", o código não foi setado.");
        }
    }

    /* getAll()
     * Mtodo responsvel por buscar todos os objetos deste tipo de objeto de acordo com os parmetros.
     */

    public function getAll($where = NULL, $order = NULL, $limit = array('20')) {

        //verifica os parmetros passados.
        if (!is_array($order) AND !is_null($order)) {
            throw new Exception("O parametro order é inválido.");
        }
        if (!is_array($limit) AND !is_null($limit) AND (is_string($limit) AND $limit != '*')) {
            throw new Exception("O parametro limit é inválido.");
        }

        //Armazena atributos entre virtula.
        $atributosSql = implode(", ", $this->atributosPersistencia);

        //Inicializa variaveis.
        $whereSql = $orderSql = $limitSql = NULL;

        if (!is_null($where)) {
            $whereSql = "WHERE " . $where;
        }
        if (!is_null($order)) {
            $orderSql = "ORDER BY " . implode(", ", $order);
        }

        if (!is_null($limit) AND $limit != '*' AND !empty($limit)) {
            $limitSql = "LIMIT " . implode(", ", $limit);
        }
        $sql = trim("SELECT $this->primaryKey, $atributosSql FROM " . $this->tabelaEntidade . " " . $whereSql . " " . $orderSql . " " . $limitSql);
        $conexao = Connection::getConnection();
        $rs = $conexao->query($sql);

        if ($rs) {
            $listaEntidade = array();
            while ($dados = $rs->fetch_assoc()) {
                //Inicializa objeto herdado da entidade.
                $nomeEntidade = "\\" . $this->nomeEntidade;
                $entidade = new $nomeEntidade();

                //Inseri a primary key da pesquisa.
                $entidade->setPrimaryKey($dados[$this->primaryKey]);

                //Percorre todos os atributos  do objeto e nicializa preenchimento pelos metodos set.
                foreach ($this->atributosPersistencia as $atributo) {
                    $setAtribute = "set" . $atributo;
                    //Executa método set do atributo
                    $entidade->$setAtribute($dados[$atributo]);
                }

                $listaEntidade[] = $entidade;
            }
            return $listaEntidade;
        } else {
            throw new Exception("Erro ao executar metodo getAll na objeto " . $this->nomeEntidade);
        }
    }

    private function setTabelaEntidade($tabelaEntidade) {
        if (empty($tabelaEntidade)) {
            throw new Exception("Erro! Tabela  do objeto " . $this->nomeEntidade . " não setada.");
        } else {
            $this->tabelaEntidade = $tabelaEntidade;
        }
    }

    public function isLoad() {
        $primaryKey = $this->primaryKey;
        $primaryKey = (int)$this->$primaryKey;
        if ($primaryKey > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function persistAttribute($atribute) {
        $this->atributosPersistencia[] = $atribute;
    }

    public function getPrimaryKey() {
        $primaryKey = $this->primaryKey;
        return $this->$primaryKey;
    }

    public function setPrimaryKey($codigo) {
        $primaryKey = $this->primaryKey;
        $this->$primaryKey = $codigo;
    }

    /*
     * Método chamado após o método Delete for chamado.
     */

    public function onDelete() {
        return true;
    }

}
