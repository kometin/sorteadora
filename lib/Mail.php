<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mail
 *
 * @author christian
 */
require_once('class.phpmailer.php');

class Mail {
    public $address_from;
    public $name_from;
    public $smtp_host;
    public $smpt_port;
    public $smtp_user;
    public $smtp_pwd;
    public $smtp_secure;
    public $subject;
    public $text;


    private $mail;
    private $html;
    private $debug;
    private $auth;

    public function __construct($debug = 1, $html = true, $auth = true){
        $this->html = $html;
        $this->debug = $debug;
        $this->auth = $auth;
        
        $this->mail = new PHPMailer(); 
        $this->mail->IsHTML($this->html);
        $this->mail->IsSMTP();
        $this->mail->SMTPDebug  = $this->debug;
        $this->mail->SMTPAuth = $this->auth;
    }
    
    public function add($to) {
        if(!is_array($to))
            $to = array($to);
        foreach($to as $t)
            $this->mail -> AddAddress ($t);
    }   
    
    public function attach($file, $name = ""){
        $this->mail->AddAttachment($file, $name);
    }
    
    public function clear(){
        $this->mail->ClearAllRecipients();
    }

    public function Send(){
        $this->mail -> From = $this->address_from;
        $this->mail -> FromName = $this->name_from;		
        $this->mail->Host =  $this->smtp_host; 
        $this->mail->Port = $this->smtp_port;
        
        $this->mail -> Subject = utf8_decode($this->subject);
        $this->mail -> Body = utf8_decode($this->text);
        
        if($this->smtp_secure)
            $this->mail->SMTPSecure = $this->smtp_secure;

        $this->mail->Username = $this->smtp_user ? $this->smtp_user : $this->address_from;
        $this->mail->Password = $this->smtp_pwd;

        //Se verifica que se haya enviado el correo con el metodo Send().
        return $this->mail->Send();	
    }
    
   
}
