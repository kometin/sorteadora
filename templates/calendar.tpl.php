<?function Style($context){?>
<style type="text/css">
   #cmbYear {margin: 20px auto; width: 150px; text-align: center;}
   #calendar {width: 99%; height: 500px;}
</style>
<?}?>

<?function Script($context){?>
<script type ="text/javascript">
    $(function(){
       $('#cmbYear').change(function(){
            DisplayCalendar();
       }).trigger('change');
    });
    
    function DisplayCalendar(){
        Loading();
        $.getJSON('calendar.php?action=load&year=' + $('#cmbYear').val(), function(json){
              Ready();
              $('#calendar').makeCalendar({
                    date_start: $('#cmbYear').val() + '-01-01', 
                    date_end: $('#cmbYear').val() + '-12-31', 
                    days_selected: json, 
                    fn_select: function(day){
                        $.get('calendar.php?action=set&date=' + day, function(data){
                           if(data) Error(data); 
                        });
                    }, 
                    fn_deselect: function(day){
                        $.get('calendar.php?action=set&date=' + day, function(data){
                           if(data) Error(data); 
                        });
                    }
              });
        });
    }
</script>
<?}?>

<?function Body($context){?>

<select class="form-control input-lg" id ="cmbYear">
    <?for($y=Date('Y')+1; $y>= $context->min; $y--){?>
    <option value="<?=$y?>" <?=($y==Date('Y')?"selected":"")?>><?=$y?></option>
    <?}?>
</select>

<div id ="calendar">
    
</div>
<?}?>

