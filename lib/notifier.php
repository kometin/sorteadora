<?php

require_once ('DBConn.php');
require_once ('ext.php');


/**
 * Description of notifier
 *
 * @author christian
 */
class Notifier {
    private $notif; 
    private $param;
    private $db;
    private $data;
    private $text;
    private $subject;
    private $message;
    private $recipients;
    
    function __construct(){
        $this->db = new DBConn();
    }
    
    public function PlanUpdate($param) {
        $sql ="select Empleado, Correo, t.Fecha_Inicio, t.Fecha_Fin "
                . "from estructura_operativa e "
                . "join plan_tutorial t on t.ID_Emp = e.ID_Emp "
                . "where ID_Plan = " . $param;
        $this->data = $this->db->getObject($sql);
        if($this->data){
            $this->text = "Por medio del presente se le notifica que ha sido <b>CREADO o ACTUALIZADO</b> el plan tutorial de personal "
                    . "asignado a ustéd para el periodo del " . SimpleDate($this->data->Fecha_Inicio) . " al " . SimpleDate($this->data->Fecha_Fin) . ". "
                    . "<p>Favor de revisar los detalles del mismo en nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, "
                    . "módulo de <b>Capacitación - Plan tutorial</b></p>";
            $this->subject = "Aviso de Plan Tutorial";
            $this->message = "Plan Tutorial CREADO o ACTUALIZADO ahora";
            $this->AddRecipient($this->data->Correo, $this->data->Empleado);
        }
        $this->SendNotif();
    }
    
    public function PlanValidate($param) {
        $sql ="select e1.Empleado, e2.Correo, e2.Empleado as Tutor, t.Fecha_Inicio, t.Fecha_Fin "
                . "from plan_tutorial t "
                . "join estructura_operativa e1 on e1.ID_Emp = t.ID_Emp "
                . "join estructura_operativa e2 on e2.ID_Emp = t.ID_Tutor "
                . "where ID_Plan = " . $param;
        $this->data = $this->db->getObject($sql);
        if($this->data){
            $this->text = "Por medio del presente se le notifica que ha sido <b>VALIDADO</b> el plan tutorial de personal "
                    . "relacionado con <b>" . $this->data->Empleado . "</b> para el periodo del " . SimpleDate($this->data->Fecha_Inicio) . " al " . SimpleDate($this->data->Fecha_Fin) . ". "
                    . "<p>Favor de revisar los detalles del mismo en nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, "
                    . "módulo de <b>Capacitación - Plan tutorial</b></p>";
            $this->subject = "Aviso de Plan Tutorial";
            $this->message = "Plan Tutorial VALIDADO ahora";
            $this->AddRecipient($this->data->Correo, $this->data->Tutor);
        }
        $this->SendNotif();
    }
    
    public function PlanReject($param) {
        $sql ="select e1.Empleado, e2.Correo, e2.Empleado as Tutor, t.Fecha_Inicio, t.Fecha_Fin "
                . "from plan_tutorial t "
                . "join estructura_operativa e1 on e1.ID_Emp = t.ID_Emp "
                . "join estructura_operativa e2 on e2.ID_Emp = t.ID_Tutor "
                . "where ID_Plan = " . $param;
        $this->data = $this->db->getObject($sql);
        if($this->data){
            $this->text = "Por medio del presente se le notifica que ha sido <b>RECHAZADO</b> el plan tutorial de personal "
                    . "relacionado con <b>" . $this->data->Empleado . "</b> para el periodo del " . SimpleDate($this->data->Fecha_Inicio) . " al " . SimpleDate($this->data->Fecha_Fin) . ". "
                    . "<p>Favor de revisar los detalles del mismo en nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, "
                    . "módulo de <b>Capacitación - Plan tutorial</b></p>";
            $this->subject = "Aviso de Plan Tutorial";
            $this->message = "Plan Tutorial RECHAZADO ahora";
            $this->AddRecipient($this->data->Correo, $this->data->Tutor);
        }
        $this->SendNotif();
    }
    
