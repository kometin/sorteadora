<?
    $data=$context->data;
?>
<script>
$(function(){
    var sinMuestra=0;
    var error=0;
    <?  if(count($context->Resultados )>0){?>
        haymuestras=1;
    <? }else{?>
        haymuestras=0;
    <?}?>
    DoSelect($('.select2'));
    DatePicker($('.date'));
    $( ".accordion" ).accordion({
        heightStyle: "content",
              collapsible: true

        

    });

    $('#btnSave').click(function(){ 
        if(Full($('#op'), true)) {
            if(haymuestras==0){
                 Error("Es necesario indicar la cantidad de la muestra y dar clic en el Ã­cono");
            }else{
                var summaMuestras=0;
                totalOrden=<?=$context->Total_Partes?>;
                $(".totales").each(function() { 
                    if($(this).val()!=''){
                        summaMuestras=summaMuestras+parseInt($(this).val());
                    }
                });                    
                if(summaMuestras>totalOrden){
                    Error('La suma de las muestras '+summaMuestras+' no puede ser mayor al total de la orden '+totalOrden);                    
                }else{
                    if(error==0){
                        LoadButton($('#btnSave'));                                                                              
                        $.post('ordenes.php?action=savefacres2', $('#op').serialize(), function(data){
                            Ready();
                            if(data)
                                Error(data);
                            else{
                                OK("Guardado");
                                CloseModal();
                                Loading();
                                ReloadGrid(grid, 'data/loadOrdenes.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));                                                                
                            }                                       
                        });
                    }else{
                        Error("Es necesario corregir primero las cantidades");

                    }
                }
            }
        }else{
            Error("Faltan campos por llenar");
            
            
        }
    }); 
    var z=100;
    $('#btnNewRes').click(function(){
         haymuestras=0;
        z++;
        table='';
        table+='<tr id="tra'+z+'">'; 
        table+='    <td><input type="text" name="Lote[]" class="form-control require" placeholder="Lote"  value=""></td>  ';
        table+='    <td><input type="text" name="Fecha[]" class="form-control date require" style="width:110px" placeholder="Fecha"  value="<?=$row['Fecha']?>"></td>';
        table+='    <td>';
        table+='        <input type="text" onchange="" id="Muestra'+z+'" name="Muestra[]" class="TotalCol form-control numeric require" style="width:90px" placeholder="Muestra"  value="<?=$row['Muestra']?>">';
        table+='    </td><td><i class="fa fa-share  fa-2x" onclick="agregaRenglones('+z+',$(\'#Muestra'+z+'\').val(),'+z+')"></i>';
        table+='    </td>';
        table+='    <td>';
        table+='        <table  class="table table-striped">';
        table+='            <tr><td>';
       table+='             <div class="accordion">';
                    <? $z=0; foreach($context->Factores as $row){?>                                                     
        table+='                        <h3><input type="hidden" name="idFactor[]" value="<?=$row['id']?>">';
        table+='                            <?=$row['Factor']?> (<?=$row['Especificacion']?>)';
        table+='                        </h3>';
        table+='                    <div>';
        table+='                       <table id="tbresultados'+z+'<?=$row['id']?>" class="table table-striped totales"></table>';
        table+='                    </div>';
                    <? $z++;}?>
        table+='            </div>';                            
        table+='                </td>';                       
        table+='            </tr>';   
        table+='        </table>';
        table+='<td><i class="fa fa-2x fa-trash-o" onclick="$(this).parent().parent().remove();"></i></td>'; 
        
        table+='    </td>';     
        table+='</tr>';           
         $("#tblress").append(table);
        DatePicker($('.date'));      
$( ".accordion" ).accordion({
        heightStyle: "content",
              collapsible: true

        

    });     
     });        
});

function DelFac(id){

    Question( "Â¿Desea eliminar este factor?", function(){
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
 function DelFacRes1(id){

    Question( "Â¿Desea eliminar este factor?", function(){
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
 function agregaRenglones(id,max,idd){
    sinMuestra=0;
    haymuestras=1;
    renglones=0;
    entra=0;
    maxOriginal=max;
    <? foreach($context->Factores as $row){?>
        table='';
        renglones= $("#tbresultados"+idd+<?=$row['id']?>+" tr").length;   
        if(renglones > maxOriginal){
            $("#tbresultados"+idd+<?=$row['id']?>+" tr").empty();
            
        }else{
            if(entra==0){
                max= max-renglones;
                entra=1;
            }
        }
        for(z=0;z<max;z++){
            table+='<tr>'; 
            table+='<td>';
            if(idd=='')
                table+='<input type="hidden" name="idd[]"   value="0">'; 
            else
                table+='<input type="hidden" name="idd[]"   value="'+idd+'">';                 
            table+='<input type="text" name="Resultados'+idd+'<?=$row['id']?>[]"  id="Resultados"  class="form-control numeric require" placeholder="Resultado"  value="">';             
            table+='</td>';
            table+='</tr>'; 
        }   
     //      $('#tra'+idd).find($("#tbresultados"+idd+<?=$row['id']?>)).append(table);
        $("#tbresultados"+idd+<?=$row['id']?>).append(table);
    <? }?>
    $( ".accordion" ).accordion({
        heightStyle: "content",
        collapsible: true        
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

<form id="op"  autocomplete="off">
    <table id="tblress" class="table table-striped">
        <tr>
            <td colspan="5  " >Agrerar resultado</td>
            <td><a class="btn btn-primary" id ="btnNewRes"><i class="fa fa-plus"></i></a></td>
        </tr>        
        <tr>
            <td>Lote</td>
            <td>Fecha</td>
            <td colspan="2">Muestra</td>
            
            <td>Caracteristica (EspecificaciÃ³n) </td>            
        </tr>
        <? $TotalCan=0;
            if(count($context->Resultados )>0){
            foreach($context->Resultados as $row){
                $TotalCan+=$row['Cantidad']?>
        <tr id="tr<?=$row['id']?>">            
            <td><input type="hidden" name="id[]" id="id" value="<?=$row['id']?>">
                <input type="text" name="Lote[]" class="form-control require" placeholder="Lote"  value="<?=$row['Lote']?>"></td>  
            <td><input type="text" name="Fecha[]" class="form-control date require" style="width:110px" placeholder="Fecha"  value="<?= SimpleDate($row['Fecha_Lote'])?>"></td>            
            <td><input type="text" name="Muestra[]" id="Muestra<?=$row['id']?>" readonly="" class=" form-control numeric require" style="width:90px" placeholder="Cantidad"  value="<?=$row['Muestra']?>"></td>          
            <td></td>

            <td>
                <table  class="table table-striped">
                    <tr>
                        <td>
                        <div class="accordion">
                        <? $z=0; foreach($context->Factores as $fac){?>                                                        
                            <h3><input type="hidden" name="idFactor[]" value="<?=$fac['id']?>">
                               <?=$fac['Factor']?>  &nbsp;(<?=$fac['Especificacion']?>)
                            </h3>
                            <div>
                               <table id="tbresultados<?=$row['id'].$fac['id']?>" class="table table-striped">
                                   <? for($z=0;$z<$row['Muestra']; $z++){?>
                                   <tr>
                                       <td>
                                           <input type="hidden" name="idd[]"   value="<?=$row['id']?>">
                                           <input type="text" name="Resultados<?=$row['id'].$fac['id']?>[]"  class="form-control numeric require totales" placeholder="Resultado"  value="<?=$context->ResultadosFac[$row['id']][$fac['id']][$z]?>">
                                       </td>
                                   </tr>
                                   <? }?>
                               </table>
                            </div>
                        <? $z++;}?>
                        </div>                            
                        </td>   
                        <td>
                            <i class="fa fa-2x fa-trash-o" onclick="DelFacRes1('<?=$row['id']?>')">
                            
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
            </td>
            <td>    
                <i class="fa fa-share fa-2x" onclick="agregaRenglones(0,$('#Muestra0').val(),'')"></i>
            </td>
            <td>
                <table  class="table table-striped">
                    <tr><td>
                    <div class="accordion">
                    <? $z=0; foreach($context->Factores as $row){?>

                                                        
                                <h3><input type="hidden" name="idFactor[]" value="<?=$row['id']?>">
                                    <?=$row['Factor']?> (<?=$row['Especificacion']?>)
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