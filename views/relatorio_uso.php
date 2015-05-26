<?php
$success = $this->session->getFlashBag()->get('success');
$error = $this->session->getFlashBag()->get('error');
$relatorio = $this->request->query->get('relatorio');


Tool::alert("success", $success);
Tool::alert("error", $error);
?>
<div class="row clearfix">
    <div class="col-md-12 column">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="col-sm-2">Estacionamento</th>
                    <th class="col-sm-2">Placa</th>
                    <th class="col-sm-2">Data Entrada</th>
                    <th class="col-sm-2">Data Saída</th>
                    <th class="col-sm-2">Valor Cobrado</th>
                    <th class="col-sm-2">Situação</th>
                </tr>
            </thead>
            <?php if (count($relatorio)) { ?>
                <tbody>
                    <?php
                    foreach ($relatorio as $estacionamento) {
                        ?>
                        <tr>
                            <td class="col-sm-2"><?php echo $estacionamento['nome']; ?></td>
                            <td class="col-sm-2"><?php echo $estacionamento['placa']; ?></td>
                            <td class="col-sm-2"><?php echo \Tool::converteData("Y-m-d H:i:s", "d/m/Y - H:i", $estacionamento['entrada']); ?></td>
                            <td class="col-sm-2"><?php echo $estacionamento['saida'] != "" ? \Tool::converteData("Y-m-d H:i:s", "d/m/Y - H:i", $estacionamento['saida']) : NULL; ?></td>
                            <td class="col-sm-2">R$ <?php echo \Tool::formatToMoney($estacionamento['valor']); ?></td>
                            <td class="col-sm-2" style="font-weight: bold; <?php echo !$estacionamento['situacao'] ? "color: red;" : "color: green;"; ?>"><?php echo $estacionamento['situacao'] ? "Encerrado" : "Estacionado"; ?></td>
                            
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            </table>
            <div class="alert alert-info">Nenhum registro de estacionamento.</div>
        <?php } ?>
    </div>
</div>
