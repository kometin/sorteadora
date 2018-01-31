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
<div class="card card-container">
    <center><img src="css/img/logo.JPG" width="150" height="150" alt=""/> </center> <hr>
    <h3 class="text-center">Ingenium Services Login</h3><br>
    <!--<img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />-->
    <p id="profile-name" class="profile-name-card"></p>
    <form class="form-signin" action="#" method="post">

        <span id="reauth-email" class="reauth-email"></span>
        <input type="text" id="inputEmail" class="form-control" name ="email" placeholder="Correo electrónico" required autofocus>
        <input type="password" id="inputPassword" class="form-control" name ="pwd" placeholder="Contraseña" required>

        <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Entrar</button>
    </form><!-- /form -->
    <a href="#" class="forgot-password">
        ¿Olvido su contraseña?
    </a>

</div><!-- /card-container -->
<?}?>

