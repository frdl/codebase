<?php

namespace frdlweb;

 interface StubInterface
 { 
   public function init () : void;  
   public function moduleLocation(?string $location = null);
   public function installTo(string $location, bool $forceCreateDirectory = false, $mod = 0755) : object;	 
   public function isIndex(bool $onlyIfFirstFileCall = true) : bool;  
   public function install(?array $params = [] )  : bool|array;
   public function uninstall(?array $params = []  )  : bool|array;
   public function setDownloadSource(string $source);	 
   public function get(string $id) : object|bool;
   public function setStubIndexPhp(string $id, string $code, ?string $toFile = null)  : bool;
   public function load(string $file, ?string $as = null) : object;	 
   public function isIndexRequest() : bool; 
   public function runAsIndex(?bool $showErrorPageReturnBoolean = true) : bool|object;	
 }
