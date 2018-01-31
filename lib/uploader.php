<?php
   
class Uploader{
    private $allowed = array("pdf", "doc", "docx", "xls", "xlsx", "jpg", "jpeg", "png", "ppt", "pptx", "zip", "rar", "txt");
    private $files;
    private $name;
    private $uploaded;
    private $directory;
    
   
    const HASH_NAME = "MD5_FILE";
    const SAME_NAME = "SAME NAME";

    public function __construct($files_to_upload, $dir, $name){
        $this->files = ($files_to_upload['name'] ? array($files_to_upload) : $files_to_upload );
        $this->directory = $dir . (substr_count($dir, "/", strlen($dir)-1) ? "" : "/" );
        $this->name = $name;
    }
    
    public function getAllowed(){
        return $this->allowed;
    }
    
    public function setAllowed($new_ext){
        if(is_array($new_ext)){
            $this->allowed = $new_ext;
        }else{
            $this->allowed = array($new_ext);
        }
    }
    
    public function addAllowed($add_ext){
        if(is_array($add_ext)){
            $this->allowed = array_merge($this->allowed, $add_ext);
        }else{
            $this->allowed[] = $add_ext;
        }
    }
    
    public function removeAllowed($quit_ext){
        if(is_array($quit_ext)){
            foreach($quit_ext as $q)
                unset( $this->allowed[array_search ($q, $this->allowed) ] );
        }else{
            unset( $this->allowed[array_search ($quit_ext, $this->allowed) ] );
        }
    }
    
    
    public function Upload(){
        foreach($this->files as $key => $f){
            $file_name = $f['name'];
            $file_path = $f['tmp_name'];
            $file_ext = strtolower(end(explode(".", $file_name)));
            if($file_name){
                if(in_array($file_ext, $this->allowed)){
                    switch($this->name){
                        case self::HASH_NAME:
                            $target = $this->directory . md5_file($file_path) . "." . $file_ext;
                            break;
                        case self::SAME_NAME:
                            $target = $this->directory . $file_name;
                            break;
                        default:
                            $target = $this->directory . str_replace(".".$file_ext, "", $this->name) . "." . $file_ext;
                            break;
                    }
                    if(move_uploaded_file($file_path, $target)){
                        $this->uploaded[] = array("FILE" => $file_name, "PATH" => $target, "EXT" => $file_ext, "SRC" => $key);
                    }else{
                        die("Error subiendo archivo: " . $file_name);
                    }
                }else{
                    die("Archivo no permitido: " . $file_name);
                }
            }else{
                die("No fue recibido el archivo");
            }
        }
        return true;
    }
    
    public function getUploaded($index = null){
        if(isset($index))
            return (object)$this->uploaded[$index];
        else
            return $this->uploaded;
    }
}
