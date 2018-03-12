<script>
$(function(){
    var error=0;
    DoSelect($('.select2'));
    DatePicker($('.date'));
    $( ".accordion" ).accordion({
        heightStyle: "content",
              collapsible: true

        

    });

    $('#btnSave').click(function(){ 
        if(Full($('#op'))) {
            if(error==0){
                LoadButton($('#btnSave'));                                                                              
                $.post('ordenes.php?action=savefacres2', $('#op').serialize(), function(data){
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
    if(($('#Cantidad'+i).val()-total)>0){
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
 function agregaRenglones(id,max){

    <? foreach($context->Factores as $row){?>
        table='';
        for(z=0;z<max;z++){
            table+='<tr>'; 
            table+='<td>';
            table+='<input type="text" name="Resultados<?=$row['id']?>[]"  id="Resultados"  class="form-control numeric " placeholder="Resultado"  value="">'; 
            table+='</td>';
            table+='</tr>'; 
        }            
        $("#tbresultados"+<?=$row['id']?>).append(table);
    <? }?>
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
            <td>Muestra</td>
            <td>Caracteristica / Especificación </td>            
        </tr>
        <? $TotalCan=0;
            if(count($context->Resultados )>0){
            foreach($context->Resultados as $row){
                $TotalCan+=$row['Cantidad']?>
        <tr id="tr<?=$row['id']?>">            
            <td><input type="hidden" name="id[]" id="id" value="<?=$row['id']?>">
                <input type="text" name="Lote[]" class="form-control require" placeholder="Lote"  value="<?=$row['Lote']?>"></td>  
            <td><input type="text" name="Fecha[]" class="form-control date require" style="width:110px" placeholder="Fecha"  value="<?= SimpleDate($row['Fecha_Lote'])?>"></td>            
            <td><input type="text" name="Muestra[]" id="Muestra<?=$row['id']?>" class=" form-control numeric require" style="width:90px" placeholder="Cantidad"  value="<?=$row['Muestra']?>"></td>          
            <td>
                <table  class="table table-striped">
                    <tr>
                        <td>
                        <div class="accordion">
                        <? $z=0; foreach($context->Factores as $fac){?>                                                        
                            <h3><input type="hidden" name="idFactor[]" value="<?=$fac['id']?>">
                                <?=$fac['Factor']?><?=$fac['Especificacion']?>
                            </h3>
                            <div>
                               <table id="tbresultados<?=$row['id'].$fac['id']?>" class="table table-striped">
                                   <? for($z=0;$z<$row['Muestra']; $z++){?>
                                   <tr>
                                       <td>
                                           <input type="text" name="Resultados<?=$fac['id']?>[]"  class="form-control numeric " placeholder="Resultado"  value="<?=$context->ResultadosFac[$row['id']][$fac['id']][$z]?>">
                                       </td>
                                   </tr>
                                   <? }?>
                               </table>
                            </div>
                        <? $z++;}?>
                        </div>                            
                        </td>                       
                    </tr>   
                </table>
            </td>
        </tr>
        <? }}else{?>
        <tr id="tra">
            <td><input type="text" name="Lote[]" class="form-control require" placeholder="Lote"  value=""></td>  
            <td><input type="text" name="Fecha[]" class="form-control date require" style="width:110px" placeholder="Fecha"  value="<?=$row['Fecha']?>"></td>
            <td>
                <input type="text" onchange="" id="Muestra0" name="Muestra[]" class="TotalCol form-control numeric require" style="width:90px" placeholder="Muestra"  value="<?=$row['Muestra']?>">
                <i class="fa fa-share" onclick="agregaRenglones(0,$('#Muestra0').val())"></i>
            </td>
            <td>
                <table  class="table table-striped">
                    <tr><td>
                    <div class="accordion">
                    <? $z=0; foreach($context->Factores as $row){?>

                                                        
                                <h3><input type="hidden" name="idFactor[]" value="<?=$row['id']?>">
                                    <?=$row['Factor']?><?=$row['Especificacion']?>
                                </h3>
                            <div>
                               <table id="tbresultados<?=$row['id']?>" class="table table-striped"></table>
                            </div>

                    <? $z++;}?>
                    </div>                            
                        </td>                       
                    </tr>   
                </table>
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