<?php
header('Content-Type: text/plain');
header('Access-Control-Allow-Origin: *');

echo file_get_contents(__DIR__.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'licenses.md');
