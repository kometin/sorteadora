<script>
    $(function(){
       
        errorMail=0;
        $('#btnSaveContact').click(function(){
           error=0;
           var correos= ['']; 
           mensaje='';
           if(Full($('#contact'))) {
               LoadButton($(this)); 
               //Loading();
               $(".mail").each(function() {
                    if(ValidMail($(this).val())==false ){
                       error=1;   
                       Error("El formato del correo es incorrecto");
                       Ready();
                    }               
                });
                if(errorMail==1){
                    Error("El/los correos "+mensaje+" ya estan dados de alta en otro cliente");                                   
                    Ready(); 
                }
                if(error==0){
                    if(  $('.checado:checked').length  > 0){
                        $.post('clientes.php?action=checacontacto', $('#contact').serialize(), function(e){
                            if(e!='' ){                             
                                Error(e);   
                                Ready();
                            }else{
                                $('#contact').find(':checkbox:not(:checked)').attr('value', '0').prop('checked', true);                        
                                $.post('clientes.php?action=savecontact', $('#contact').serialize(), function(data){
                                    Ready();
                                    Loading();
                                    if(data)
                                         Error(data);
                                    else{
//                                        CloseModal();
//                                        ReloadGrid(grid, 'data/loadClientes.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));
                                        OK("Guardado");                                        
                                        Modal('clientes.php?action=mail&id=<?=$context->id?>', 'Envio de correos', 900);
                                    }                      
                                });      
                            }
                        });
                    }else{
                       Error("Debe de haber almenos un contacto principal");   
                        Ready(); 
                    }
                }
           }
        });
        $('#btnNewContact').click(function(){
            
           table='<tr>';
            table+='<td>'; 
            table+='      <select  name="Tipo[]" class="form-control" id="Tipo">';
            table+='            <option value="Financial">Financial</option>';
            table+='            <option value="Quality">Quality</option>';
            table+='        </select>';
            table+='    </td>';
            table+='    <td><input type="text" name="Nombre[]" class="form-control require" id="Nombre" value=""></td>';  
            table+='    <td><input type="text" name="Correo[]" class="form-control require mail" id="Correo" value=""></td>';
            table+='    <td><a class = "btn btn-danger btn-access">NO</a><input type ="hidden" name = "access[]" value="-1"></td>'
//            table+='    <td><input type="password" name="Password[]" class="form-control" id="Password"></td>';  
            table+='    <td align="center" style="text-align:center !important;"><center><input type="checkbox" name="Principal[]"  id="Principal" class="checado" value="1"></center></td>';
            table+='    <td><input type="hidden" name="id[]" id="id" value=""><i class="fa fa-2x fa-trash-o" onclick="$(this).parent().parent().remove();"></i></td>';
            table+='</tr>'; 
            $("#contactostbl").append(table);
     $('.checado').on('change', function() {
        $('.checado').not(this).prop('checked', false);  
        $(this).prop("checked", true); 
    });


        });
     $('.checado').on('change', function() {
        $('.checado').not(this).prop('checked', false);  
        $(this).prop("checked", true); 
    });
    
        $('#contact').on('click', '.btn-access', function(){
           if($(this).hasClass('btn-success')){
               $(this).removeClass('btn-success').addClass('btn-danger').text('NO');
               $(this).parent().find('input').val("-1");
           }else{
               $(this).removeClass('btn-danger').addClass('btn-success').text('SI');
               $(this).parent().find('input').val("1");
           }
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
    </br>
<form id="contact" >
    <table class="table table-striped" id="contactostbl">
        <tr>
            <th>Tipo</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Acceso</th>
            <th>Principal</th>
            <th>Borrar</th>
        </tr>
        <? foreach($context->contactos as $row){?>
        <tr id="tr<?=$row['id']?>">
            <td>
                <select  name="Tipo[]" class="form-control" id="Tipo">
                    <option value="Financial" <? if($row['Tipo']=='Finantial')echo "selected";?>>Financial</option>
                    <option value="Quality" <? if($row['Tipo']=='Quality')echo "selected";?>>Quality</option>
                </select>
            </td>
            <td><input type="text" name="Nombre[]" class="form-control require" id="Nombre" value="<?=$row['Nombre']?>"></td>  
            <td><input type="text" name="Correo[]" class="form-control require mail" id="Correo" value="<?=$row['Correo']?>"></td>  
<!--            <td>
                <a class=" btn btn-info btn-lg" id="chg1"  style="display:<?=($row['id']?"":"none")?>"  onclick="$(this).hide();$('#Password<?=$row['id']?>').val(''); $('#Password<?=$row['id']?>').show(); " >Cambiar password</a>
                <input type="password" name="Password[]" style="display:none" class="form-control" id="Password<?=$row['id']?>" value='0'>
            </td>  -->
            <td width = "50">
                <a class="btn btn-<?=($row[Password] && $row[Password] != "NEW"?"success":"danger")?> btn-access"><?=($row[Password] && $row[Password] != "NEW"?"SI":"NO")?></a>
                <input type="hidden" name="access[]" value="<?=($row[Password] && $row[Password] != "NEW" ? "0" : "-1")?>">
            </td>
            <td  align="center">
                <input type="hidden" value="">
                <input type="checkbox" name="Principal[]" id="Principal" class="checado" value="1" <? if($row['Principal']==1)echo "checked";?>>
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