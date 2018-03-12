<?function Style($context){?>
<style type="text/css">
   
</style>
<?}?>

<?function Script($context){?>
<script type ="text/javascript">
    $(function(){
        
    });
</script>
<?}?>

<?function Body($context){?>
<div class="jumbotron text-center">
    <h1>Sistema Ingenium Services</h1> 
    <p>
        Logged as: <?=$_SESSION[SORTNAME]?>
    </p> 
    <small><?=$_SESSION[SORTCOMP]?></small>
    <small><?=$_SESSION[SORTROLE]?></small>
</div>
<?}?>

