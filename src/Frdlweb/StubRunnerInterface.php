<?php 

namespace frdlweb;

interface StubRunnerInterface
 { 
 	public function loginRootUser($username = null, $password = null) : bool;		
	public function isRootUser() : bool;
	public function getStubVM() : StubHelperInterface;	
	public function getStub() : StubItemInterface;		
	public function __invoke() :?StubHelperInterface;	
	public function hugVM(?StubHelperInterface $MimeVM);
	public function getInvoker();	
	public function getShield();	
	public function autoloading() : void;
	public function config(?array $config = null, $trys = 0) : array;
	public function configVersion(?array $config = null, $trys = 0) : array;		
	public function getCodebase() :?\Frdlweb\Contract\Autoload\CodebaseInterface;
	public function getWebrootConfigDirectory() : string;
	public function getApplicationsDirectory() : string;
}
