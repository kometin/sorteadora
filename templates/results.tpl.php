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
        Grid();
        
    });
    
    function Grid(){
        ReloadGrid(grid, 'data/loadResults.php?order=<?=$context->order?>');
    }   
    
</script>

<?}?>

<?function Body($context){?>

<?if($context->order){?>

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

<?}else{?>
<div class="alert alert-danger text-center"><h3>ORDER NUMBER INVALID</h3></div>
<?}?>

<?}?>

