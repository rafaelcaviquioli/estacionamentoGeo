<?php

class Config {
    public static $host = "localhost";
    public static $user = "root";
    public static $password = "pinaclespi";
    public static $db = "repretec";

    //Ex: http://www.pensador.com.br/
    public static $urlSite = "http://192.168.0.210/daniel/repretec/";

    //Variaveis para os textos das p�ginas
    public static $titulos = array('titulo_um', 'titulo_dois', 'representante'); //S�mente t�tulo
    public static $uploadFile = array('representante'); //Tem upload de arquivo
    public static $textosPadroes = array(3, 2, 4, 1, 8, 12 , 11, 26, 13, 15, 16, 14, 20, 24, 23, 25); //Textos que n�o podem ser exclu�dos
    public static $paginasPermitemNovosTextos = array(3, 4, 9, 10); //Paginas que permitem adicionas novos textos
}

?>
