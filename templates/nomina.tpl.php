<?function Style($context){?>
<style type="text/css">
    #display {background: #99ccff; padding: 10px;}
    #btnPrev {float: left;}
    #btnNext {float: right;}
    #tbl-work th {max-width: 60px !important;}
</style>
<?}?>

<?function Script($context){?>
<script type ="text/javascript">
    var data, page, total, step = 7;
    
    $(function(){
        DatePicker($('.date'));
        
        $('#btnShow').click(function(){
           if($('#txtSince').val() && $('#txtUntil').val()){
               Loading();
               $('#display').load('nomina.php?action=load&d1=' + $('#txtSince').val() + '&d2=' + $('#txtUntil').val(), function(data){
                  Ready();
               });
           }else{
               Warning("Capture ambas fechas para continuar");
           }
        });
        
        $('#btnPrev').click(function(){
//           if(!$(this).has('disabled'))
                Navigate('-');
        });
        $('#btnNext').click(function(){
//           if(!$(this).has('disabled'))
                Navigate('+');
        });
    });
    
    function Navigate(direction){
        var max = Math.floor(total / step);
        var since, until;
        if(direction == '+'){
            page++;
        }else{
            page--;
        }
//        alert(page);
        if(page > max){
            since = page * step - step;
            until = total;
        }else{
            since = page * step - step;
            until = page * step;
        }
//        alert(since);
//        alert(until);
        $('#tbl-work').find('td:visible, th:visible').not('.fixed').hide();
        for(var i=since; i<until; i++){
            $('#tbl-work').find('.col-index-' + i).fadeIn();
        }
        
        if(page > 1){
            $('#btnPrev').removeAttr('disabled');
        }else{
            $('#btnPrev').attr('disabled', 'true');
        }
        if(page > max){
            $('#btnNext').attr('disabled', 'true');
        }else{
            $('#btnNext').removeAttr('disabled');
        }
        
    }
    
    function getParamValues(){
        return '&LD=' + $('#txtLimitDouble').val() + '&LT=' + $('#txtLimitTriple').val() + '&PN=' + $('#txtPaymentNormal').val() + '&PD=' + $('#txtPaymentDouble').val() + '&PT=' + $('#txtPaymentTriple').val();
    }
</script>
<?}?>

<?function Body($context){?>
<div id ="selector" class="form-group">
    <table class="table">
        <tr>
            <td width ="200"><button class="btn btn-default" id ="btnPrev" disabled><i class="fa fa-arrow-left"></i></button></td>
            <td><label>Fecha de inicio</label></td>
            <td><input type="text" class="form-control date require" id="txtSince"></td>
            <td><label>Fecha de fin</label></td>
            <td><input type="text" class="form-control date require" id="txtUntil"></td>
            <td>
                <a class="btn btn-primary" id ="btnShow"><i class="fa fa-search"></i> Mostrar</a>
            </td>
            <td width ="200"><button class="btn btn-default" id ="btnNext" disabled><i class="fa fa-arrow-right"></i></button></td>
        </tr>
    </table>
</div>
<div class="panel panel-info">
    <div class="panel-heading"><h4>Valor de parámetros actuales</h4></div>
    <div class="panel-body">
        <table class="table" id ="tbl-param">
            <tR>
                <td><label>Límite horas dobles</label></td>
                <td><input type="text" class="form-control require numeric" id ="txtLimitDouble" value="<?=$context->params[1]?>"></td>
                <td><label>Límite horas triples</label></td>
                <td><input type="text" class="form-control require numeric" id="txtLimitTriple" value="<?=$context->params[2]?>"></td>
                <td><label>Pago horas normal</label></td>
                <td><input type="text" class="form-control require numeric" id="txtPaymentNormal" value="<?=$context->params[3]?>"></td>
                <td><label>Pago horas dobles</label></td>
                <td><input type="text" class="form-control require numeric" id="txtPaymentDouble" value="<?=$context->params[4]?>"></td>
                <td><label>Pago horas triples</label></td>
                <td><input type="text" class="form-control require numeric" id="txtPaymentTriple" value="<?=$context->params[5]?>"></td>
            </tR>
        </table>
    </div>
</div>
<div id ="display">
    
</div>
<?}?>

