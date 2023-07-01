<?php 

namespace Frdlweb;

 interface StubHelperInterface
 { 
  public function runStubs($stubs=null);
  public function addPhpStub($code, $file = null);	 
  public function addWebfile($path, $contents, $contentType = 'application/x-httpd-php', $n = 'php');	 
  public function addClassfile($class, $contents);
  public function get_file($part, $file, $name); 
  public function Autoload($class);   
  public function __toString();	
  public function __invoke(); 	
  public function __call($name, $arguments);
  public function getFileAttachment($file = null, $offset = null, ?bool $throw = true);	
  public function hugRunner(mixed $runner);
  public function getRunner();
  //public function _run_php_1(StubItemInterface $part, $class = null, ?bool $lint = null);
 }