    public function PlanEval($param) {
        $sql ="select e1.Empleado, e1.Correo, t.Fecha_Inicio, t.Fecha_Fin "
                . "from plan_tutorial t "
                . "join estructura_operativa e1 on e1.ID_Emp = t.ID_Emp "
                . "where ID_Plan = " . $param;
        $this->data = $this->db->getObject($sql);
        if($this->data){
            $this->text = "Por medio del presente se le notifica que ha sido <b>CALIFICADO</b> el plan tutorial de personal "
                    . "asignado a udtéd para el periodo del " . SimpleDate($this->data->Fecha_Inicio) . " al " . SimpleDate($this->data->Fecha_Fin) . ". "
                    . "<p>Por lo cual es necesario que prosiga con la <b>EVALUACIÓN DEL TUTOR</b> a continuación, "
                    . "dentro de nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, "
                    . "módulo de <b>Capacitación - Plan tutorial</b></p>";
            $this->subject = "Aviso de Plan Tutorial";
            $this->message = "Se requiere realizar la EVALUACIÓN DEL TUTOR";
            $this->AddRecipient($this->data->Correo, $this->data->Empleado);
        }
        $this->SendNotif();
    }
    
    public function PlanDictamen() {
        $sql ="select Correo from empleados e "
                . "left join perfiles p on p.ID_Perfil = e.ID_Perfil "
                . "where e.Activo = 1 and (p.Permisos like '%22@%' OR e.Permisos like '%22@%')";
        $this->data = $this->db->getArray($sql);
        $this->message = "Nuevo Plan Tutorial por DICTAMINAR";
        foreach($this->data as $d)
            $this->AddRecipient($d['Correo']);
        $this->SendNotif();
    }
    
    public function PlanFinish($param) {
        $sql ="select e1.Empleado, e1.Correo as CorreoColab, e2.Correo as CorreoTutor, e2.Empleado as Tutor, t.Fecha_Inicio, t.Fecha_Fin "
                . "from plan_tutorial t "
                . "join estructura_operativa e1 on e1.ID_Emp = t.ID_Emp "
                . "join estructura_operativa e2 on e2.ID_Emp = t.ID_Tutor "
                . "where ID_Plan = " . $param;
        $this->data = $this->db->getObject($sql);
        if($this->data){
            $this->text = "Por medio del presente se le notifica que ha <b>FINALIZADO</b> el proceso del plan tutorial de personal "
                    . "relacionado con <b>" . $this->data->Empleado . "</b> para el periodo del " . SimpleDate($this->data->Fecha_Inicio) . " al " . SimpleDate($this->data->Fecha_Fin) . ", "
                    . "y se encuentran disponibles ahora las <b>CONSTANCIAS</b> generadas por el mismo. "
                    . "<p>Puede revisarlas dentro de nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, "
                    . "módulo de <b>Capacitación - Plan tutorial</b></p>";
            $this->subject = "Aviso de Plan Tutorial";
            $this->message = "Plan Tutorial FINALIZADO ahora";
            $this->AddRecipient($this->data->CorreoColab, $this->data->Empleado);
            $this->AddRecipient($this->data->CorreoTutor, $this->data->Tutor);
        }
        $this->SendNotif();
    }
    
    public function CapUpdate() {
        $sql ="select Correo from empleados e "
                . "left join perfiles p on p.ID_Perfil = e.ID_Perfil "
                . "where e.Activo = 1 and (p.Permisos like '%21@%' OR e.Permisos like '%21@%')";
        $this->data = $this->db->getArray($sql);
        $this->message = "Solicitud de Capacitación ENVIADA";
        foreach($this->data as $d)
            $this->AddRecipient($d['Correo']);
        $this->SendNotif();
    }
    
    public function CapReject($param) {
        $sql = "select Empleado, Correo, Tema, u.Nombre "
            . "from capacitacion_ur c "
            . "join cat_unidadesresponsables u on u.ID_UR = c.ID_UR "
            . "join estructura_operativa e on e.ID_Emp = c.ID_Solicita "
            . "where ID_Cap = " . $param;
        $this->data = $this->db->getObject($sql);
        if($this->data){
            $this->text = "Por medio del presente se le notifica que la capacitación solicitada para la UR <b>" . $this->data->Nombre . "</b> "
                    . "sobre el tema: <b>" . $this->data->Tema . "</b>, ha sido <b>RECHAZADA</b>."
                    . "<p>Puede revisar los detalles dentro de nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, "
                    . "módulo de <b>Capacitación - Capacitación UR</b></p>";
            $this->subject = "Aviso de Capacitación";
            $this->message = "Capacitación RECHAZADA ahora";
            $this->AddRecipient($this->data->Correo, $this->data->Empleado);
        }
        $this->SendNotif();
    }
    
