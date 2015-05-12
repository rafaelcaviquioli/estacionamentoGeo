<?php

class Config {
    public static $host = "localhost";
    public static $user = "root";
    public static $password = "pinaclespi";
    public static $db = "repretec";

    //Ex: http://www.pensador.com.br/
    public static $urlSite = "http://192.168.0.210/daniel/repretec/";

    //Variaveis para os textos das páginas
    public static $titulos = array('titulo_um', 'titulo_dois', 'representante'); //Sómente título
    public static $uploadFile = array('representante'); //Tem upload de arquivo
    public static $textosPadroes = array(3, 2, 4, 1, 8, 12 , 11, 26, 13, 15, 16, 14, 20, 24, 23, 25); //Textos que não podem ser excluídos
    public static $paginasPermitemNovosTextos = array(3, 4, 9, 10); //Paginas que permitem adicionas novos textos
}

?>
