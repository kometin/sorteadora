<script>
    $(function(){
        $('#Password').val('');
        $('#Password2').val('');        
        $('#btnSave').click(function(){
           if(Full($('#user'))) {
               if(ValidMail($('#mail').val()) ){               
                    if($('#Password').val()!=$('#Password2').val()){
                        Error("Las contraseñas no coinciden");
                     }else{  
                        $.get('usuarios.php?action=ckc&id='+$('#id').val()+'&cor=' +$('#mail').val() , function (e) {                                                 
                               if(e==''){
                                    LoadButton($(this));
                                    $.post('usuarios.php?action=save', $('#user').serialize(), function(data){
                                        Ready();
                                        if(data)
                                            Error(data);
                                        else{
                                          ReloadGrid(grid, 'data/loadUsuarios.php');
                                            OK("Guardado");
                                            CloseModal();
                                        }
                                    });
                               }else
                                  Error("El correo ya existe en otro usuario");  
                        
                        });                              
                     }
                }else
                     Error("El formato del correo es incorrecto");
           }
        });
    });
</script>
<?
$data=$context->data;
?>
<form id="user">
    <?/*
    <div class="form-group">
        <label>Tipo de usuario</label>
        <select class="form-control require" name ="rol">
            <option value="">Seleccione</option>
            <option value="ADMIN" <? if($data[0]['Rol']=='ADMIN')echo "selected";?>>Administrador</option>
            <option value="CLIENT" <? if($data[0]['Rol']=='ADMIN')echo "CLIENT";?>>Cliente</option>
        </select>
    </div>*/?>
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" class="form-control require" name="name" value="<?=$data[0]['Nombre']?>" placeholder="Nombre de usuario">
    </div>
    <div class="form-group">
        <label>Ap. Paterno</label>
        <input type="text" class="form-control require" name="patern" value="<?=$data[0]['Paterno']?>" placeholder="Apellido paterno">
    </div>
    <div class="form-group">
        <label>Ap. Materno</label>
        <input type="text" class="form-control " name="matern" value="<?=$data[0]['Materno']?>" placeholder="Apellido materno">
    </div>
    <div class="form-group">
        <label>Correo</label>
        <input type="text" class="form-control require  " id="mail" name="mail" value="<?=$data[0]['Correo']?>" placeholder="Correo electrónico">
    </div>
    <div class="form-group" <?=($context->id?"style='display:none'":"")?> id="pwd">
        <label>Contraseña</label>
        <input type="password" class="form-control require" name="Password" id="Password" value="" placeholder="Contraseña">
    </div>
    <div class="form-group" <?=($context->id?"style='display:none'":"")?>  id="pwd2">
        <label>Repetir contraseña</label>
        <input type="password" class="form-control require" name="Password2" id="Password2" value="" placeholder="Repetir contraseña">
    </div>    
    <input type="hidden" name="id" id="id" value="<?=$context->id?>">
    <p>
        <a class=" btn btn-info btn-lg" id="chg1"  style="display:<?=($context->id?"":"none")?>"  onclick="$('#chg1').hide(); $('#pwd').show();$('#pwd2').show();$( '#Password2' ).prop( 'disabled', false ); $( '#Password' ).prop( 'disabled', false ); " >Cambiar password</a>
 
    <a class="btn btn-success btn-lg" id ="btnSave"><i class="fa fa-save"></i> Guardar</a></p>
</form>