<script>
    $(function(){
        $('#btnSave').click(function(){
           if(Full($('#op'))) {
               LoadButton($(this));
                    $.get('servicios.php?action=ckr&id='+$('#id').val()+'&Servicio=' +$('#Servicio').val() , function (e) {                        
                        if(e==''){
                            
                            $.post('servicios.php?action=save', $('#op').serialize(), function(data){
                               Ready();
                               if(data)
                                   Error(data);
                               else{
                                    ReloadGrid(grid, 'data/loadServicios.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));

                                   OK("Guardado");
                                   CloseModal();
                               }
                            });
                        }else{
                            Ready();
                            Error("El Servicio ya existe");        
                        }
                    });                     
           }
        });
    });
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
<form id="op" <? if($context->ver==1){echo 'class="vista"';}?>>
    <div class="form-group">
        <label>Servicio</label>
        <input type="text" class="form-control require" name="Servicio" value="<?=$data[0]['Servicio']?>" placeholder="Servicio">
    </div>
    <div class="form-group">
        <label>Tipo de medición del servicio</label>
        <select name="Tipo_Medicion" class="form-control require">
            <option value="1" <? if($data[0]['Tipo_Medicion']==1) echo "Selected";?>>Uno</option>
            <option value="2" <? if($data[0]['Tipo_Medicion']==2) echo "Selected";?>>Dos</option>
            <option value="3" <? if($data[0]['Tipo_Medicion']==3) echo "Selected";?>>Tres</option>
        </select>
    </div>    
    
    <? if($context->ver!=1){?>
    
    <input type="hidden" name="id" id="id" value="<?=$context->id?>">
    <p><a class="btn btn-success btn-lg" id ="btnSave"><i class="fa fa-save"></i> Guardar</a></p>
    <? }?>
</form>