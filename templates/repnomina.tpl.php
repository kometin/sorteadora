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
                
        DatePicker($('.date'));
        DoSelect($('.select2'));
        
        $('#btnSearch').click(function(){
            if($('#txtDate1').val() && $('#txtDate2').val()){
                Loading();
                LoadDetails();
//                LoadConcentrade();
            }else{
                Warning("Seleccione ambas fechas para ccontinuar");
            }
        });
    });
    
    function LoadDetails(){
        ReloadGrid(grid_det, 'data/loadRepNomina.php?mode=det' + getURL());
    }
    
    function LoadConcentrade(){
        ReloadGrid(grid_con, 'data/loadRepNomina.php?mode=con' + getURL());
    }
    
    function getURL(){
        return '&oper=' + $('#cmbOper').val() + '&d1=' + $('#txtDate1').val() + '&d2=' + $('#txtDate2').val();
    }
</script>
<?}?>

<?function Body($context){?>
<div class="form-group">
    <table class="table">
        <tr>
            <td><label>Operador</label></td>
            <td>
                <select class="select2" style="width: 400px" id ="cmbOper">
                    <option value="">TODOS</option>
                    <?foreach($context->oper as $o){?>
                    <option value="<?=$o[id]?>"><?=$o[RFC]?> <?=$o[Nombre]?> <?=$o[Paterno]?> <?=$o[Materno]?></option>
                    <?}?>
                </select>
            </td>
            <td><label>Entre</label></td>
            <td><input type="text" class="form-control date" id ="txtDate1"></td>
            <td><label>y</label></td>
            <td><input type="text" class="form-control date" id ="txtDate2"></td>
            <td><button class="btn btn-primary" id ="btnSearch"><i class="fa fa-search"></i> Mostrar</button></td>
        </tr>
    </table>
</div>

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

