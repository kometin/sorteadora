<script>
    $(function(){
        $('#Password').val('');
        $('#Password2').val('');    
        $('#btnSave').click(function(){
           
           if(Full($('#op'))) {
               LoadButton($('#btnSave')); 
                if($('#RFC').val()=='' || validateRFC13($('#RFC').val()) ){                    
                     $.get('clientes.php?action=ckr&id='+$('#id').val()+'&rfc=' +$('#RFC').val() , function (e) {                        
                         if(e==''){                                    
                             $.post('clientes.php?action=save', $('#op').serialize(), function(data){
                                Ready();
                                if(data)
                                    Error(data);
                                else{
                                    OK("Guardado");
                                    Loading();
                                    ReloadGrid(grid, 'data/loadClientes.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));                                    
                                    CloseModal();
                                }                                       
                             });
                         }else{
                              Error(e);        
                              Ready();
                         }
                     });
                 }else{
                     Error("El formato del RFC es incorrecto");                            
                     Ready();
                 }
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
        <input type="text" class="form-control require" name="Empresa" value="<?=$data[0]['Empresa']?>" placeholder="Empresa">
    </div>
    <div class="form-group">
        <label>RFC</label>
        <input type="text" class="form-control require" name="RFC" id="RFC" value="<?=$data[0]['RFC']?>" placeholder="RFC">
    </div>

    <div class="form-group">
        <label>Dirección</label>
        <textarea class="form-control " name="Direccion" placeholder="Dirección"><?=$data[0]['Direccion']?></textarea> 
    </div>
         
    <div class="form-group">
        <label>Teléfono</label>
        <input type="text" class="form-control " name="Telefono" value="<?=$data[0]['Telefono']?>" placeholder="Teléfono">
    </div> 
    <? /*
    <div class="form-group">
        <label>Correo</label>
        <input type="text" class="form-control require" name="Correo" id="Correo" value="<?=$data[0]['Correo']?>" placeholder="Correo">
    </div>
    <div class="form-group" <?=($context->id?"style='display:none'":"")?> id="pwd">
        <label>Contraseña</label>
        <input type="password" autocomplete="Off" class="form-control require" name="Password" id="Password" value="" placeholder="Contraseña">
    </div>
    <div class="form-group" <?=($context->id?"style='display:none'":"")?>  id="pwd2">
        <label>Repetir contraseña</label>
        <input type="password" class="form-control require" name="Password2" id="Password2" value="" placeholder="Repetir contraseña">
    </div> 
     
     */?>   
    <input type="hidden" name="id" id="id" value="<?=$context->id?>">
    <p>
        <a class="btn btn-success btn-lg" id ="btnSave"><i class="fa fa-save"></i> Guardar</a>
    </p>
</form>