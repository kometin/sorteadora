<?function Style($context){?>
<style type="text/css">
   
</style>
<?}?>

<?function Script($context){?>
<script type ="text/javascript">
    var grid;
    $(function(){
        <?setGrid("grid", $context->params, true)?>
        ReloadGrid(grid, 'data/loadClientes.php');
                
        $('#btnNew').click(function(){
             Modal('clientes.php?action=form', 'Nuevo cliente', 600); 
        });
        $('#chkGrid').click(function(){
            Grid();
        });        
        if($('#chkGrid').is(':checked')){
            Loading();
                ReloadGrid(grid, 'data/loadClientes.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));

        }        
    });
function Grid(){
    ReloadGrid(grid, 'data/loadUsuarios.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));
}   
function Edit(id){
    Modal('clientes.php?action=form&id=' + id, 'Editar cliente', 700, function () {
    });
}
function Ver(id){
    Modal('clientes.php?action=ver&id=' + id, 'Ver cliente', 700, function () {
    });
}

    function Del(id){

        Question( "¿Desea dar de baja este cliente?", function(){
           Loading();
        $.get('clientes.php?action=del&id=' + id, function (data) {
              Ready();
              if(data)
                  Error(data);
              else{
                  OK("Borrado");
                  ReloadGrid(grid, 'data/loadClientes.php?all=' + ($('#chkGrid').is(':checked')?"1":"0"));
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
    <a class="btn btn-primary" id ="btnNew"><i class="fa fa-plus"></i> Nuevo cliente</a>
</p>
<div class="checkbox checkbox-circle checkbox-primary" id ="chkAll">
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

