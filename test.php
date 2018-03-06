<?php

ini_set('display_errors', 1);

require_once('lib/ext.php');

echo "TEST ";

echo SendMail("cbaltazarc@guanajuato.gob.mx", "Testing...", "Sr", "xxxxxx");