    public function CapValidate($param) {
        $stat_perm = array(4 => 22, 6 => 23);
        $sql ="select Correo from empleados e "
                . "left join perfiles p on p.ID_Perfil = e.ID_Perfil "
                . "where e.Activo = 1 and (p.Permisos like '%" . $stat_perm[$param] . "@%' OR e.Permisos like '%" . $stat_perm[$param] . "@%')";
        $this->data = $this->db->getArray($sql);
        $this->message = "Solicitud de Capacitación POR VALIDAR";
        foreach($this->data as $d)
            $this->AddRecipient($d['Correo']);
        $this->SendNotif();
    }
    
    public function EstructuraReject($param) {
        $sql = "select Tipo_Solicitud, Fecha_Solicitud, Empleado, Correo "
            . "from solicitudes_estructura s "
            . "join estructura_operativa e on e.ID_Emp = s.ID_Solicita "
            . "where ID_Solicitud = " . $param;
        $this->data = $this->db->getObject($sql);
        if($this->data){
            $this->text = "Por medio del presente se le notifica que su solicitud de <b>" . $this->data->Tipo_Solicitud . "</b> "
                    . "en la estructura con fecha de <b>" . SimpleDate($this->data->Fecha_Solicitud) .  "</b>, ha sido <b>RECHAZADA</b>"
                    . "<p>Puede revisar los detalles dentro de nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, "
                    . "módulo de <b>Estructura - Solicitudes</b></p>";
            $this->subject = "Aviso de Solicitud de Estructura";
            $this->message = "Solicitud de estructura RECHAZADA ahora";
            $this->AddRecipient($this->data->Correo, $this->data->Empleado);
        }
        $this->SendNotif();
    }
    
    public function EstructuraValidate($param) {
        $sql = "select Tipo_Solicitud, Fecha_Solicitud, s.Estatus, Empleado, Correo "
            . "from solicitudes_estructura s "
            . "join estructura_operativa e on e.ID_Emp = s.ID_Solicita "
            . "where ID_Solicitud = " . $param;
         $this->data = $this->db->getObject($sql);
         if($this->data){
            $this->text = "Por medio del presente se le notifica que su solicitud de <b>" . $this->data->Tipo_Solicitud . "</b> "
                    . "en la estructura con fecha de <b>" . SimpleDate($this->data->Fecha_Solicitud) .  "</b>, ha sido <b>VALIDADA</b>, "
                    . ($this->data->Estatus==1?"y ha sido ENVIADA para su aprobación por la DGRH":"y ya se han APLICADO los cambios solicitados")
                    . "<p>Puede revisar los detalles dentro de nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, "
                    . "módulo de <b>Estructura - Solicitudes</b></p>";
            $this->subject = "Aviso de Solicitud de Estructura";
            $this->message = "Solicitud de estructura RECHAZADA ahora";
            $this->AddRecipient($this->data->Correo, $this->data->Empleado);
        }
        $this->SendNotif();
    }
    
    public function EstructuraUpdate() {
        $sql ="select Correo from empleados e "
                . "left join perfiles p on p.ID_Perfil = e.ID_Perfil "
                . "where e.Activo = 1 and (p.Permisos like '%2@%' OR e.Permisos like '%2@%')";
        $this->data = $this->db->getArray($sql);
        $this->message = "Solicitud de Estructura ENVIADA";
        foreach($this->data as $d)
            $this->AddRecipient($d['Correo']);
        $this->SendNotif();
    }
    
    public function AsistReminder($param) {
        $this->text = "Por medio del presente mensaje, se le recuerda que debe dar cumplimiento al proceso de <b>REVISIÓN DE ASISTENCIA PARA EL PERSONAL A SU CARGO</b> "
            . "<p>Lo anterior con fundamento en el registro de incidencias reportadas en el Sistema Integral de Personal a la fecha.</p>"
            . "<p>Para continuar puede acceder a nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, "
            . "módulo de Asistencia - Control de asistencia - Revisión</p>";
        $sql = "select Correo, Empleado from estructura_operativa where ID_Emp in (" . implode(",", $param) . ")";
        $this->subject = "Revisión de Asistencia";
        foreach($this->db->getArray($sql) as $a)
            $this->AddRecipient ($a['Correo'], $a['Empleado']);
        $this->SendNotif();
    }
    
