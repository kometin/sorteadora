<?function Style($context){?>
<style type="text/css">
   
</style>
<?}?>

<?function Script($context){?>
<script type ="text/javascript">
    $(function(){
       $('#btnChangePwd').click(function(){
          if(Full($('#change-password'))) {
              LoadButton($(this));
              $.post('account.php?action=password', $('#change-password').serialize(), function(data){
                 Ready();
                 if(data)
                     Error(data);
                 else
                     OK("Contraseña cambiada / Password changed");
              });
          }
       });
    });
</script>
<?}?>

<?function Body($context){?>
<div class="col-md-10 col-md-offset-1">
    <div class="panel panel-success">
    <div class="panel-heading panel-title">Datos de la empresa / Company information</div>
        <div class="panel-body">
            <table class="table table-striped">
                <tr>
                    <td><label>Nombre / Company</label></td>
                    <td><?=$context->client->Empresa?></td>
                    <td><label>Razón Social / Formal name</label></td>
                    <td><?=$context->client->RazonSocial?></td>
                </tr>
                <tr>
                    <td><label>RFC</label></td>
                    <td><?=$context->client->RFC?></td>
                    <td><label>Teléfono / Phone number</label></td>
                    <td><?=$context->client->Telefono?></td>
                </tr>
                <tr>
                    <td><label>Dirección / Address</label></td>
                    <td colspan="3"><?=$context->client->Direccion?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading panel-title">Contactos registrados / Company contacts</div>
        <div class="panel-body">
            <table class="table table-striped">
                <thead>
                <th>Nombre / Name</th>
                <th>Correo / Email</th>
                <th>Acceso a sistema / Access granted</th>
                </thead>
                <?foreach($context->contacts as $c){?>
                <tr>
                    <td><?=$c[Nombre]?></td>
                    <td><?=$c[Correo]?></td>
                    <td><h4><span class="label label-<?=$c[Password] && $c[Password] != "NEW" ? "success" : "default"?> "> <?=$c[Password] && $c[Password] != "NEW" ? "YES" : "NO"?> </span></h4></td>
                </tr>
                <?}?>
            </table>
        </div>
    </div>
    <div class="panel panel-warning">
        <div class="panel-heading panel-title">Cuentas bancarias / Bank accounts</div>
        <div class="panel-body">
            <table class="table table-striped">
                <thead>
                <th>Banco / Bank</th>
                <th>Cuenta / Account</th>
                <th>Moneda / Currency</th>
                <th>Referencia / Reference</th>
                <th>Representante / Representative</th>
                </thead>
                <?foreach($context->banks as $a){?>
                <tr>
                    <td><?=$a[Banco]?></td>
                    <td><?=$a[Cuenta]?></td>
                    <td><?=$a[Moneda]?></td>
                    <td><?=$a[Referencia]?></td>
                    <td><?=$a[Representante]?></td>
                </tr>
                <?}?>
            </table>
        </div>
    </div>
    <div class="panel panel-danger">
        <div class="panel-heading panel-title">Cambiar contraseña / Change password</div>
        <div class="panel-body" >
            <form id ="change-password">
                <label>Nueva contraseña / New password</label>
                <input type="password" class="form-control require" name ="new-pwd" id ="txtNewPwd" placeholder="Enter password">
                <label>Confirme contraseña / Confirm your password</label>
                <input type="password" class="form-control require" name ="confirm-pwd" id ="txtConfirmPwd" placeholder="Retype password">
            </form>
            <br>
            <button class="btn btn-success" id ="btnChangePwd"><i class="fa fa-lock"></i> Aceptar / Accept</button>
        </div>
    </div>
</div>

<?}?>

