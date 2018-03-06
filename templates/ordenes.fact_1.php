<script>
$(function(){
    DoSelect($('.select2'));
    DatePicker($('.date'));

    $('#btnSave').click(function(){ 
        if(Full($('#op'))) {
            LoadButton($('#btnSave'));                                                                              
            $.post('ordenes.php?action=savefact', $('#op').serialize(), function(data){
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
    $('#btnNewFac').click(function(){
            
           table='<tr>';
            table+='<td><input type="text" name="Factor[]" class="form-control require" placeholder="Descripción del factor"  value="">';
            table+='<td><input type="hidden" name="id[]" id="id" value=""><i class="fa fa-2x fa-trash-o" onclick="$(this).parent().parent().remove();"></i></td>';
            table+='</tr>'; 
            $("#tblfactores").append(table);
        });   
});
function DelFac(id){

    Question( "¿Desea eliminar este factor?", function(){
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
            <td >Factor</td>
            <td><a class="btn btn-primary" id ="btnNewFac"><i class="fa fa-plus"></i></a></td>
        </tr>
        <? if(count($context->Factores )>0){
            foreach($context->Factores as $row){?>
        <tr id="tr<?=$row['id']?>">
            <td><input type="text" name="Factor[]" class="form-control require" placeholder="Descripción del factor"  value="<?=$row['Factor']?>"></td>  
            <td>
                <i class="fa fa-2x fa-trash-o" onclick="DelFac('<?=$row[id]?>')">
                    <input type="hidden" name="id[]" id="id" value="<?=$row['id']?>">
                </li>
            </td>
        </tr>
        <? }}else{?>
        <tr id="tra">
            <td><input type="text" name="Factor[]" class="form-control require" placeholder="Descripción del factor"  value=""></td>  
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