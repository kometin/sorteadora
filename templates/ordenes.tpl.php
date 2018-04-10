<?function Style($context){?>
<style type="text/css">
    .swal2-popup{
        z-index: 10000000;
    }
</style>
<?}?>

<?function Script($context){?>
<script type ="text/javascript">
    var grid;
    $(function(){
        <?setGrid("grid", $context->params, true)?>
        Loading();
        ReloadGrid(grid, 'data/loadOrdenes.php');
                
        $('#btnNew').click(function(){
             Modal('ordenes.php?action=form', 'Nueva orden de servicio', 600); 
        });
$('#chkGrid').click(function(){
            Grid();
        });        
        if($('#chkGrid').is(':checked')){
            
                ReloadGrid(grid, 'data/loadOrdenes.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));
                Loading();

        }        
    });
function Grid(){
    
    ReloadGrid(grid, 'data/loadOrdenes.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));
    Loading();
    
}   
function Edit(id){
    Modal('ordenes.php?action=form&id=' + id, 'Editar orden de servicio', 700, function () {
    });
}
function Factores(id, tipo){
    Modal('ordenes.php?action=fac&id=' + id+'&tipo='+tipo, 'Configuración de la orden', 800, function () {});
}function Resultados(id,tipo){
    Modal('ordenes.php?action=res&id=' + id+"&tipo="+tipo, 'Resultados', 1000, function () {});
}
function Ver(id){
    Modal('ordenes.php?action=ver&id=' + id, 'Editar usuario', 700, function () {
    });
}    
    function Del(id){

        Question( "¿Desea dar de baja esta orden?", function(){
           Loading();
        $.get('ordenes.php?action=del&id=' + id, function (data) {
              Ready();
              if(data)
                  Error(data);
              else{
                  OK("Borrado");
                  ReloadGrid(grid, 'data/loadOrdenes.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));
              }
           });
        });
    } 
    
    function Manage(id, stat){
        switch(stat){
            case 1:
                Modal('ordenes.php?action=manage&id='+ id, 'Enviar confirmación', 900);
                break;
            case 2:
                Modal('ordenes.php?action=manage&id='+ id, 'Avance de servicio', 800);
                break;
            case 3:
                Modal('ordenes.php?action=manage&id='+ id, 'Cerrar órden', 700);
                break;
            default: 
                
                break;
                        
        }
    }
    
</script>
<style>
.checkbox{
    float: right;
    margin-right: 10px;
    top: -32px;
    height: 0px;
}

</style>
<?}?>

<?function Body($context){?>
<p>
    <a class="btn btn-primary" id ="btnNew"><i class="fa fa-plus"></i> Nueva orden de servicio</a>
</p>
</br>
<div class="checkbox checkbox-circle checkbox-primary" style='display:none' id ="chkAll">
    <input type="checkbox" id="chkGrid" >
    <label for="chkGrid">
        Mostrar bajas
    </label>
</div>  
<table width="100%"  cellpadding="0" cellspacing="0">		
    <tr>
         <td id="pager_grid"></td>
    </tr>
    <tr>
         <td><div id="infopage_grid" style =""></div></td>
    </tr>
    <tr>
         <td><div id="grid" style ="height: 500px; width: 100%"></div></td>
    </tr>
    <tr>
         <td class = "RowCount"></td>
    </tr>
</table>
<?}?>

