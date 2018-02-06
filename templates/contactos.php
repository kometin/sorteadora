<script>
    $(function(){
        error=0;
        $('#btnSaveContact').click(function(){
           if(Full($('#contact'))) {
               LoadButton($(this)); 
               Loading();
               $(".mail").each(function() {
                    if(ValidMail($(this).val())==false ){
                        error=1;   
                       Error("El formato del correo es incorrecto");
                       Ready();
                    } 
                });
                if(error==0){
                    $('#contact').find(':checkbox:not(:checked)').attr('value', '0').prop('checked', true);                        
                    $.post('clientes.php?action=savecontact', $('#contact').serialize(), function(data){
                        Ready();
                         if(data)
                             Error(data);
                         else{
                             ReloadGrid(grid, 'data/loadClientes.php');
                             OK("Guardado");
                             CloseModal();
                         }                      
                    });           
                }

           }
        });
        $('#btnNewContact').click(function(){
            
           table='<tr>';
            table+='<td>'; 
            table+='      <select  name="Tipo[]" class="form-control" id="Tipo">';
            table+='            <option value="uno">uno</option>';
            table+='            <option value="dos">dos</option>';
            table+='        </select>';
            table+='    </td>';
            table+='    <td><input type="text" name="Nombre[]" class="form-control require" id="Nombre" value=""></td>';  
            table+='    <td><input type="text" name="Correo[]" class="form-control require mail" id="Correo" value=""></td>';
            table+='    <td><input type="password" name="Password[]" class="form-control" id="Password"></td>';  
            table+='    <td><input type="checkbox" name="Principal[]" id="Principal" class="form-control" value="1"></td>';
            table+='    <td><input type="hidden" name="id[]" id="id" value=""><i class="fa fa-2x fa-trash-o" onclick="$(this).parent().parent().remove();"></i></td>';
            table+='</tr>'; 
            $("#contactostbl").append(table);

        });
        
    });
        function DelContacto(id){

        Question( "¿Desea dar de baja este contacto?", function(){
           Loading();
        $.get('clientes.php?action=delc&id=' + id, function (data) {
              Ready();
              if(data)
                  Error(data);
              else{
                $('#tr'+id).remove();
                OK("Borrado");

                }
           });
        });
    } 
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
    <a class="btn btn-primary" id ="btnNewContact"><i class="fa fa-plus"></i> Nuevo contacto</a>
<form id="contact" >
    <table class="table table-striped" id="contactostbl">
        <tr>
            <th>Tipo</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Password</th>
            <th>Principal</th>
            <th>Borar</th>
        </tr>
        <? foreach($context->contactos as $row){?>
        <tr id="tr<?=$row['id']?>">
            <td>
                <select  name="Tipo[]" class="form-control" id="Tipo">
                    <option value="uno">uno</option>
                    <option value="dos">dos</option>
                </select>
            </td>
            <td><input type="text" name="Nombre[]" class="form-control require" id="Nombre" value="<?=$row['Nombre']?>"></td>  
            <td><input type="text" name="Correo[]" class="form-control require mail" id="Correo" value="<?=$row['Correo']?>"></td>  
            <td><input type="password" name="Password[]" class="form-control" id="Password"></td>  
            <td>
                <input type="hidden" value="">
                <input type="checkbox" name="Principal[]" id="Principal" class="form-control" value="1" <? if($row['Principal']==1)echo "checked";?>>
            
            </td>
            <td>
                <i class="fa fa-2x fa-trash-o" onclick="DelContacto('<?=$row[id]?>')">
                    <input type="hidden" name="id[]" id="id" value="<?=$row['id']?>">
            </li></td>
        </tr>
        <? }?>
    </table>
    
    
    <input type="hidden" name="cid" id="cid" value="<?=$context->id?>">
    <p><a class="btn btn-success btn-lg" id ="btnSaveContact"><i class="fa fa-save"></i> Guardar</a></p>
    
</form>