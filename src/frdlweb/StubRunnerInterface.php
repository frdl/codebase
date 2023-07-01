<?php 

namespace frdlweb;

interface StubRunnerInterface extends StubInterface
 { 
	public function instance(?object $instance = null)  : object;
 //	public function loginRootUser($username = null, $password = null) : bool;		
//	public function isRootUser() : bool;
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
	public function getFrdlwebWorkspaceDirectory() : string;
	public function getWebrootConfigDirectory() : string;
	public function getApplicationsDirectory() : string;
	public function getRemoteAutoloader() : LoaderInterface;
	public function autoUpdateStub(string | bool $update = null, string $newVersion = null, string $url = null);
	
}
