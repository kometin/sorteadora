
<!DOCTYPE html>
<html lang="es"> 
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <title>Ingenium Services Admin</title>
        
        <link type="text/css" rel="stylesheet" href="css/bootstrap.css">
        <?if($_SESSION[SORTUSER]){?>
        <link type="text/css" rel="stylesheet" href="css/jquery-ui.css">
        <link type="text/css" rel="stylesheet" href="css/menu/plugins.css">
        <link type="text/css" rel="stylesheet" href="css/menu/style.css">
        <link type="text/css" rel="stylesheet" href="css/menu/presets/preset-gradient.css">
        <link type="text/css" rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.css">
        <link type="text/css" rel="stylesheet" href="css/select2/select2.css">
        <link type="text/css" rel="stylesheet" href="css/multiselect/css/multi-select.css">
        <link type="text/css" rel="stylesheet" href="js/dhtmlx/dhtmlx.css">
        <link type="text/css" rel="stylesheet" href="js/dataTables/css/jquery.dataTables.min.css">
        <link type="text/css" rel="stylesheet" href="js/sweetalert/dist/sweetalert.css">
        <link type="text/css" rel="stylesheet" href="js/timepicker/jquery.timepicker.css">
        <link type="text/css" rel="stylesheet" href="js/calendar/calendar.css">
        <?}else{?>
        <link type="text/css" rel="stylesheet" href="css/login.css">
        <?}?>
        
        <style type="text/css">
            body, html {
                height: 100%;
                background-repeat: no-repeat;
                background-image: linear-gradient(#27ACDD, #84BA55);
            }
            #main-title {margin-top: 100px;}
            div.gridbox {
                -webkit-box-sizing: content-box;
                -moz-box-sizing: content-box;
                box-sizing: content-box;
            }
            .emptyfield {border: 2px solid red;}
            .row {margin-right: 0 !important;}
            .fa {cursor: pointer;}
            .fa-trash-o {color: red;}
            .fa-question-circle {color: #4988ED;}
            .fa-times-circle-o {color: #F21D1D;}
            .fa-pencil {color: #74C5F7;}
            .fa-edit {color: #4988ED;}
            .fa-power-off {color: red;}
            .fa-archive {color: #84847F;}
            .fa-user {color: #80D3F7;}
            .fa-times {color: #C90808; }
            .fa-download {color: #218DED; }
            .RowCount {float: right; padding: 10px 20px;}
            .fa-search-plus {color: #036;}
            <?if($_SESSION[SORTUSER]){?>
            #main-content {padding: 10px; border-radius: 5px; border: 2px solid #6d6767; background: white; }
            <?}?>
        </style>
        
        <?Style($context)?>
        
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/jquery-ui.js"></script>
        <script type="text/javascript" src="js/bootstrap.js"></script>
        <script type="text/javascript" src="js/dhtmlx/dhtmlx.js"></script>
        <script type="text/javascript" src="js/dataTables/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="js/select2.js"></script>
        <script type="text/javascript" src="js/jquery.multi-select.js"></script>
        <script type="text/javascript" src="js/sweetalert/dist/sweetalert2.all.js"></script>
        <script type="text/javascript" src="js/timepicker/jquery.timepicker.js"></script>
        <script type="text/javascript" src="js/moment-with-locales.js"></script>
        <script type="text/javascript" src="js/calendar/calendar.js"></script>
        <script type="text/javascript" src="js/fn.js"></script>
        
        <script type="text/javascript">
            $(document).ready(function() {
                $('.main-nav').find('li.has-child').removeClass('active');
                $('.main-nav').find('a[href="<?=getModule()?>"]').parents('li.has-child').addClass('active');
            });
        </script>
        
        <?Script($context)?>
        
    </head>
    <body>
        <?if($_SESSION[SORTUSER]){?>

        <?include('templates/menu.tpl.php')?>

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h3 class="modal-title" ><span class="label label-info" id="myModalLabel">Modal title</span></h3>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <h2 id ="main-title" class="text-center"><span class="label label-success"> <?=$context->title?> </span></h2><hr>

        <?}?>

        <div class="container" id ="main-content">

            <?Body($context)?>

        </div>
    </body>
</html>

