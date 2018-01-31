<script>
    $(function(){
        $('#btnSave').click(function(){
           if(Full($('#op'))) {
               if(validateRFC13($('#RFC').val()) ){
                    $.get('operadores.php?action=ckr&id='+$('#id').val()+'&rfc=' +$('#RFC').val() , function (e) {                        
                        if(e==''){
                            LoadButton($(this));
                            $.post('operadores.php?action=save', $('#op').serialize(), function(data){
                               Ready();
                               if(data)
                                   Error(data);
                               else{
                                   ReloadGrid(grid, 'data/loadOperadores.php');

                                   OK("Guardado");
                                   CloseModal();
                               }
                            });
                        }else{
                            Error("El RFC ya existe en otro operador");        
                        }
                    });                     
                }else
                    Error("El formato del RFC es incorrecto");
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
<form id="op" <? if($context->ver==1){echo 'class="vista"';}?>>
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" class="form-control require" name="Nombre" value="<?=$data[0]['Nombre']?>" placeholder="Nombre del operador">
    </div>
    <div class="form-group">
        <label>Ap. Paterno</label>
        <input type="text" class="form-control require" name="Paterno" value="<?=$data[0]['Paterno']?>" placeholder="Ap. Materno">
    </div>
    <div class="form-group">
        <label>Ap. Materno</label>
        <input type="text" class="form-control" name="Materno" value="<?=$data[0]['Materno']?>" placeholder="Ap. Materno">
    </div>
    <div class="form-group">
        <label>RFC</label>
        <input type="text" class="form-control require" name="RFC" id="RFC" value="<?=$data[0]['RFC']?>" placeholder="RFC con homoclave">
    </div>
    <div class="form-group">
        <label>CURP</label>
        <input type="text" class="form-control " name="CURP" value="<?=$data[0]['CURP']?>" placeholder="CURP">
    </div>
    <div class="form-group">
        <label>Dirección</label>
        <textarea class="form-control require" name="Direccion" placeholder="Dirección"><?=$data[0]['Direccion']?></textarea>
    </div>
    <div class="form-group">
        <label>Teléfono</label>
        <input type="text" class="form-control require" name="Telefono" value="<?=$data[0]['Telefono']?>" placeholder="Teléfono">
    </div>
    <? if($context->ver!=1){?>
    
    <input type="hidden" name="id" id="id" value="<?=$context->id?>">
    <p><a class="btn btn-success btn-lg" id ="btnSave"><i class="fa fa-save"></i> Guardar</a></p>
    <? }?>
</form>