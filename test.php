<?php

ini_set('display_errors', 1);

require_once('lib/ext.php');

$str = "123";
echo $enc =  Cipher($str, "enc");
echo "<br>";
echo $dec =  Cipher($enc, "dec");
echo "<br>";





//echo SendMail("cbaltazarc@guanajuato.gob.mx", "Testing...", "Sr", "xxxxxx");
