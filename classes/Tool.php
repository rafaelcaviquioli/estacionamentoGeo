<?php

/**
 * Description of Tool
 * A Classe ToolBox conter� todas as ferramentas de uso constante de Entidades.
 * @author Rafael
 */
class Tool {

    static function converteData($formatoDe, $formatoPara, $valor) {
        try {
            if ($formatoDe == 'd/m/Y') {
                $valor = implode("-", array_reverse(explode("/", $valor)));
            }else if ($formatoDe == 'd/m/Y H:i:s') {
                $valor = explode(" ", $valor);
                $valor = implode("-", array_reverse(explode("/", $valor[0]))) . " " . $valor[1];
            }
            $dateTime = new DateTime($valor);
            return $dateTime->format($formatoPara);
        } catch (Exception $e) {
            return false;
        }
    }

    static function validaId($codigo) {
        return ((int)$codigo) > 0;
    }

    static function formatToMoney($valor) {
        return number_format($valor, 2, ',', '.');
    }

    static function formatFromMoney($valor) {
        return str_replace('R$ ', '', str_replace(',', '.', str_replace('.', '', $valor)));
    }

    static function alert($tipo, $mensagem, $aditional = NULL) {
        if (count($mensagem)) {
            $mensagem = implode("<br />", $mensagem);

            if ($tipo == 'success') {
                $cssSpam = "chevron-down";
            } else if ($tipo == 'error') {
                $tipo = "danger";
                $cssSpam = "remove";
            } else if ($tipo == 'warning') {
                $cssSpam = "envelope";
            }
            echo "
            <div class=\"alert alert-$tipo\">
                <spam class=\"glyphicon glyphicon-$cssSpam\"></spam> $mensagem $aditional
            </div>";
        }
    }

    //Se o Mês não for informado retorna o mês atual
    static function getDescricaoMes($mes) {
        if ($mes == '1') {
            return 'Janeiro';
        } elseif ($mes == '2') {
            return 'Fevereiro';
        } elseif ($mes == '3') {
            return 'Março';
        } elseif ($mes == '4') {
            return 'Abril';
        } elseif ($mes == '5') {
            return 'Maio';
        } elseif ($mes == '6') {
            return 'Junho';
        } elseif ($mes == '7') {
            return 'Julho';
        } elseif ($mes == '8') {
            return 'Agosto';
        } elseif ($mes == '9') {
            return 'Setembro';
        } elseif ($mes == '10') {
            return 'Outubro';
        } elseif ($mes == '11') {
            return 'Novembro';
        } elseif ($mes == '12') {
            return 'Dezembro';
        } else {
            return Tool::getDescricaoMes(date('m'));
        }
    }

    static function abreviarString($str, $limit) {
        if ($limit < 3) {
            $limit = 3;
        }
        if (strlen($str) > $limit) {
            return substr($str, 0, $limit - 3) . '...';
        } else {
            return $str;
        }
    }

    static function modal($title = NULL, $body, $buttonConfirm = NULL, $id) {
        echo "
        <div class=\"modal fade\" id=\"myModal$id\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel$id\" aria-hidden=\"true\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
                        <h4 class=\"modal-title\" id=\"myModalLabel$id\">$title</h4>
                    </div>
                    <div class=\"modal-body\">
                        <h4>$body</h4>
                    </div>
                    <div class=\"modal-footer\">
                        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Cancelar</button>";
        if (!is_null($buttonConfirm)) {
            echo "<button type=\"submit\" class=\"btn btn-danger\">$buttonConfirm</button>";
        }
        echo "</div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->    
        ";
    }

    //Função semelhante a função PGTO do excel
    static function pgto($Valor, $Parcelas, $Juros) {

        $Juros = bcdiv($Juros, 100, 15);
        $E = 1.0;
        $cont = 1.0;

        for ($k = 1; $k <= $Parcelas; $k++) {
            $cont = bcmul($cont, bcadd($Juros, 1, 15), 15);
            $E = bcadd($E, $cont, 15);
        }
        $E = bcsub($E, $cont, 15);

        $Valor = bcmul($Valor, $cont, 15);
        return bcdiv($Valor, $E, 15);
    }

}

?>