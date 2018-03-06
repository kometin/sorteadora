<script>
$(function(){
    DoSelect($('.select2'));
    DatePicker($('.date'));

    $('#btnSave').click(function(){ 
        if(Full($('#op'))) {
            LoadButton($('#btnSave'));                                                                              
            $.post('ordenes.php?action=savefacres', $('#op').serialize(), function(data){
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
}      $("#tblfactores").append(table);
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
  var facts = new Array();
<? foreach($context->Factores as $row){?>
      facts.push("<?php echo $row['id'] ?>");
<? }?>
 function calcula(i){
    total=0;
    $(".Factor"+i).each(function() {
        if($(this).val()!='')
            total+=$(this).val();
    });
    if(($('#Cantidad'+i).val()-total)>0){
       $('#Total'+i).val(total);
            alert(total);
    }
   else
       alert('Se paso');
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
            <td>Lote</td>
            <td>Fecha</td>
            <td>Cantidad</td>
            <?foreach($context->Factores as $row){?>
            <td>Defecto (<?=$row['Factor']?>)</td>
            <? }?>
            <td>Rechazadas</td>
            <td>Total OK</td>            
        </tr>
        <? if(count($context->Resultados )>0){
            foreach($context->Resultados as $row){?>
        <tr id="tr<?=$row['id']?>">            
            <td><input type="hidden" name="id[]" id="id" value="<?=$row['id']?>">
                <input type="text" name="Lote[]" class="form-control require" placeholder="Lote"  value="<?=$row['Lote']?>"></td>  
            <td><input type="text" name="Fecha[]" class="form-control date require" style="width:110px" placeholder="Fecha"  value="<?= SimpleDate($row['Fecha_Lote'])?>"></td>            
            <td><input type="text" name="Cantidad[]" class="form-control numeric require" style="width:90px" placeholder="Cantidad"  value="<?=$row['Cantidad']?>"></td>

            <? $totalRecha=0;
                foreach($context->Factores as $rowF){
                $totalRecha+=$context->ResultadosFac[$rowF['id']]?>
            <td><input type="hidden" name="idFactor[]" id="idFactor" value="<?=$idsFac[$rowF['id']]?>">
                <input type="text" name="Factor[]" class="form-control require" placeholder="Cantidad factor"  value="<?=$context->ResultadosFac[$rowF['id']]?>"></td>
            <? }?>
            <td><input type="text" name="Rechazadas[]" readonly="" class="form-control " placeholder="Rechazadas"  value="<?=$totalRecha?>"></td>  
            <td><input type="text" name="Total[]" readonly="" class="form-control " placeholder="Total"  value="<?=($row['Cantidad']-$totalRecha)?>"></td>  

            <td>
                <i class="fa fa-2x fa-trash-o" onclick="DelFacRes('<?=$row[idFactor]?>')">
                    
                </li>
            </td>
        </tr>
        <? }}else{?>
        <tr id="tra">
            <td><input type="text" name="Lote[]" class="form-control require" placeholder="Lote"  value=""></td>  
            <td><input type="text" name="Fecha[]" class="form-control date require" style="width:110px" placeholder="Fecha"  value="<?=$row['Fecha']?>"></td>
            <td><input type="text" onchange="calcula(0,<?=count($context->Factores)?>);" id="Cantidad0" name="Cantidad[]" class="form-control numeric require" style="width:90px" placeholder="Cantidad"  value="<?=$row['Cantidad']?>"></td>
            <? $z=0; foreach($context->Factores as $row){?>
            <td>
                <input type="hidden" name="idFactor[]" value="<?=$row['id']?>">
                <input type="text" name="Factor[]" id="" class="form-control numeric require Factor0" placeholder="Cantidad factor"  value="<?=$row['Respuesta']?>">
            </td>
            <? $z++;}?>
            <td><input type="text" name="Rechazadas[]" id="Rechazadas0" readonly="" class="form-control numeric " placeholder="Rechazadas"  value=""></td>  
            <td><input type="text" name="Total[]" id="Total0" readonly="" class="form-control numeric " placeholder="Total"  value=""></td>  
            <td> 
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