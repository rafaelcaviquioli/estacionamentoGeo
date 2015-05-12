<?php

/**
 * Created by PhpStorm.
 * User: j.norenberg
 * Date: 23/02/2015
 * Time: 15:22
 */
class Idioma extends \ORM\IdiomaORM {
    //Indices são os ids dos idiomas no banco de dados
    static $traducoes = array(
        1 => array(
            'enviar' => 'send',
            'frase_correcao_monetaria' => 'Indexation and labor calculations',
            'encontre_agora' => 'find now',
            'siga_nos' => 'Follow us',
            'entre_em_contato' => 'CONTACT US',
            'nome' => 'name',
            'telefone' => 'phone',
            'mensagem'=>'message',
            'entraremos_em_contato'=> 'We will contact you as soon as possible.',
            'cadastro_newsletter' => 'Thank you for registering on our list.'
        ),
        2 => array(
            'enviar' => 'enviar',
            'frase_correcao_monetaria' => 'Correção monetária e cálculos trabalhistas',
            'encontre_agora' => 'encontre agora',
            'siga_nos' => 'Siga-nos',
            'entre_em_contato' => 'ENTRE EM CONTATO CONOSCO',
            'nome' => 'nome',
            'telefone' => 'telefone',
            'mensagem'=>'mensagem',
            'entraremos_em_contato'=> 'Entraremos em contato com você o mais breve possível.',
            'cadastro_newsletter' => 'Obrigado por se cadastrar na nossa lista.'
        )
    );

    public function __construct($id = null) {
        parent::__construct($id);
    }

    public function getPaginas($update = false, $where = null, $order = array('sequencia'), $limit = '*') {
        if (is_null($this->paginas) OR $update) {
            $paginas = \Pagina::newInstance()->getAll("idioma='" . $this->getId() . "'" . ($where != null ? " AND " . $where : null), $order, $limit);
            $this->paginas = $paginas;
        }
        return $this->paginas;
    }

    public function getProdutos($update = false, $where = null, $order = array('titulo'), $limit = '*') {
        if (is_null($this->produtos) OR $update) {
            $produtos = \Produto::newInstance()->getAll("idioma='" . $this->getId() . "'" . ($where != null ? " AND " . $where : null), $order, $limit);
            $this->produtos = $produtos;
        }
        return $this->produtos;
    }

    public function getCategorias($update = false, $where = null, $order = array('titulo'), $limit = '*') {
        if (is_null($this->categorias) OR $update) {
            $produtos = \Categoria::newInstance()->getAll("idioma='" . $this->getId() . "'" . ($where != null ? " AND " . $where : null), $order, $limit);
            $this->categorias = $produtos;
        }
        return $this->categorias;
    }

    public function getCategoriasServicos($update = false, $where = null, $order = array('titulo'), $limit = '*') {
        if (is_null($this->categoriasServicos) OR $update) {
            $categoriasServicos = \CategoriaServicos::newInstance()->getAll("idioma='" . $this->getId() . "'" . ($where != null ? " AND " . $where : null), $order, $limit);
            $this->categoriasServicos = $categoriasServicos;
        }
        return $this->categoriasServicos;
    }

    public function getServicos($update = false, $where = null, $order = array('titulo'), $limit = '*') {
        if (is_null($this->servicos) OR $update) {
            $servicos = \Servico::newInstance()->getAll("idioma='" . $this->getId() . "'" . ($where != null ? " AND " . $where : null), $order, $limit);
            $this->servicos = $servicos;
        }
        return $this->servicos;
    }


    public function getCertificacoes($update = false, $where = null, $order = array('titulo'), $limit = '*') {
        if (is_null($this->certificacoes) OR $update) {
            $certificacoes = \Certificacao::newInstance()->getAll("idioma='" . $this->getId() . "'" . ($where != null ? " AND " . $where : null), $order, $limit);
            $this->certificacoes = $certificacoes;
        }
        return $this->certificacoes;
    }

    public function getEstruturas($update = false, $where = null, $order = array('titulo'), $limit = '*') {
        if (is_null($this->estruturas) OR $update) {
            $estruturas = \Estrutura::newInstance()->getAll("idioma='" . $this->getId() . "'" . ($where != null ? " AND " . $where : null), $order, $limit);
            $this->estruturas = $estruturas;
        }
        return $this->estruturas;
    }

    public function alterarIdioma($id = 2, $session) {
        if ($id > 0) {
            $idioma = new \Idioma($id);
        } else {
            $idioma = new \Idioma(2);
        }
        $session->set('idioma', $idioma);
    }

    public static  function getIdiomaAtual($session) {
        $idiomaAtual = $session->get('idioma');
        if (!$idiomaAtual instanceof Idioma OR !$idiomaAtual->isLoad()) {
            Idioma::newInstance()->alterarIdioma(2, $session);
        }
        return $session->get('idioma');
    }

    public static function getTraducao($string, $session) {
        $idioma = Idioma::newInstance()->getIdiomaAtual($session);
        return Idioma::$traducoes[$idioma->getId()][$string];
    }
}