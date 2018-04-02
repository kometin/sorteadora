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
    });
</script>


    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#access" role="tab" data-toggle="tab">Acceso a sistema</a></li>
        <li role="presentation" class=""><a href="#financial" role="tab" data-toggle="tab">Datos financieros</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="access">
            <form id ="sender-access">
                <input type="hidden" name="type" value ="CUSTOMER">
                <input type="hidden" name="message-es" class="msg-spanish">
                <input type="hidden" name="message-en" class="msg-english">
                <br>
                <div class="form-group">
                    <label>Destinatarios</label>
                    <select class="select2 address" multiple name ="address[]" style ="width: 700px">
                        <option></option>
                        <?foreach($context->contacts as $c){?>
                            <?if($c[Password] == "NEW"){?>
                                <option value="<?=$c[Correo]?>" selected> <?=$c[Nombre]?> (<?=$c[Correo]?>) </option>
                            <?}?>
                        <?}?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>
                            <input type ="checkbox" class="chk-editor" name ="language[]" value="es" checked="">
                            Español
                        </label>
                        <div class="summernote mail-spanish"><?= htmlspecialchars_decode($context->mails[CUSTOMER][es])?></div>
                    </div>
                    <div class="col-md-6">
                        <label>
                            <input type ="checkbox" class="chk-editor" name ="language[]" value="en" checked="">
                            English
                        </label>
                        <div class="summernote mail-english"><?= htmlspecialchars_decode($context->mails[CUSTOMER][en])?></div>
                    </div>
                </div>
                <div class="alert alert-info">
                    La información de acceso será adjuntada automáticamente en este correo
                </div>
                <p>
                    <a class="btn btn-success btn-lg btn-send-mail" ><i class="fa fa-send-o"></i> Enviar</a>
                </p>
            </form>
        </div>
        
        <div role="tabpanel" class="tab-pane fade in " id="financial">
            <form id ="sender-financial">
                <input type="hidden" name="type" value ="SUPPLIER">
                <input type="hidden" name="message-es" class = "msg-spanish">
                <input type="hidden" name="message-en" class = "msg-english">
                <br>
                <div class="form-group">
                    <label>Destinatarios</label>
                    <select class="select2 address" multiple name ="address[]" style ="width: 700px">
                        <option></option>
                        <?foreach($context->contacts as $c){?>
                            <?if($c[Tipo] == "Financial"){?>
                                <option value="<?=$c[Correo]?>" selected> <?=$c[Nombre]?> (<?=$c[Correo]?>)</option>
                            <?}?>
                        <?}?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>
                            <input type ="checkbox" class="chk-editor" name ="language[]" value="es" checked="">
                            Español
                        </label>
                        <div class="summernote mail-spanish" ><?= htmlspecialchars_decode($context->mails[SUPPLIER][es])?></div>
                    </div>
                    <div class="col-md-6">
                        <label>
                            <input type ="checkbox" class="chk-editor" name ="language[]" value="en" checked="">
                            English
                        </label>
                        <div class="summernote mail-english" ><?= htmlspecialchars_decode($context->mails[SUPPLIER][en])?></div>
                    </div>
                </div>
                <div class="alert alert-info">
                    El archivo de información de la empresa será adjuntado automáticamente
                </div>
                <p>
                    <a class="btn btn-success btn-lg btn-send-mail" ><i class="fa fa-send-o"></i> Enviar</a>
                </p>
            </form>
        </div>
    </div>
</form>