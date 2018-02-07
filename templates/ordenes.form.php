<script>
 var ser = new Array();

<?php foreach ($context->serviciosguardados as $u) { ?>
            ser.push("<?php echo $u ?>");
<?php } ?>
 MultiSelect($('#Servicios'), '', '', false, ser);

    $(function(){  
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
        <label>Cliente</label>
        <input type="text" class="form-control require" name="cliente_id" value="<?=$data[0]['cliente_id']?>" placeholder="cliente_id">
    </div>
    <div class="form-group">
        <label>Número de partes</label>
        <input type="text" class="form-control " name="Numero_parte" value="<?=$data[0]['Numero_parte']?>" placeholder="Numero_parte">
    </div>
    <div class="form-group">
        <label>Descripción</label>
        <textarea  class="form-control require" name="Descripcion" id="Descripcion"  placeholder="Descripcion"><?=$data[0]['Descripcion']?></textarea>
    </div>
    <div class="form-group">
        <label>Folio</label>
        <input type="text" class="form-control " name="Folio" value="<?=$data[0]['Folio']?>" placeholder="Folio">
    </div>
    <div class="form-group">
        <label>Herremientas</label>
        <input type="text" class="form-control " name="Herremientas" value="<?=$data[0]['Herremientas']?>" placeholder="Herremientas">
    </div>
    <div class="form-group">
        <label>Medidores</label>
        <input type="text" class="form-control " name="Medidores" value="<?=$data[0]['Medidores']?>" placeholder="Medidores">
    </div>
    <div class="form-group">
        <label>Químicos</label>
        <input type="text" class="form-control " name="Quimicos" value="<?=$data[0]['Quimicos']?>" placeholder="Quimicos">
    </div>
    <div class="form-group">
        <label>Otros</label>
        <input type="text" class="form-control " name="Otros" value="<?=$data[0]['Otros']?>" placeholder="Otros">
    </div>
    <div class="form-group">
        <label>Tiempo por parte</label>
        <input type="text" class="form-control " name="Tiempo_x_parte" value="<?=$data[0]['Tiempo_x_parte']?>" placeholder="Tiempo_x_parte">
    </div>
    <div class="form-group">
        <label>Total de partes</label>
        <input type="text" class="form-control " name="Total_partes" value="<?=$data[0]['Total_partes']?>" placeholder="Total_partes">
    </div>
    <div class="form-group">
        <label>Fecha cierre</label>
        <input type="text" class="form-control date" name="Fecha_cierre" value="<?= SimpleDate($data[0]['Fecha_cierre'])?>" placeholder="Numero_parte">
    </div>
    <select multiple="multiple" name="Servicios[]" id="Servicios" class="require  form-control" >
                    <? foreach($context->servicios as $un){?>
                    <option value="<?= $un['id'] ?>" ><?= $un['Servicio'] ?></option>
                    <? }?>
    </select>    


    <input type="hidden" name="id" id="id" value="<?=$context->id?>">
    <p>
        <a class="btn btn-success btn-lg" id ="btnSave"><i class="fa fa-save"></i> Guardar</a>
    </p>
</form>