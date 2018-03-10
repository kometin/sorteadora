<script>
    
</script>

<form id ="sender">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#access" role="tab" data-toggle="tab">Acceso a sistema</a></li>
        <li role="presentation" class=""><a href="#financial" role="tab" data-toggle="tab">Datos financieros</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="access">
            <div class="form-group">
                <label>Destinatarios</label>
                <select class="select2" multiple id ="cmbMailAccess">
                    <option></option>
                    <?foreach($context->contacts as $c){?>
                    <?if($c[Password]){?>
                        <option value="<?=$c[Correo]?>" selected> <?=$c[Nombre]?> </option>
                    <?}?>
                    <?}?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="summernote" id ="MailAccessSpanish"></div>
                </div>
                <div class="col-md-6">
                    <div class="summernote" id ="MailAccessEnglish"></div>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade in " id="financial">
            
        </div>
    </div>
</form>