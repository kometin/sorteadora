<script>
    $(function(){
       
        errorMail=0;
        $('#btnSaveCta').click(function(){
           error=0;
           var correos= ['']; 
           mensaje='';
           if(Full($('#cta'))) {
               LoadButton($(this)); 
                if(error==0){ 
                    $.post('clientes.php?action=savecta', $('#cta').serialize(), function(data){
                        Ready();
                        Loading();
                        if(data)
                             Error(data);
                        else{
                            CloseModal();
                          //  ReloadGrid(grid, 'data/loadClientes.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));
                            OK("Guardado");                                        
                        }                      
                    });                     
                }
            }
        });
        $('#btnNewCta').click(function(){
            
           table='<tr>';
            table+='<td><input type="text" name="Banco[]" class="form-control require" placeholder="Banco" id="Banco" value="<?=$row['Banco']?>"></td> '; 
            table+='<td><input type="text" name="Cuenta[]" class="form-control require" placeholder="Cuenta" id="Cuenta" value="<?=$row['Cuenta']?>"></td>';  
            table+='<td><input type="text" name="Moneda[]" class="form-control require " placeholder="Moneda" id="Moneda" value="<?=$row['Moneda']?>"></td>';  
            table+='<td><input type="text" name="Referencia[]" class="form-control require " placeholder="Referencia" id="Referencia" value="<?=$row['Referencia']?>"></td>';  
            table+='<td><input type="text" name="Representante[]" class="form-control require " placeholder="Representante" id="Representante" value="<?=$row['Representante']?>"></td>';  
   
            table+='    <td><input type="hidden" name="id[]" id="id" value=""><i class="fa fa-2x fa-trash-o" onclick="$(this).parent().parent().remove();"></i></td>';
            table+='</tr>'; 
            $("#ctatbl").append(table);

        });

       
    });


function DelCta(id){

    Question( "¿Desea dar de baja esta cuenta?", function(){
        Loading();
        $.get('clientes.php?action=delcta&id=' + id, function (data) {
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
<?
$data=$context->data;
?>
    <a class="btn btn-primary" id ="btnNewCta"><i class="fa fa-plus"></i> Nuevo cuenta</a>
    </br>
<form id="cta" >
    <table class="table table-striped" id="ctatbl">
        <tr>
            <th>Banco</th>
            <th>Cuenta</th>
            <th>Moneda</th>
            <th>Referencia</th>
            <th>Representante</th>
            <th>Borrar</th>
        </tr>
        <? foreach($context->cuentas as $row){?>
        <tr id="tr<?=$row['id']?>">
            <td><input type="text" name="Banco[]" class="form-control require" placeholder="Banco" id="Banco" value="<?=$row['Banco']?>"></td>  
            <td><input type="text" name="Cuenta[]" class="form-control require" placeholder="Cuenta" id="Cuenta" value="<?=$row['Cuenta']?>"></td>  
            <td><input type="text" name="Moneda[]" class="form-control require " placeholder="Moneda" id="Moneda" value="<?=$row['Moneda']?>"></td>  
            <td><input type="text" name="Referencia[]" class="form-control require " placeholder="Referencia" id="Referencia" value="<?=$row['Referencia']?>"></td>  
            <td><input type="text" name="Representante[]" class="form-control require " placeholder="Representante" id="Representante" value="<?=$row['Representante']?>"></td>  
            <td>
                <i class="fa fa-2x fa-trash-o" onclick="DelCta('<?=$row[id]?>')">
                    <input type="hidden" name="id[]" id="id" value="<?=$row['id']?>">
            </li></td>
        </tr>
        <? }?>
    </table>
    
    
    <input type="hidden" name="cid" id="cid" value="<?=$context->id?>">
    <p><a class="btn btn-success btn-lg" id ="btnSaveCta"><i class="fa fa-save"></i> Guardar</a></p>
    
</form>