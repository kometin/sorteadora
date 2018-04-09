<script>
    $(function(){
       DoSelect($('.select2'));
        
       Summernote($('.summernote'));
       
       $('.chk-editor').change(function(){
          var editor = $(this).parents('div:first').find('.summernote');
          $(editor).summernote( $(this).is(':checked') ? 'enable' : 'disable' );
       });
       
       $('.btn-send-mail').click(function(){
          var parent = $(this).parents('form');
          if($(parent).find('select.address').val()){
              if($(parent).find('.chk-editor:checked').length > 0){
                  $(parent).find('.msg-spanish').val( $(parent).find('.mail-spanish').summernote('code') );
                  $(parent).find('.msg-english').val( $(parent).find('.mail-english').summernote('code') );
                  LoadButton($(this));
                  $.post('clientes.php?action=mail', $(parent).serialize(), function(data){
                        Ready();
                        if(data){
                            Error(data);
                        }else{
                            OK("Enviado"); 
                        }
                  });
              }else{
                  Warning("Seleccione un idioma para su mensaje");
              }
          }else{
              Warning("Seleccione los destinatarios");
          }
       });
       
       $('#btnConfirm').click(function(){
          Question("Desea confirmar esta órden manualmente?", function(){
             LoadButton($('#btnConfirm')) ;
             $.get('ordenes.php?action=confirm&id=<?=$context->data->id?>', function(data){
                 Ready();
                 if(data){
                     Error(data);
                 }else{
                     OK("Órden confirmada");
                     CloseModal();
                     Loading();
                     ReloadGrid(grid, 'data/loadOrdenes.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));
                 }
                     
             });
          });
       });
    });
</script>
<form id ="sender-confirm">
    <div class="panel panel-primary">
        <div class="panel-heading panel-title">Información general</div>
        <div class="panel-body">
            <input type="hidden" name="key" value ="<?=$context->data->Clave?>">
            <table class="table table-condensed table-striped">
                <tr>
                    <td><label>Folio</label></td>
                    <tD>
                        <input type="text" class="form-control" name ="folio" value="<?= substr($context->data->Empresa, 0, 3)?><?=format($context->data->Folio, 3, "0")?>" readonly>
                    </tD>
                    <td><label>Servicio</label></td>
                    <tD>
                        <input type="text" class="form-control" name ="service" value="<?=$context->data->Servicio?>" readonly>
                    </tD>
                </tr>
                 <tr>
                    <td><label>Descripción</label></td>
                    <tD colspan="3">
                        <input type="text" class="form-control" name ="desc" value="<?=$context->data->Descripcion?>" readonly>
                    </tD>
                 </tr>
                 <tr>
                    <td><label>Num. Parte</label></td>
                    <tD>
                        <input type="text" class="form-control" name ="number" value="<?=$context->data->Numero_Parte?>" readonly>
                    </tD>
                    <td><label>Total Partes</label></td>
                    <tD>
                        <input type="text" class="form-control" name ="total" value="<?=$context->data->Total_Partes?>" readonly>
                    </tD>
                 </tr>
            </table>
        </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading panel-title">Confirmación</div>
        <div class="panel-body">
            <input type="hidden" name="type" value ="ACCEPT">
            <input type="hidden" name="message-es" class="msg-spanish">
            <input type="hidden" name="message-en" class="msg-english">
            <br>
            <div class="form-group">
                <label>Destinatarios</label>
                <select class="select2 address" multiple name ="address[]" style ="width: 800px">
                    <option></option>
                    <?foreach($context->contacts as $c){?>

                        <option value="<?=$c[Correo]?>" <?=$c[Principal]?"selected":""?>> <?=$c[Nombre]?> (<?=$c[Correo]?>) </option>

                    <?}?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>
                        <input type ="checkbox" class="chk-editor" name ="language[]" value="es" checked="">
                        Español
                    </label>
                    <div class="summernote mail-spanish"><?= htmlspecialchars_decode($context->mails[ACCEPT][es])?></div>
                </div>
                <div class="col-md-6">
                    <label>
                        <input type ="checkbox" class="chk-editor" name ="language[]" value="en" checked="">
                        English
                    </label>
                    <div class="summernote mail-english"><?= htmlspecialchars_decode($context->mails[ACCEPT][en])?></div>
                </div>
            </div>
            <div class="alert alert-info">
                La información completa de la órden será adjuntada automáticamente 
            </div>
            <div style="height: 50px">
                <a class="btn btn-success btn-lg pull-left btn-send-mail" ><i class="fa fa-send-o"></i> Enviar</a>
                <a class="btn btn-danger btn-lg pull-right" id ="btnConfirm" ><i class="fa fa-check"></i> Confirmar órden manual</a>
            </div>
        </div>
    </div>        
</form>
       