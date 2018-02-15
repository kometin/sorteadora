<?function Style($context){?>
<style type="text/css">
   
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
            Loading();
                ReloadGrid(grid, 'data/loadOrdenes.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));

        }        
    });
function Grid(){
    Loading();
    ReloadGrid(grid, 'data/loadOrdenes.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));
}   
function Edit(id){
    Modal('ordenes.php?action=form&id=' + id, 'Editar usuario', 700, function () {
    });
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

