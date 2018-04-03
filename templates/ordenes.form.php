<script>

<?php /*
 *  var ser = new Array();

 * foreach ($context->serviciosguardados as $u) { ?>
            ser.push("<?php echo $u ?>");
 MultiSelect($('#Servicios'), 'Servicios disponibles', 'Seleccionados', false, ser);

                <?php } */?>

    $(function(){
                DoSelect($('.select2'));
   
                DatePicker($('.date'));

        $('#btnSave').click(function(){ 
            if(Full($('#op'))) {
                 LoadButton($('#btnSave'));                                                                              
                 $.post('ordenes.php?action=save', $('#op').serialize(), function(data){
                    Ready();
                    if(data)
                        Error(data);
                    else{
                        ReloadGrid(grid, 'data/loadOrdenes.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));
                        OK("Guardado");
                        CloseModal();
                    }                                       
                 });                    
            }

        });
    });
</script>
<style>
    <? if($context->ver==1){?>
        .vista input{
            border:none;
        }
    <? }?>
</style>
<?
$data=$context->data;
?>
<form id="op" <? if($context->ver==1){echo 'class="vista"';}?> autocomplete="off">
   <? /* <div class="form-group">
        <label>Nombre</label>
        <input type="text" class="form-control require" name="Nombre" value="<?=$data[0]['Nombre']?>" placeholder="Nombre del cliente">
    </div>
    */?>
    <div class="form-group">
        <label>Empresa</label>
        </br>
        <select  name="cliente_id" id="cliente_id" class="require select2" style="width:500px" >
            <option></option>
        <? foreach($context->clientes as $c){?>
            <option value="<?= $c['id'] ?>" <? if($data[0]['cliente_id']==$c['id'] )echo "SELECTED";?> ><?= $c['Empresa'] ?></option>
        <? }?>
       </select> 
    </div>
    <div class="form-group">
        <label>Número de parte</label>
        <input type="text" class="form-control require"  name="Numero_parte" id="Numero_parte" value="<?=$data[0]['Numero_Parte']?>" placeholder="Número de parte">
    </div>
    <div class="form-group">
        <label>Descripción</label>
        <textarea  class="form-control require" name="Descripcion" id="Descripcion"  placeholder="Descripción de servicio"><?=$data[0]['Descripcion']?></textarea>
    </div>
    <div class="form-group">
        <label>Herramientas</label>
        <textarea class="form-control " name="Herramientas" placeholder="Herramientas especiales"><?=$data[0]['Herramientas']?></textarea>
    </div>
    <div class="form-group">
        <label>Medidores</label>
        <textarea  class="form-control " name="Medidores" placeholder="Equipo especial de medición "><?=$data[0]['Medidores']?></textarea>
    </div>
    <div class="form-group">
        <label>Químicos</label>
        <textarea  class="form-control " name="Quimicos"  placeholder="Químicos especiales"><?=$data[0]['Quimicos']?></textarea>
    </div>
    <div class="form-group">
        <label>Otros</label>
        <textarea  class="form-control " name="Otros" placeholder="Otros"><?=$data[0]['Otros']?></textarea>
    </div>
    <div class="form-group">
        <label>Tiempo por parte</label>
        <input type="text" class="form-control numeric require" style="width:200px" name="Tiempo_x_parte" value="<?=$data[0]['Tiempo_x_Parte']?>" placeholder="Tiempo por cada parte">
    </div>
    <div class="form-group">
        <label>Total de partes</label>
        <input type="text" class="form-control numeric require" style="width:200px" name="Total_partes" value="<?=$data[0]['Total_Partes']?>" placeholder="Total partes">
    </div>
    <? /*
    <div class="form-group">
        <label>Fecha cierre</label>
        <input type="text" class="form-control date require"  style="width:120px" name="Fecha_Cierre" value="<?= SimpleDate($data[0]['Fecha_Cierre'])?>" placeholder="Fecha de cierre">
    </div>
    */?>
    
    <label>Servicio</label>
    </br>
    <select <? if($context->id!='') echo "disabled"?>  placeholder="Servicios" name="servicio_id"  style="width:500px" id="servicio_id" class="require select2" >
        <option value=""></option>
                    <? foreach($context->servicios as $un){?>
                    <option value="<?= $un['id'] ?>" <? if($data[0]['servicio_id'] ==$un['id']) echo "SELECTED";?> ><?= $un['Servicio'] ?></option>
                    <? }?>
    </select>   
    <input type="hidden" name="id" id="id" value="<?=$context->id?>">
    <br></br>
    <p>
        <a class="btn btn-success btn-lg" id ="btnSave"><i class="fa fa-save"></i> Guardar</a>
    </p>
</form>