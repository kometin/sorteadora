<script>
 var ser = new Array();

<?php foreach ($context->serviciosguardados as $u) { ?>
            ser.push("<?php echo $u ?>");
<?php } ?>
 MultiSelect($('#Servicios'), '', '', false, ser);

    $(function(){  
                DatePicker($('.date'));

        $('#btnSave').click(function(){ 
            if($('#Servicios').val()!=null){
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
            }else{
                Error("Debe seleccionar almenos un servicio");
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
        <select  name="cliente_id" id="cliente_id" class="require  form-control" >
       <? foreach($context->clientes as $c){?>
       <option value="<?= $c['id'] ?>" ><?= $c['Empresa'] ?></option>
       <? }?>
       </select> 
    </div>
    <div class="form-group">
        <label>Número de partes</label>
        <input type="text" class="form-control require"  name="Numero_parte" id="Numero_parte" value="<?=$data[0]['Numero_Parte']?>" placeholder="Número de parte">
    </div>
    <div class="form-group">
        <label>Descripción</label>
        <textarea  class="form-control require" name="Descripcion" id="Descripcion"  placeholder="Descripción"><?=$data[0]['Descripcion']?></textarea>
    </div>
    <div class="form-group">
        <label>Herramientas</label>
        <textarea class="form-control " name="Herramientas" placeholder="Herramientas"><?=$data[0]['Herramientas']?></textarea>
    </div>
    <div class="form-group">
        <label>Medidores</label>
        <textarea  class="form-control " name="Medidores" placeholder="Medidores"><?=$data[0]['Medidores']?></textarea>
    </div>
    <div class="form-group">
        <label>Químicos</label>
        <textarea  class="form-control " name="Quimicos"  placeholder="Químicos"><?=$data[0]['Quimicos']?></textarea>
    </div>
    <div class="form-group">
        <label>Otros</label>
        <textarea  class="form-control " name="Otros" placeholder="Otros"><?=$data[0]['Otros']?></textarea>
    </div>
    <div class="form-group">
        <label>Tiempo por parte</label>
        <input type="text" class="form-control " name="Tiempo_x_parte" value="<?=$data[0]['Tiempo_x_Parte']?>" placeholder="Tiempo por cada parte">
    </div>
    <div class="form-group">
        <label>Total de partes</label>
        <input type="text" class="form-control numeric" style="width:200px" name="Total_partes" value="<?=$data[0]['Total_Partes']?>" placeholder="Total partes">
    </div>
    <div class="form-group">
        <label>Fecha cierre</label>
        <input type="text" class="form-control date require"  style="width:120px" name="Fecha_Cierre" value="<?= SimpleDate($data[0]['Fecha_Cierre'])?>" placeholder="Fecha de cierre">
    </div>
    <label>Servicios</label>
    <select multiple="multiple"  placeholder="Servicios" name="Servicios[]" id="Servicios" class="require  form-control MultiSelect" >
                    <? foreach($context->servicios as $un){?>
                    <option value="<?= $un['id'] ?>" ><?= $un['Servicio'] ?></option>
                    <? }?>
    </select>   
    <input type="hidden" name="id" id="id" value="<?=$context->id?>">
    <br>
    <p>
        <a class="btn btn-success btn-lg" id ="btnSave"><i class="fa fa-save"></i> Guardar</a>
    </p>
</form>