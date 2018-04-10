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
      
        $('#btnExport').click(function(){
           ExportData(grid); 
        });
    });
    
    function Grid(){
        ReloadGrid(grid, 'data/loadResults.php?order=<?=$context->order?>');
    }   
    
    <?if($context->type == 1){?>
    
        AmCharts.makeChart("Chart1",{
            "type": "pie",
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
            "titleField": "Factor",
            "valueField": "Valor",
            "fontSize": 12,
            "theme": "light",
            "allLabels": [],
            "balloon": {},
            "titles": [],
            "depth3D": 15,
            "angle": 30, 
            "titles": [
                    {
                        "id": "Title-1",
                        "size": 15,
                        "text": "Total Defectos / Total Defects"
                    }
            ],
            "export": {
                "enabled": true
             },
            "dataProvider": <?=$context->chart1?>
        });
    
    
    
        AmCharts.makeChart("Chart2",{
                "type": "serial",
                "categoryField": "Lote",
                "startDuration": 1,
                "categoryAxis": {
                        "gridPosition": "start"
                },
                "trendLines": [],
                "graphs": [
                    <?foreach($context->factors as $i => $f){?>
                        {
                                "balloonText": "[[title]] in lote [[category]]:[[value]]",
                                "fillAlphas": 1,
                                "id": "AmGraph-<?=$i?>",
                                "title": "Defecto <?=$i+1?>",
                                "type": "column",
                                "valueField": "<?=$f[Factor]?>"
                        },
                    <?}?>
                        
                ],
                "guides": [],
                "valueAxes": [
                        {
                            "id": "ValueAxis-1",
                            "title": "Conteo / Counter"
                        }
                ],
                "allLabels": [],
                "balloon": {},
                "legend": {
                        "enabled": true,
                        "useGraphSettings": true
                },
                "titles": [
                        {
                            "id": "Title-1",
                            "size": 15,
                            "text": "Defectos por Lote / Defects by Batch"
                        }
                ],
                "export": {
                    "enabled": true
                 },
                "dataProvider": <?=$context->chart2?>
        });
        
    
    <?}elseif($context->type == 2){?>
       <?$cont = 1?>
       <?foreach($context->charts as $k => $v){?> 
           AmCharts.makeChart("Chart<?=$cont++?>",{
                "type": "pie",
                "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                "titleField": "Judgement",
                "valueField": "Count",
                "fontSize": 12,
                "theme": "light",
                "allLabels": [],
                "balloon": {},
                "titles": [],
                "depth3D": 15,
                "angle": 30, 
                "titles": [
                        {
                            "id": "Title-1",
                            "size": 15,
                            "text": "Results for <?=$k?>"
                        }
                ],
                "export": {
                    "enabled": true
                 },
                "dataProvider": <?=json_encode($v)?>
            });
       <?}?>
       
    <?}?>
</script>

<?}?>

<?function Body($context){?>

<?if($context->order){?>


<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#table" role="tab" data-toggle="tab">Tabla/Complete List</a></li>
    <?if($context->type != 3){?>
    <li role="presentation" class=""><a href="#graph" role="tab" data-toggle="tab">Gráficas/Charts</a></li>
    <?}?>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane fade in active" id="table">
        <button class="btn btn-primary pull-right" id ="btnExport"><i class="fa fa-file-excel-o"></i> Exportar/Export data</button><br>
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
    </div>
    <div role="tabpanel" class="tab-pane fade in " id="graph">
        <?if($context->type == 1){?>
        
        <div class="col-md-offset-1 col-md-10">
            <h3 class="text-center"><span class="label label-success">Gráfica 1 / Chart 1</span></h3>
            <div class="chart" style ="width: 100%; height: 400px;" id ="Chart1">

            </div>
        </div>
        <div class="col-md-offset-1 col-md-10">
            <h3 class="text-center"><span class="label label-success">Gráfica 2 / Chart 2</span></h3>
            <div class="chart" style ="width: 100%; height: 400px;" id ="Chart2">

            </div>
        </div>
        
        <?}?>
        <?if($context->type == 2){?>
        
        <?for($i=0; $i<count($context->charts); $i++){?>
        <div class="col-md-offset-1 col-md-10">
            <h3 class="text-center"><span class="label label-success">Gráfica <?=$i+1?> / Chart <?=$i+1?></span></h3>
            <div class="chart" style ="width: 100%; height: 300px;" id ="Chart<?=$i+1?>">
                
            </div>
        </div>
        <?}?>
        
        <?}?>
    </div>
</div>

<?}else{?>
<div class="alert alert-danger text-center"><h3>ORDER NUMBER INVALID</h3></div>
<?}?>

<?}?>

