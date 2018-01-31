<script>
    $(function(){
        
        data = <?=$context->data?>;
        
        var dates = "";
        var column = 0;
        
        $.each(data[0].dates, function(k, v){
            dates += "<th colspan = '2' class = 'date col-index-" + column + "'><center>" + k + "</center></th>"; 
            column++;
        });
        
        page = 0;
        total = column;
        
        var header = "<thead>"
                   + "<th class = 'fixed' width = '200'>Operador</th>"
                   + dates 
                   + "</thead>";
        $('#tbl-work').append(header);
        var rows = "";
        $.each(data, function(){
            column = 0;
            var id = this.id;
            rows += "<tr>"
                  + "<td class = 'fixed'>" + this.name + "<br><span class = 'label label-default'>" + this.rfc + "</span></td>";
            $.each(this.dates, function(k, v){
               rows += "<td class = 'col-index-" + column + "'><input type = 'text' class = 'timepicker form-control input-sm require numeric time-in' name ='" + id + "|" + k + "|in' value ='" + (v.in || "") + "' placeholder = 'Entrada'></td>"
                     + "<td class = 'col-index-" + column + "'> <input type = 'text' class = 'timepicker form-control input-sm require numeric time-out' name ='" + id + "|" + k + "|out' value ='" + (v.out || "") + "' placeholder = 'Salida'></td>";
               column++;
            });
            rows += "</tr>";
        });
        $('#tbl-work').append(rows);
        $('#tbl-work').find('td, th').not('.fixed').hide();
        $('.timepicker').timepicker({timeFormat: 'G:i', scrollDefault: '06:30'}).on('changeTime', function(){
            if($(this).hasClass('time-in')){
                var time = moment('2018-01-01 ' + $(this).val());
                console.log(time);
                var out = $(this).parent().next().find('.time-out');
                if(!$(out).val()){
                    $(out).timepicker('setTime', time.add(8, 'hours').format('HH:mm'));
                }
            }
        });
        
        if($('.timepicker').length > 1000){
            Error("La cantidad de registros excede el límite, es posible revisar pero no guardar ningún cambio");
            $('#btnSaveAll').hide();
        }
        
        Navigate('+');
        
        $('#btnSaveAll').click(function(){
            if(Full($('#tbl-work'), true) && Full($('#tbl-param'))){
                Loading();
                $.post('nomina.php?action=save' + getParamValues(), $('#jornadas').serialize(), function(data){
                    Ready();
                    if(data){
                        Error(data);
                    }else{
                        OK("Datos guardados");
                    }
                });
            }else{
                Error("Debe completar todos los campos vacíos para continuar");
            }
        });
    });
    
    
</script>

<form id ="jornadas">
    <table class="table table-condensed table-striped " id ="tbl-work">
        
    </table>
    <p>
        <a class="btn btn-success btn-lg" id ="btnSaveAll"><i class="fa fa-save"></i> Guardar todo</a>
    </p>
</form>