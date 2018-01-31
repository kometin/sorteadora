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
               $.post('config.php?action=save', $('#config').serialize(), function(data){
                  Ready();
                  if(data)
                      Error(data);
                  else{
                      OK("Guardado");
                  }
               });
           }
        });
    });
</script>
<?}?>

<?function Body($context){?>
<form id ="config">
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
    <p><a class="btn btn-success btn-lg" id ="btnSave"><i class="fa fa-save"></i> Guardar</a></p>
</form>
<?}?>

