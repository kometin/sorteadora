<?function Style($context){?>
<style type="text/css">
   
</style>
<?}?>

<?function Script($context){?>
<script type ="text/javascript">
    var grid;
    $(function(){
        <?setGrid("grid", $context->params, true)?>
        ReloadGrid(grid, 'data/loadOperadores.php');
                
        $('#btnNew').click(function(){
             Modal('operadores.php?action=form', 'Nuevo operador', 600); 
        });
    });
function Edit(id){
    Modal('operadores.php?action=form&id=' + id, 'Editar usuario', 700, function () {
    });
}
function Ver(id){
    Modal('operadores.php?action=ver&id=' + id, 'Editar usuario', 700, function () {
    });
}    function Del(id){

        Question( "¿Desea borrar este cliente?", function(){
           Loading();
        $.get('clientes.php?action=del&id=' + id, function (data) {
              Ready();
              if(data)
                  Error(data);
              else{
                  OK("Borrado");
               ReloadGrid(grid, 'data/loadClientes.php');
              }
           });
        });
    } 
    function Del(id){

        Question( "¿Desea borrar este operador?", function(){
           Loading();
        $.get('operadores.php?action=del&id=' + id, function (data) {
              Ready();
              if(data)
                  Error(data);
              else{
                  OK("Borrado");
                ReloadGrid(grid, 'data/loadOperadores.php');
              }
           });
        });
    } 
    
</script>
<?}?>

<?function Body($context){?>
<p>
    <a class="btn btn-primary" id ="btnNew"><i class="fa fa-plus"></i> Nuevo operador</a>
</p>
<br>
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

