<script>
$(function(){
    DoSelect($('.select2'));
    DatePicker($('.date'));

    $('#btnSave').click(function(){ 
        if(Full($('#op'))) {
            LoadButton($('#btnSave'));                                                                              
            $.post('ordenes.php?action=saveres3', $('#op').serialize(), function(data){
                Ready();
                if(data)
                    Error(data);
                else{
                    ReloadGrid(grid, 'data/loadOrdenes.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));
                    OK("Guardado");
                    CloseModal();
                    Loading();
                }                                       
            });                    
        }
    });
    $('#btnNewFac').click(function(){
         table='<tr id="tra">';
            table+='<td><input type="text" name="Locacion[]" class="form-control require" placeholder="Locacion"  value=""></td>';  
            table+='<td><input type="text" name="Dia[]" class="form-control require numeric" style="width:100px" placeholder="Día"  value=""></td>';
            table+='<td>';
                table+='<input type="hidden" name="id[]" id="id" value="">';
                table+='<textarea id="Informacion" name="Informacion[]" placeholder="Información" class="require form-control"></textarea>';
            table+='</td><td><i class="fa fa-2x fa-trash-o" onclick="$(this).parent().parent().remove();"></i></td>';
        table+='</tr>';             
            $("#tblfactores").append(table);
        });   
});
function DelRes3(id){

    Question( "¿Desea eliminar este defecto?", function(){
        Loading();
        $.get('ordenes.php?action=delres3&id=' + id, function (data) {
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
<form id="op"  autocomplete="off">
    <table id="tblfactores" class="table table-striped">
        <tr>
            <td >Locación</td>
            <td >Día</td>
            <td >Información general</td>
            <td><a class="btn btn-primary" id ="btnNewFac"><i class="fa fa-plus"></i></a></td>
        </tr>
        <? if(count($context->Resultados )>0){
            foreach($context->Resultados as $row){?>
        <tr id="tr<?=$row['id']?>">
            <td><input type="text" name="Locacion[]" class="form-control require" placeholder="Locacion" value="<?=$row['Locacion']?>"></td>  
            <td><input type="text" name="Dia[]" class="form-control require numeric" style="width:100px" placeholder="Día"  value="<?=$row['Dia']?>"></td>  
            <td>
                <textarea id="Informacion" name="Informacion[]" placeholder="Información" class="require form-control"><?=$row['Informacion']?></textarea>
            </td>
            <td>
                <i class="fa fa-2x fa-trash-o" onclick="DelRes3('<?=$row[id]?>')">
                    <input type="hidden" name="id[]" id="id" value="<?=$row['id']?>">
                </li>
            </td>
        </tr>
        <? }}else{?>
        <tr id="tra">
            <td><input type="text" name="Locacion[]" class="form-control require" placeholder="Locacion"  value=""></td>  
            <td><input type="text" name="Dia[]" class="form-control require numeric" style="width:100px" placeholder="Día"  value=""></td>
            <td>
                <input type="hidden" name="id[]" id="id" value="">
                <textarea id="Informacion" name="Informacion[]" placeholder="Información" class="require form-control"></textarea>
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