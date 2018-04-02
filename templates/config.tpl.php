<?function Style($context){?>
<style type="text/css">
   
</style>
<?}?>

<?function Script($context){?>
<script type ="text/javascript">
    $(function(){
        $('#btnSave').click(function(){
           if(Full($('#config'))){
               LoadButton($(this));
               AjaxSend($('#config'), 'config.php?action=save', function(data){
                  Ready();
                  OK("Guardado");
               });
           }
        });
        
        $('#btnChangeFile').click(function(){
           $(this).parent().append('<input type="file" name="file1" id ="file1" accept=".docx,.pdf">').find("a.btn").remove();
        });
    });
</script>
<?}?>

<?function Body($context){?>
<form id ="config" class="col-md-6 col-md-offset-3">
    <div class="form-group">
        <label>Limite Horas Dobles</label>
        <input type="number" class="form-control numeric require" name="param[]" placeholder="Num max hrs" value="<?=$context->params[1]?>"> 
    </div>
    <div class="form-group">
        <label>Limite Horas Triples</label>
        <input type="number" class="form-control numeric require" name="param[]" placeholder="Num max hrs" value="<?=$context->params[2]?>"> 
    </div>
    <div class="form-group">
        <label>Pago Hora Normal</label>
        <input type="number" class="form-control numeric require" name="param[]" placeholder="Cuota $" value="<?=$context->params[3]?>"> 
    </div>
    <div class="form-group">
        <label>Pago Hora Doble</label>
        <input type="number" class="form-control numeric require" name="param[]" placeholder="Cuota $" value="<?=$context->params[4]?>"> 
    </div>
    <div class="form-group">
        <label>Pago Hora Triple</label>
        <input type="number" class="form-control numeric require" name="param[]" placeholder="Cuota $" value="<?=$context->params[5]?>"> 
    </div>
    <div class="form-group">
        <label>Información financiera de la empresa</label>
        <?if($context->params[6]){?>
            <a href="<?=$context->params[6]?>" target="_blank" class="btn btn-default"><i class="fa fa-download"></i> Descargar</a>
            <a class="btn btn-danger" id ="btnChangeFile"><i class="fa fa-ban"></i> Cambiar</a>
        <?}else{?>
            <input type="file" name="file1" id ="file1" accept=".docx,.pdf">
        <?}?>
    </div>
    <p><a class="btn btn-success btn-lg" id ="btnSave"><i class="fa fa-save"></i> Guardar</a></p>
</form>
<?}?>

