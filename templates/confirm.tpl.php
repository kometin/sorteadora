<?function Style($context){?>
<style type="text/css">
   
</style>
<?}?>

<?function Script($context){?>
<script type ="text/javascript">
    $(function(){
        
    });
</script>
<?}?>

<?function Body($context){?>

<?$info = (object)$context->data[0]?>

<div class="panel panel-primary">
    <div class="panel-heading panel-title">Información / Order information</div>
    <div class="panel-body">
        <table class="table table-striped table-condensed">
            <tr>
                <td width = ""><label>Fecha / Order date</label></td>
                <tD width = "150"><?=SimpleDate($info->Fecha_Orden)?></tD>
                <td width = "150"><label>Servicio / Service</label></td>
                <tD width = "200"><?=($info->Servicio)?></tD>
            </tr>
            <tr>
                <td><label>Parte / Part Number</label></td>
                <tD><?=($info->Numero_Parte)?></tD>
                <td><label>Folio / Order ID</label></td>
                <tD><?=substr($info->Empresa, 0, 3) . format($info->Folio, 3, "0")?></tD>
            </tr>
            <tr>
                <td><label>Descripción / Description</label></td>
                <tD colspan="3"><?=($info->Descripcion)?></tD>
            </tr>
            <tr>
                <td><label>Herramientas / Special Tools</label></td>
                <tD colspan="3"><?=($info->Herramientas)?></tD>
            </tr>
            <tr>
                <td><label>Equipo especial medición / Special Measurement Equipment</label></td>
                <tD colspan="3"><?=$info->Medidores?></tD>
            <tr>
                <td><label>Químicos especiales / Special Chemical</label></td>
                <tD colspan="3"><?=($info->Quimicos)?></tD>
            </tr>
            <tr>
                <td><label>Otros / Others</label></td>
                <tD colspan="3"><?=($info->Otros)?></tD>
            </tr>
            <tr>
                <td><label>Total / Total pieces</label></td>
                <tD><?=number_format($info->Total_Partes)?></tD>
                <td><label>Tiempo / Rate per part</label></td>
                <tD><?=($info->Tiempo_x_Parte)?> min.</tD>
            </tr>
        </table>
    </div>
</div>

<div class="panel panel-info">
    <div class="panel-heading panel-title">Servicio / Service data</div>
    <div class="panel-body">
        <table class="table table-striped table-condensed">
            <?foreach($context->data as $d){?>
            <tr>
            <?switch($d[Tipo_Medicion]){
                case "1":?> 
                <td><label>Defecto / Defect</label></td>
                <tD><?=$d[Factor]?></tD>
                <?break; 
                case "2":?>
                <td><label>Característica / Feature</label></td>
                <tD><?=$d[Factor]?></tD>
                <td><label>Regla / Rule</label></td>
                <tD><?=str_replace(array("Mayor", "Menor", "Rango"), array("Greater than", "Lower than", "Tolerance +/- " . $d[Tolerancia]), $d[Regla])?></tD>
                <?if($d[Especificacion]){?>
                <td><label>Especificación / Especification</label></td>
                <tD><?=$d[Especificacion]?></tD>
                <?}?>
                <?break;?>
            <?}?>
            </tr>
            <?}?>
        </table>
    </div>
</div>

<div class="panel panel-danger">
    <div class="panel-heading panel-title">Acciones disponibles / Available actions</div>
    <div class="panel-body text-center">
        <?switch($context->data[0][Estatus]){
            case "1":?>

                <a href ="?action=continue&order=<?=$info->Clave?>" class="btn btn-danger btn-lg" id ="btnConfirm"><i class="fa fa-check"></i> Confirmar servicio / Confirm accept order</a>
            
                <?break;
            case "2":?>
                
                <div class="alert alert-success">
                    <p><label>La órden de servicio se encuentra en proceso</label></p>
                    <p><label>This order is in process</label></p>
                </div>
                
                <?break;
         }?>
    </div>
</div>



<?}?>

