<script>
$(function(){
    DoSelect($('.select2'));
    DatePicker($('.date'));

    $('#btnSave').click(function(){ 
        if(Full($('#op'))) {
            LoadButton($('#btnSave'));                                                                              
            $.post('ordenes.php?action=savefact2', $('#op').serialize(), function(data){
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
    var c=0;
    $('#btnNewFac').click(function(){
            
            table='<tr>';
            table+='<td><input type="text" name="Factor[]" class="form-control require" placeholder="Característica"  value=""></td>';  
            table+='<td><select name="Regla[]" onchange="validaRegla('+c+',$(this).val())"  class="form-control require">';
            table+='      <option></option>';
            table+='             <option value="Mayor">Mayor</option>';
            table+='              <option value="Menor">Menor</option>';
            table+='              <option value="Rango">Rango</option>';
            table+='  </select></td>';
            table+=' <td><input type="text" name="Especificacion[]" class="form-control require numeric" placeholder="Especificación"  value=""></td>';  
            table+=' <td><input type="text" name="Tolerancia[]" id="Tolerancia'+c+'" readonly class="form-control require numeric" placeholder="Tolerencia"  value=""></td>';
            table+='<td><input type="hidden" name="id[]" id="id" value=""><i class="fa fa-2x fa-trash-o" onclick="$(this).parent().parent().remove();"></i></td>';
            table+='</tr>'; 
            $("#tblfactores").append(table);
        });   
    c++;    
});
function DelFac(id){

    Question( "¿Desea eliminar esta característica?", function(){
        Loading();
        $.get('ordenes.php?action=delfac&id=' + id, function (data) {
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
function validaRegla(id, val){
    if(val=='Rango'){
        $('#Tolerancia'+id).prop('readonly', false);
        $('#Tolerancia'+id).addClass('require'); 
    }else{
        $('#Tolerancia'+id).prop('readonly', true); 
        $('#Tolerancia'+id).val(''); 
        $('#Tolerancia'+id).removeClass('require'); 
    }
    
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
<form id="op"  autocomplete="off">
    <table id="tblfactores" class="table table-striped">
        <tr>
            <td >Característica</td>
            <td >Regla</td>
            <td>Especificación</td>
             <td>Tolerancia</td>
            <td><a class="btn btn-primary" id ="btnNewFac"><i class="fa fa-plus"></i></a></td>
        </tr>
        <? if(count($context->Factores )>0){
            foreach($context->Factores as $row){?>
        <tr id="tr<?=$row['id']?>">
            <td><input type="text" name="Factor[]" class="form-control require" placeholder="Característica"  value="<?=$row['Factor']?>"></td>  
            <td>
                <select name="Regla[]" id="Regla<?= $row['id'] ?>" onchange="validaRegla(<?=$row['id']?>,$(this).val())" class="form-control require">
                  <option></option>
                <option value="Mayor" <? if($row['Regla']=='Mayor') echo "SELECTED";?>>Mayor</option>
                <option value="Menor" <? if($row['Regla']=='Menor') echo "SELECTED";?>>Menor</option>
                <option value="Rango" <? if($row['Regla']=='Rango') echo "SELECTED";?>>Rango</option>
              </select>            
            </td>  
            <td><input type="text" name="Especificacion[]" class="form-control require numeric" placeholder="Especificación"  value="<?=$row['Especificacion']?>"></td>  
            <td><input type="text" name="Tolerancia[]" <? if($row['Regla']!='Rango')echo "readonly";?> id="Tolerancia<?= $row['id'] ?>" class="form-control  <? if($row['Regla']=='Rango')echo "require";?> numeric" placeholder="Tolerencia"  value="<?=$row['Tolerancia']?>"></td>  
            <td>
                <i class="fa fa-2x fa-trash-o" onclick="DelFac('<?=$row[id]?>')">
                    <input type="hidden" name="id[]" id="id" value="<?=$row['id']?>">
                </li>
            </td>
        </tr>
        <? }}else{?>
        <tr id="tra">
          <td><input type="text" name="Factor[]" class="form-control require" placeholder="Característica"  value=""></td>  
          <td><select name="Regla[]" id="Regla0" class="form-control require">
                  <option></option>
                          <option value="Mayor">Mayor</option>
                          <option value="Menor">Menor</option>
                          <option value="Rango">Rango</option>
              </select>
            </td>  
           <td><input type="text" name="Especificacion[]" class="form-control require numeric" placeholder="Especificación"  value=""></td>  
            <td><input type="text" name="Tolerancia[]" id="Tolerancia0" class="form-control require numeric" placeholder="Tolerencia"  value=""></td>  
              
            <td> <input type="hidden" name="id[]" id="id" value="">
            </td>
        </tr>        
        <? }?>
    </table>
    <input type="hidden" name="idOrden" id="idOrden" value="<?=$context->idOrden?>">
    <br></br>
    <p>
        <a class="btn btn-success btn-lg" id ="btnSave"><i class="fa fa-save"></i> Guardar</a>
    </p>
</form>