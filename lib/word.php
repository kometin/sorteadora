<?php

    require_once 'phpword/PHPWord-master/src/PhpWord/Autoloader.php';
    \PhpOffice\PhpWord\Autoloader::register();

    use PhpOffice\PhpWord\TemplateProcessor;
    
    class PhpWordTemplate extends TemplateProcessor{
        var $template_from;
        var $file_target;
        var $array_data; 
        var $templateWord;
        var $success;
        
        public function __construct($documentTemplate, $documentTarget, $data) {
//            parent::__construct($documentTemplate);
            
            $ext = strtolower(end(explode(".", $documentTemplate)));
            if ($ext == "docx"){
                if($documentTarget != $documentTemplate){
                    $this->template_from = $documentTemplate;
                    $this->file_target = $documentTarget;
                    $this->array_data = $data;
                    $this->templateWord = new TemplateProcessor($this->template_from);
                    $this->SetData();
                    $this->SaveFile();
                    $this->success = file_exists($documentTarget);
                }else{
                    throw new Exception('Los documentos origen y destino deben ser distintos');
                }
            }else{
                throw new Exception('Tipo de archivo no soportado. Solo docx');
            }
        }
        
        private function SetData(){
            foreach($this->array_data as $k => $v){
                $this->templateWord->setValue($k, $v);
            }
        }
        
        private function SaveFile(){
            $this->templateWord->saveAs($this->file_target);
        }
    }

    