    public function AsistReview($param) {
        $sql = "select e.Empleado, IFNULL(e2.Empleado, e3.Empleado) as Jefe, IFNULL(e2.Correo, e3.Correo) as  Correo "
            . "from estructura_operativa e "
            . "left join estructura_operativa e2 on e2.Secuencial = e.SecuencialPadre and e2.ID_Emp is not null "
            . "left join asistencia_suplentes s on s.ID_Emp = e.ID_Emp "
            . "left join estructura_operativa e3 on e3.ID_Emp = s.ID_Jefe "
            . "where e.ID_Emp = " . $param;
        $this->data = $this->db->getObject($sql);
        if($this->data->Jefe){
            $this->text = "Por medio del presente mensaje, se le notifica que el empleado <b>" . $this->data->Empleado . "</b>, ha enviado 
                            una o más justificaciones de asistencia que requieren de su atención y revisión.
                            <p>Para realizar esto puede acceder a nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, 
                            módulo de Asistencia - Control de asistencia - Revisión</p>";
            $this->subject = "Revisión de Justificaciones";
            $this->AddRecipient($this->data->Correo, $this->data->Jefe);
        }
        $this->SendNotif();
    }
    
    public function AsistReject($param) {
        $sql = "select Empleado, Correo from estructura_operativa where ID_Emp = " . $param;
        $this->data = $this->db->getObject($sql);
        $this->text = "Por medio del presente mensaje, se le notifica que se han <b>RECHAZADO</b> algunas de sus justificaciones enviadas.
                    <p>Posiblemente con alternativa para hacer los ajustes o cambios pertinentes y proceder nuevamente.</p>
                    <p>Para revisar los detalles de esto puede acceder a nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, 
                     módulo de Asistencia - Control de asistencia</p>";
        $this->subject = "Justificaciones Rechazadas";
        $this->AddRecipient($this->data->Correo, $this->data->Empleado);
        $this->SendNotif();
    }
    
    public function PropuestaUpdate() {
        $sql ="select Correo, CONCAT_WS(' ', Nombre, Apellido_Paterno) as Nombre from empleados e "
            . "left join perfiles p on p.ID_Perfil = e.ID_Perfil "
            . "where e.Activo = 1 and (p.Permisos like '%32@%' OR e.Permisos like '%32@%')";
        $this->data = $this->db->getArray($sql);
        $this->subject = "Recepción de propuesta de mejora e innovación";
        $this->text = "Por medio del presente mensaje, se le informa que se ha recibido o actualizado una nueva propuesta dentro "
                    . "del <b>Portal de Mejora e Innovación</b>, la cual requiere de su revisión y/o aprobación para continuar. "
                    . "<p>Para revisar los detalles de esto puede acceder a nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, 
                        módulo de Inicio - Mejoras e innovación</p>";
        $this->message = "Nueva propuesta de mejora";
        foreach($this->data as $d)
            $this->AddRecipient($d['Correo'], $d['Nombre']);
        $this->SendNotif();
    }
    
    public function PropuestaReject($param) {
        $sql = "select Correo, Empleado from estructura_operativa where ID_Emp = " . $param;
        $this->data = $this->db->getObject($sql);
        $this->subject = "Prouesta de Mejora Rechazada";
        $this->text = "Por medio del presente mensaje se le comunica que su propuesta de mejora enviada a travéz del portal "
                    . "ha sido <b>RECHAZADA</b> por el administrador. "
                    . "<p>Para revisar los detalles de esto puede acceder a nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, 
                        módulo de Inicio - Mejoras e innovación</p>";
        $this->AddRecipient($this->data->Correo, $this->data->Empleado);
        $this->SendNotif();
    }
    
    public function PropuestaValidate($param) {
        $sql = "select Correo, Empleado from estructura_operativa where ID_Emp = " . $param;
        $this->data = $this->db->getObject($sql);
        $this->subject = "Prouesta de Mejora Aprobada";
        $this->text = "Por medio del presente mensaje se le comunica que su propuesta de mejora enviada a travéz del portal "
                    . "ha sido <b>APROBADA</b> por el administrador y en breve será procesada para la asgianción de un "
                    . "responsable de seguimiento."
                    . "<p>Puede seguir los detalles de esto en nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, 
                        módulo de Inicio - Mejoras e innovación</p>";
        $this->AddRecipient($this->data->Correo, $this->data->Empleado);
        $this->SendNotif();
    }
    
