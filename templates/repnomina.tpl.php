<?function Style($context){?>
<style type="text/css">
   
</style>
<?}?>

<?function Script($context){?>
<script type ="text/javascript">
    var grid_det, grid_con;
    $(function(){
        <?=setGrid("grid_det", $context->params['DET'], true)?>
        <?=setGrid("grid_con", $context->params['CON'], true)?>
                
    });
</script>
<?}?>

<?function Body($context){?>
<ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#details" role="tab" data-toggle="tab">Reporte detallado</a></li>
        <li role="presentation" class=""><a href="#concentrade" role="tab" data-toggle="tab">Reporte concentrado</a></li>
       
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="details">
            <table width="100%"  cellpadding="0" cellspacing="0">		
                <tr>
                     <td id="pager_grid_det"></td>
                </tr>
                <tr>
                     <td><div id="infopage_grid_det" style =""></div></td>
                </tr>
                <tr>
                     <td><div id="grid_det" style ="height: 500px; width: 100%"></div></td>
                </tr>
                <tr>
                     <td class = "RowCount"></td>
                </tr>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane fade in " id="concentrade">
            <table width="100%"  cellpadding="0" cellspacing="0">		
                <tr>
                    <td id="pager_grid_con"></td>
               </tr>
               <tr>
                    <td><div id="infopage_grid_con" style =""></div></td>
               </tr>
               <tr>
                    <td><div id="grid_con" style ="height: 500px; width: 100%"></div></td>
               </tr>
               <tr>
                    <td class = "RowCount"></td>
               </tr>
           </table>
        </div>
    </div>
        


<?}?>

