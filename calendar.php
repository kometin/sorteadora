<?php
    require_once('lib/secure.php');
    require_once('lib/templates.php');
    require_once('lib/DBConn.php');
    require_once('lib/ext.php');

    $context = new Context();
    $db = new DBConn();

    if(!$action){
        $context->title = "Calendario de días festivos";

        $sql = "select IFNULL(MIN(YEAR(Fecha)), YEAR(NOW())) from calendario where Activo = 1";
        $context->min = $db->getOne($sql);

        RenderTemplate('templates/calendar.tpl.php', $context, 'templates/base.php');

    }elseif($action == "load"){
        $sql = "select Fecha from calendario where Activo = 1 and YEAR(Fecha) = " . $year;
        $inhab = array();
        foreach($db->getArray($sql) as $a)
            $inhab[] = $a['Fecha'];
        echo json_encode($inhab);

    }elseif($action == "set"){
        if($id = $db->exist("id", "calendario", "Activo = 1 and Fecha = '" . $date . "'")){
            $sql = "update calendario set Activo = 0, updated_at = NOW(), updated_by = " . $_SESSION[SORTUSER] . " where id = " . $id;
        }else{
            $sql = "insert into calendario (Fecha, Activo, updated_at, updated_by) values('" . $date . "', 1, NOW(), " . $_SESSION[SORTUSER] . ")";
        }
        $db->execute($sql);
    }