    public function PropuestaPublish($param) {
        $sql = "select Correo, Empleado from estructura_operativa where ID_Emp = " . $param;
        $this->data = $this->db->getObject($sql);
        $this->subject = "Prouesta de Mejora Publicada";
        $this->text = "Por medio del presente mensaje se le comunica que su propuesta de mejora enviada a travéz del portal "
                    . "ha sido <b>PUBICADA</b> y ahora se encuentra publicada para todo el personal dentro del mismo portal."
                    . "<p>Puede seguir los detalles de esto en nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, 
                        módulo de Inicio - Mejoras e innovación</p>";
        $this->AddRecipient($this->data->Correo, $this->data->Empleado);
        $this->SendNotif();
    }
    
    public function SendVacations($param) {
        $sql = "select e.Empleado, IFNULL(e2.Empleado, e3.Empleado) as Jefe, IFNULL(e2.Correo, e3.Correo) as  Correo "
            . "from estructura_operativa e "
            . "left join estructura_operativa e2 on e2.Secuencial = e.SecuencialPadre and e2.ID_Emp is not null "
            . "left join asistencia_suplentes s on s.ID_Emp = e.ID_Emp "
            . "left join estructura_operativa e3 on e3.ID_Emp = s.ID_Jefe "
            . "where e.ID_Emp = " . $param;
        $this->data = $this->db->getObject($sql);
        if($this->data->Jefe){
            $this->text = "Por medio del presente mensaje, se le notifica que el empleado <b>" . $this->data->Empleado . "</b>, 
                           ha enviado o actualizado su solicitud de vacaciones, por lo cúal se requiere de su revisión y validación
                           para continuar con su proceso.
                           <p>Para realizar esto puede acceder a nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, 
                           módulo de <b>Prestaciones - Vacaciones - Validar - Vacaciones de mi personal</b></p>";
            $this->subject = "Validación de vacaciones";
            $this->AddRecipient($this->data->Correo, $this->data->Jefe);
        }
        $this->SendNotif();
    }
    
    public function RejectVacations($param) {
        $sql = "select Correo, Empleado from estructura_operativa e 
                join vacaciones v on v.ID_Emp = e.ID_Emp 
                where ID_Vac = " . $param;
        $this->data = $this->db->getObject($sql);
        if($this->data->Correo){
            $this->text = "Por medio del presente mensaje, se le notifica que han sido rechazadas algunas de sus fechas en su solicitud 
                           de vacaciones. Puede entrar a revisar las observaciones en cada uno de los días marcados para corregir.
                           <p>Acceda a nuestro <a href = '" . SYSTEM_LINK . "'>Sistema de Personal</a>, módulo de <b>Prestaciones - Vacaciones - Solicitar / Validar</b></p>";
            $this->subject = "Vacaciones rechazadas";
            $this->AddRecipient($this->data->Correo, $this->data->Empleado);
        }
        $this->SendNotif();
    }

    public function ValidateVacations() {
        $sql ="select Correo from empleados e "
                . "left join perfiles p on p.ID_Perfil = e.ID_Perfil "
                . "where e.Activo = 1 and (p.Permisos like '%36@%' OR e.Permisos like '%36@%')";
        $this->data = $this->db->getArray($sql);
        $this->message = "Solicitud de Vacaciones ENVIADA";
        foreach($this->data as $d)
            $this->AddRecipient($d['Correo']);
        $this->SendNotif();
    }


/////////////// PRIVATE METHODS /////////////////////////////////////////////////    
    private function AddRecipient($mail, $name = null){
        $this->recipients[] = array("MAIL" => $mail, "NAME" => $name);
    }


    private function SendNotif(){
        if($this->recipients){
             foreach ($this->recipients as $r){
                 if($this->text)
                     insertMail("Sistema de Personal", $r['MAIL'], $this->subject, $this->text, $r['NAME']);
                 if($this->message)
                     insertIntranet($r['MAIL'], "Sistema de Personal", $this->message, SYSTEM_LINK);
             }
        }
    }
}
