<script>
$(function(){
    var error=0;
    DoSelect($('.select2'));
    DatePicker($('.date'));

    $('#btnSave').click(function(){ 
        if(Full($('#op'))) {
            if(error==0){
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
            }else{
                Error("Es necesario corregir primero las cantidades");

            }
        }
    }); 
    var z=100;
    $('#btnNewRes').click(function(){
        z++;
        table='<tr id="tra">'; 
         table+='<td><input type="text" name="Lote[]" class="form-control require" placeholder="Lote"  value=""></td>';   
         table+='<td><input type="text" name="Fecha[]" class="form-control date require" style="width:110px" placeholder="Fecha"  value=""></td>'; 
         table+='<td><input type="text" onchange="calcula('+z+');" id="Cantidad'+z+'" name="Cantidad[]" class="TotalCol form-control numeric require" style="width:90px" placeholder="Cantidad"  value=""></td>'; 
         <? $z=0; foreach($context->Factores as $row){?>
            table+='<td>';
            table+='<input type="hidden" name="idFactor[]" value="">';
            table+='<input type="text" name="Factor[]" onchange="calcula('+z+');" id="" class="form-control numeric require Factor'+z+'" placeholder="Cantidad <?=$row['Factor']?>"  value="">'; 
            table+='</td>';
         <? $z++;}?>
         table+='<td><input type="text" name="Rechazadas[]"  id="Rechazadas'+z+'" readonly="" class="form-control numeric " placeholder="Rechazadas"  value=""></td>  '; 
         table+='<td><input type="text" name="Total[]" id="Total'+z+'" readonly="" class="form-control numeric " placeholder="Total"  value=""></td>  '; 
         table+=' <td>';  
         table+='<i class="fa fa-2x fa-trash-o" onclick="$(this).parent().parent().remove();"></i></td>'; 
         table+=' </tr>  ';            
         $("#tblress").append(table);
    DatePicker($('.date'));         
     });        
});

function DelFacRes(id){

    Question( "¿Desea eliminar este resultado?", function(){
        Loading();
        $.get('ordenes.php?action=DelFacRes1&id=' + id, function (data) {
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
  var Total_Partes=<?=$context->Total_Partes?>;
<? foreach($context->Factores as $row){?>
      facts.push("<?php echo $row['id'] ?>");
<? }?>
 function calcula(i){
    total=0;
    totalCol=0;
    $(".Factor"+i).each(function() {        
        if($(this).val()!=''){
            total=total+parseInt($(this).val());
        }
    });
    $(".TotalCol").each(function() {        
        if($(this).val()!=''){
            totalCol=totalCol+parseInt($(this).val());
        }
    });    
    if(($('#Cantidad'+i).val()-total)>=0){
        if(Total_Partes>=totalCol){
            $('#Rechazadas'+i).val(total);
            $('#Total'+i).val($('#Cantidad'+i).val()-total);
            $('#totalCan').val(totalCol);
            error=0;
        }else{
            Error('Esa cantidad excede el total de la orden: '+Total_Partes);
            $('#Total'+i).val('');
            $('#totalCan').val('');
            error=1;
        }
    }else{
        Error('Esa cantidad de defectos excede el total del lote:'+$('#Cantidad'+i).val());
        $('#Total'+i).val('');
        error=1;
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
    <table id="tblress" class="table table-striped">
        <tr>
            <td >Agrerar resultado</td>
            <td><a class="btn btn-primary" id ="btnNewRes"><i class="fa fa-plus"></i></a></td>
        </tr>        
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
        <? $TotalCan=0;
            if(count($context->Resultados )>0){
            foreach($context->Resultados as $row){
                $TotalCan+=$row['Cantidad']?>
        <tr id="tr<?=$row['id']?>">            
            <td><input type="hidden" name="id[]" id="id" value="<?=$row['id']?>">
                <input type="text" name="Lote[]" class="form-control require" placeholder="Lote"  value="<?=$row['Lote']?>"></td>  
            <td><input type="text" name="Fecha[]" class="form-control date require" style="width:110px" placeholder="Fecha"  value="<?= SimpleDate($row['Fecha_Lote'])?>"></td>            
            <td><input type="text" name="Cantidad[]" onchange="calcula(<?=$row['id']?>);" id="Cantidad<?=$row['id']?>" class="TotalCol form-control numeric require" style="width:90px" placeholder="Cantidad"  value="<?=$row['Cantidad']?>"></td>

            <? $totalRecha=0;
                foreach($context->Factores as $rowF){
                    if($context->ResultadosFac[$row['id']][$rowF['id']][0]!='')
                        $totalRecha=$totalRecha+$context->ResultadosFac[$row['id']][$rowF['id']][0]?>
            <td><input type="hidden" name="idFactor[]" value="<?=$context->idsFac[$row['id']][$rowF['id']]?>">
                <input type="text" name="Factor[]" onchange="calcula(<?=$row['id']?>);" class="form-control require Factor<?=$row['id']?>" placeholder="Cantidad <?=$rowF['Factor']?>"  value="<?= number_format($context->ResultadosFac[$row['id']][$rowF['id']][0], 1)?>"></td>
            <? }?>
            <td><input type="text" name="Rechazadas[]" id="Rechazadas<?=$row['id']?>" readonly="" class="form-control " placeholder="Rechazadas"  value="<?=$totalRecha?>"></td>  
            <td><input type="text" name="Total[]" id="Total<?=$row['id']?>" readonly="" class="form-control require" placeholder="Total"  value="<?=($row['Cantidad']-$totalRecha)?>"></td>  

            <td>
                <i class="fa fa-2x fa-trash-o" onclick="DelFacRes('<?=$row['id']?>')"></li>
            </td>
        </tr>
        <? }}else{?>
        <tr id="tra">
            <td><input type="text" name="Lote[]" class="form-control require" placeholder="Lote"  value=""></td>  
            <td><input type="text" name="Fecha[]" class="form-control date require" style="width:110px" placeholder="Fecha"  value="<?=$row['Fecha']?>"></td>
            <td><input type="text" onchange="calcula(0);" id="Cantidad0" name="Cantidad[]" class="TotalCol form-control numeric require" style="width:90px" placeholder="Cantidad"  value="<?=$row['Cantidad']?>"></td>
            <? $z=0; foreach($context->Factores as $row){?>
            <td>
                <input type="hidden" name="idFactor[]" value="<?=$row['id']?>">
                <input type="text" name="Factor[]" onchange="calcula(0);" id="" class="form-control numeric require Factor0" placeholder="Cantidad <?=$row['Factor']?>"  value="<?=$row['Respuesta']?>">
            </td>
            <? $z++;}?>
            <td><input type="text" name="Rechazadas[]"  id="Rechazadas0" readonly="" class="form-control numeric " placeholder="Rechazadas"  value=""></td>  
            <td><input type="text" name="Total[]" id="Total0" readonly="" class="form-control numeric " placeholder="Total"  value=""></td>  
            <td>             </td>
        </tr>        
        <? }?>
    </table>
    <table  class="table table-striped">
        <tr>
            <td style="width:145px "></td><td  style="width:125px " align="right">Total</td><td  style="width:125px "><input  style="width:90px " id="totalCan" class="form-control" type="text" value="<?=$TotalCan?>" readonly=""></td><td></td><td></td><td></td><td></td><td></td>
        </tr> 
    </table>
    <input type="hidden" name="idOrden" id="idOrden" value="<?=$context->idOrden?>">
    <br></br>
    <p>
        <a class="btn btn-success btn-lg" id ="btnSave"><i class="fa fa-save"></i> Guardar</a>
    </p>
</form>