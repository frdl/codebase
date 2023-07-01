<?php

namespace frdlweb;

interface StubItemInterface
{		  
	public function getMimeType();	 	
	public function getName() ;
        public function getFileName();
        public function isFile();
        public function getParts();
        public function getPartsByName( $name);
        public function addFile( $type = 'application/x-httpd-php',  $disposition = 'php',  $code= '',  $file = '',  $name= '');
        public function isMultiPart();
        public function getBody($reEncode = false, &$encoding = null);
        public function __toString();
 }
