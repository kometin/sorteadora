<header id="header" class="box-header sticky">
    <div class="container">
            <div class="row box-header-wrap">
                <!-- Start logo -->
                <div class="col-sm-2 col-xs-4">
                    <div id="logo" class="logos">
                        <a href="index.php" class="standard-logo pull-left">
                            <img class="logo" src="css/img/logo.JPG" width ="80" height="80" alt="logo">
                        </a>
                    </div>
                </div> <!-- //.col-sm-2 
                <!-- End logo -->

                    <!-- Start desktop nav -->
                <div class="col-sm-10 col-xs-8">
                        <nav class="main-nav pull-right">
                            <ul>
                                <?if($_SESSION[SORTUSER]){?>
                                    <?if($_SESSION[SORTCLIENT]){?>
                                        <li>
                                            <a href="myorders.php">Servicios / Orders</a>
                                        </li>
                                        <li>
                                            <a href="account.php">Su cuenta / Your account</a>
                                        </li>
                                    <?}else{?>
                                    <li class="has-child ">
                                        <a href="#">Catálogos</a>
                                        <div class="dropdown left-indent">
                                            <ul class="dropdown-items">
                                                <li>
                                                    <a href="usuarios.php">Usuarios</a>
                                                </li>
                                                <li>
                                                    <a href="clientes.php">Clientes</a>
                                                </li>
                                                <li>
                                                    <a href="operadores.php">Operadores</a>
                                                </li>
                                                <li>
                                                    <a href="servicios.php">Servicios</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="has-child ">
                                        <a href="#">Servicios</a>
                                        <div class="dropdown left-indent">
                                            <ul class="dropdown-items">
                                                <li>
                                                    <a href="ordenes.php">Órdenes de servicio</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="has-child ">
                                        <a href="#">Nómina</a>
                                        <div class="dropdown left-indent">
                                            <ul class="dropdown-items">
                                                <li>
                                                    <a href="nomina.php">Captura</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="has-child ">
                                        <a href="#">Reportes</a>
                                        <div class="dropdown left-indent">
                                            <ul class="dropdown-items">
                                                <li>
                                                    <a href="repnomina.php">Nómina</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="has-child ">
                                        <a href="#">Sistema</a>
                                        <div class="dropdown left-indent">
                                            <ul class="dropdown-items">
                                                <li>
                                                    <a href="config.php">Opciones</a>
                                                </li>
                                                <li>
                                                    <a href="calendar.php">Calendario</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <?}?>
                                    <li>
                                        <a href="login.php?action=logout">Logout</a>
                                    </li>
                                <?}else{?>
                                <li>
                                    <a href="login.php">Login</a>
                                </li>
                                <?}?>
                            </ul>
                        </nav>

                        <!-- toogle icons, which are responsible for display and hide menu in small layout -->
                        <div class="offcanvas-toggler pull-right">
                                <i id="offcanvas-opener" class="icon-menu"></i>
                                <i id="offcanvas-closer" class="icon-times"></i>
                        </div>
                </div> <!-- //.col-sm-10 -->
                    <!-- End desktop nav -->
            </div> <!-- //.row -->
     </div> <!-- //.container -->
</header>
<!-- //End Header -->