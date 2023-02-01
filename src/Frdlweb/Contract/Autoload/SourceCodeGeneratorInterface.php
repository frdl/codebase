<?php
namespace Frdlweb\Contract\Autoload {
 if (!interface_exists(SourceCodeGeneratorInterface::class)) {	     
  interface SourceCodeGeneratorInterface
   {
     public function file(string $className):string;
     public function source(string $className):string;
     public function bundle(array $classes):string;   
   }
  }     
}
