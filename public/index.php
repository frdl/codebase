<?php
use Nette\Utils\Helpers;
use Melbahja\Http2\Pusher;

use function Webfan\Codebase\Server\init;

//broken version quick patch
if(isset($_GET['version']) && 'latest' !== $_GET['version']){
	unset($_GET['version']);
}

/*
die('PAUSE '.basename(__FILE__).__LINE__.$_SERVER['SERVER_ADDR']);
if(isset($_GET['version']) && 'latest' !== $_GET['version']){
	unset($_GET['version']);
}
@todo: bundle/printer...., 2oop functions
*/
function multineedle_stripos($haystack, $needles, $offset=0) {
    foreach($needles as $needle) {
        $found[$needle] = stripos($haystack, $needle, $offset);
    }
    return $found;
}
function cleanArray($didYouMeans){
		    $_i=0;
		    $didYouMeans_Clean=[];
		    foreach($didYouMeans as $suggestion){	
				if(!empty($suggestion)){
					$didYouMeans_Clean[]=$suggestion;
				}
				$_i++;
			}	
	return $didYouMeans_Clean;
}



$content='';

require __DIR__.'/../vendor/autoload.php';
 

init();

$pub_key_file = __DIR__ . ''.  \DIRECTORY_SEPARATOR. '..' .  \DIRECTORY_SEPARATOR. '.api-keys'.  \DIRECTORY_SEPARATOR. 'pub.key';
$priv_key_file = __DIR__ . ''.  \DIRECTORY_SEPARATOR. '..'.  \DIRECTORY_SEPARATOR. '.api-keys'.  \DIRECTORY_SEPARATOR. 'priv.key';

if(!is_dir(dirname($pub_key_file))){
  mkdir(dirname($pub_key_file), 0755, true);	
}

if(!is_dir(dirname($priv_key_file))){
  mkdir(dirname($priv_key_file), 0755, true);	
}

$password = 'chANgeThisToYourPassword';
$expires = 28 * 24 * 60 * 60;

if(!file_exists($pub_key_file) || !file_exists($priv_key_file) || filemtime($pub_key_file) < time() - $expires){
	$config = array(
    "digest_alg" => "sha512",
    "private_key_bits" => 4096,
    "private_key_type" => \OPENSSL_KEYTYPE_RSA,
   );
   
   // Create the private and public key
   $res = openssl_pkey_new($config);

  // Extract the private key from $res to $privKey
   openssl_pkey_export($res, $privKey, $password);

    // Extract the public key from $res to $pubKey
    $pubKey = openssl_pkey_get_details($res);
    $pubKey = $pubKey["key"];

	
	file_put_contents($pub_key_file, $pubKey);
	file_put_contents($priv_key_file, $privKey);
     //$data = 'plaintext data goes here';

// Encrypt the data to $encrypted using the public key
     //openssl_public_encrypt($data, $encrypted, $pubKey);

// Decrypt the data using the private key and store the results in $decrypted
     //openssl_private_decrypt($encrypted, $decrypted, $privKey);
}

if(isset($_GET['source']) && '@server.key'===$_GET['source']){
  header('Content-Type: text/plain');
  header('X-Frdlweb-Source-Expires: '.(filemtime($pub_key_file) + $expires));
  echo file_get_contents($pub_key_file);
  exit;
}


$per_page = 25;

$someDirs=[	
 'src',	
 'vendor',
 'packages',	
 'modules',	
	
 
 
 
];

 $loader = new \Webfan\Autoload\CodebaseLoader;
 $loader->setTempDirectory(__DIR__ . \DIRECTORY_SEPARATOR. '..'.  \DIRECTORY_SEPARATOR.'cache'.  \DIRECTORY_SEPARATOR.'codebase-loader-caches');
 $loader->reportParseErrors(false);
 
//die($loader->getCacheFile( '_classmaps').'<br />'.$loader->getCacheFile( ) );


 foreach($someDirs as $subdir){
	 $__dir=realpath(__DIR__ . \DIRECTORY_SEPARATOR. '..'.  \DIRECTORY_SEPARATOR. $subdir);
	 if(is_dir($__dir)){
	    $loader->addDirectory($__dir);
	 }
 }


 $moduleDirs = [
    __DIR__ . \DIRECTORY_SEPARATOR. '..'.  \DIRECTORY_SEPARATOR.'php-node_modules'.  \DIRECTORY_SEPARATOR,	
    __DIR__ . \DIRECTORY_SEPARATOR. '..'. \DIRECTORY_SEPARATOR. '..'.  \DIRECTORY_SEPARATOR.'php-noddy_modules'.  \DIRECTORY_SEPARATOR,	
];

$uri =  substr($_SERVER['REQUEST_URI'], strlen('/'.basename(__DIR__)), strlen($_SERVER['REQUEST_URI']) );	
	
   $u = explode('?', $uri);
   $uri = $u[0];
   $uri = str_replace(['/./', '/../'], ['', ''], $uri);
 if('/' !== $uri){
   foreach($moduleDirs as $moduleDir){	
	$file = $moduleDir . str_replace('/', \DIRECTORY_SEPARATOR, $uri);
	 	 
	   if(!file_exists($file) && file_exists($file.'.php') ){
			 $file = $file.'.php'; 
	    }
 
	    if((!file_exists($file) || ( file_exists($file) && is_dir($file) )) && file_exists($file.\DIRECTORY_SEPARATOR.'index.php') ){			
			 $file = $file.\DIRECTORY_SEPARATOR.'index.php'; 
	    }
	   
	   if(file_exists($file) && !is_dir($file) ){
				
		  $outPut = file_get_contents($file); 
		   
		   $outPut = false === strpos($outPut, base64_decode('X19oYWx0X2NvbXBpbGVyKCk7')) 
					  ? $loader->sign($outPut,[ file_get_contents($priv_key_file), $password], 'X19oYWx0X2NvbXBpbGVyKCk7', 
									 mt_rand(0, 9999999999999999).' '
									 .mt_rand(0, 9999999999999999).' '
									 .mt_rand(0, 9999999999999999))
					  : $outPut;	
			//   if(isset($_GET['test']))die($loader->verify($outPut,file_get_contents($pub_key_file),'X19oYWx0X2NvbXBpbGVyKCk7'));
			
			
			if((isset($_SERVER['HTTP_X_SOURCE_ENCODING']) && 'b64' === $_SERVER['HTTP_X_SOURCE_ENCODING'])
			    || 
			   (isset($_GET['source-encoding']) && 'b64' === $_GET['source-encoding'] )
			  ){
				$outPut = base64_encode($outPut);
			}
		
			header('Content-Type: text/plain');
		   
			$hash_check = strlen($outPut).'.'.sha1($outPut);
	    $userHash_check = sha1(((isset($_GET['salt']))?$_GET['salt']:null) .$hash_check);	
		header('X-Content-Hash: '.$hash_check);
		header('X-User-Hash: '.$userHash_check);
			
		header('Content-Md5: '.md5($outPut));
		header('Content-Sha1: '.sha1($outPut));
			
		 echo $outPut;
		 return;
	   }
   }
	 
	header( $_SERVER['SERVER_PROTOCOL']." 404 Not Found", true );
	 $content .= 'Not found';
 }
 

  $didYouMeans = [];
$cacheFile = $loader->getCacheFile( '_classmaps'); // $loader->getCacheFile(  ); //
if(empty($content)){

   
   $loader->setAutoRefresh(!file_exists($cacheFile));
  if(!file_exists($cacheFile)
	// || !\class_exists(\Wehowski\Helpers\ArrayHelper::class) 
	){
	  $loader->refresh();
	  $loader->rebuild();
  }
  $loader->register();


	
	/*
	  
	$FloodProtection = new \frdl\security\floodprotection\FloodProtection($_SERVER['REQUEST_URI'], 60, 60);	
  if($_SERVER['REMOTE_ADDR'] !== $_SERVER['SERVER_ADDR'] && $FloodProtection->check($_SERVER['REMOTE_ADDR'])){
     header("HTTP/1.1 429 Too Many Requests");
 
 	 $content='Too Many Requests, please try again later!';
  }
	*/
	
	
	
	$ExportHelper = new \Webfan\Codebase\Server\BundleExportHelper($loader,
								       file_get_contents($pub_key_file),
								       file_get_contents($priv_key_file),
								       $password);
	
	
   $classMaps = $ExportHelper->classmap(true, true); 
   $classes=$ExportHelper->classes(true, true);		
  

	
	
	
//deprecatipons @ToDo: better API e.g. PSR-15	
if(empty($content) && !isset($_REQUEST['bundle'])  && isset($_REQUEST['source']) && '*'!==$_REQUEST['source']){
   // $file =  $ExportHelper->classfile($source);
	
	$classCode = $ExportHelper->code($_REQUEST['source'],
									 isset($_REQUEST['version']) ? $_REQUEST['version'] : null,
									true,
									(
										(isset($_SERVER['HTTP_X_SOURCE_ENCODING']) && 'b64' === $_SERVER['HTTP_X_SOURCE_ENCODING'])
			                        ||  (isset($_REQUEST['source-encoding']) && 'b64' === $_REQUEST['source-encoding'])
									)
									);
	if(false!==$classCode){
		
		$outPut = $classCode;
		header('Content-Type: text/plain');
		   
			$hash_check = strlen($outPut).'.'.sha1($outPut);
	    $userHash_check = sha1(((isset($_REQUEST['salt']))?$_REQUEST['salt']:null) .$hash_check);	
		header('X-Content-Hash: '.$hash_check);
		header('X-User-Hash: '.$userHash_check);
			
		header('Content-Md5: '.md5($outPut));
		header('Content-Sha1: '.sha1($outPut));
			
		echo $outPut;
	  die();
	}//$classCode
}
	
	
	
	
	
	
//deprecatipons @ToDo: better API e.g. PSR-15	
if(isset($_GET['bundle']) && !isset($_GET['source'])){
	$_GET['source'] = $_GET['bundle'];
}elseif(isset($_GET['bundle']) && isset($_GET['source'])){
	
	
	$content.='You can ONLY SPECIFY EITHER source OR bundle!';
}

}//empty $content


if(empty($content) && isset($_GET['source']) && '*'!==$_GET['source']){

	$source = $_GET['source'];
	$source = str_replace('/', '\\', $source);
	$source = str_replace('.php', '', $source);
//	$source = trim($source, '\ ');
//	$source=addslashes($source);
	if('\\' === substr($source, 0,1)){
	  $source = substr($source,1,strlen($source	));
	}
	
	if(isset($classMaps[$source])){
	
		if(!isset($_GET['version'])){
			 $file =  $loader->file($source);
		}elseif(isset($_GET['version']) && isset($classMaps[$source][$_GET['version']])  ) {
			$file=$classMaps[$source][$_GET['version']][0];
		}elseif(isset($_GET['version']) && 'latest'===$_GET['version']){
			$lastI=false;
			foreach($classMaps[$source] as $i){
				//$content.=print_r($í,true);
				if(false===$lastI || $lastI[1] < $i[1]){
				  $lastI = $i;	
				  	$file=$i[0];
				}
			}
			
		}elseif(isset($_GET['version']) && isset($classMaps[$source][$_GET['version']])  ) {
			$file=$classMaps[$source][$_GET['version']][0];
		}
		
		
		
		
		if(!isset($file) || !file_exists($file)){
	    	  $file =  $loader->file($source);
			
	    	foreach($classMaps[$source] as $hash=> $i){	 	
 
			
				Pusher::getInstance()->set(false, 
                       'https://startdir.de/install/?source='.urlencode($source).'&version='.$hash,
						[
                             'rel'=>'alternate',
                               'as'=>false

                       ]);
		     }				
		}
		
		
		if(is_string($file) && file_exists($file) ){		
		 
		
			
			
			
		if(isset($_GET['bundle'])){	
			$code = file_get_contents($file);
			$rawFileContents = $code;
			$FileAll = (new \Nette\PhpGenerator\Extractor(file_get_contents($file)))->extractAll();
			$code = (new \Nette\PhpGenerator\PsrPrinter)->printFile($FileAll);
			if( empty(trim($code, ' <?php ')) 
			   || preg_match("/(echo|\=)([\s\t\n\r]*)\<\<\<([\w]*)[\s\t\n\r]*/", $rawFileContents)){
		    	$code = $rawFileContents;
			}
			
			
			$code = false === strpos($code, base64_decode('X19oYWx0X2NvbXBpbGVyKCk7')) 
					  ? $loader->sign($code, [ file_get_contents($priv_key_file), $password], 'X19oYWx0X2NvbXBpbGVyKCk7', 
									 mt_rand(0, 9999999999999999).' '
									 .mt_rand(0, 9999999999999999).' '
									 .mt_rand(0, 9999999999999999))
					  : $code;	
						
			if((isset($_SERVER['HTTP_X_SOURCE_ENCODING']) && 'b64' === $_SERVER['HTTP_X_SOURCE_ENCODING'])
			    || 
			   (isset($_GET['source-encoding']) && 'b64' === $_GET['source-encoding'] )
			  ){
				$code = base64_encode($code);
			}
			
			header('Content-Type: text/plain');
		

	   
	   
	    $hash_check = strlen($code).'.'.sha1($code);
	    $userHash_check = sha1(((isset($_GET['salt']))?$_GET['salt']:null) .$hash_check);	
		header('X-Content-Hash: '.$hash_check);
		header('X-User-Hash: '.$userHash_check);
			
		header('Content-Md5: '.md5($code));
		header('Content-Sha1: '.sha1($code));
			
		//	die($code);
		 echo $code;
		return;
		}else{


			$File = new \Nette\PhpGenerator\PhpFile;
			$Nss=[];
 
			try{
			$FileAll = (new \Nette\PhpGenerator\Extractor(file_get_contents($file)))->extractAll();
			}catch(\Exception $e){
			  die('Error: '.$e->getMessage());	
			}
	 
			
	        $namespaces=$FileAll->getNamespaces();
			foreach($namespaces as $ns){
			   $_classes = $ns->getClasses();
              $_functions = $ns->getFunctions();
            //  $_traits = $ns->getTraits();
				$_break = false;
				
				foreach($_classes as $_class){						
					
						if($source === ltrim($ns->getName().'\\'.$_class->getName(), '\\ ') ){
									
/* print_R($ns->getName().'\\'.$_class->getName().'<pre>');	print_R($ns->getName().'\\'.$_class->getName()); */
							if(!isset($Nss[$ns->getName().'\\'.$_class->getName()])){
							  $Nss[$ns->getName().'\\'.$_class->getName()] = $File->addNamespace($ns);
							}
						//	$File->addClass($_class);
							$_break=true;
							break;
						}					
		    	}			
				foreach($_functions as $_function){						
					
						if($source === ltrim($ns->getName().'\\'.$_function->getName(), '\\ ') ){
									
						   if(!isset($Nss[$ns->getName().'\\'.$_function->getName()])){
							  $Nss[$ns->getName().'\\'.$_function->getName()] = $File->addNamespace($ns);
							}
							
							$_break=true;
							break;
						}					
		    	}				
			    if(true===$_break || $ns->getName() === $source)break;
			}
			
				
			
	    if(true===$_break){
			$outPut = (new \Nette\PhpGenerator\PsrPrinter)->printFile($File);
			 //$outPut .= (new \Nette\PhpGenerator\PsrPrinter)->printClass($CClasscode);
		}elseif(function_exists($source)){
		            $function = \Nette\PhpGenerator\GlobalFunction::from($source);	
			       $outPut = ''. $function;
		}else{
			$outPut = (new \Nette\PhpGenerator\PsrPrinter)->printFile($File);
		}
			
			$rawFileContents = file_get_contents($file);
			
			if(0===count($Nss) || empty(trim($outPut, ' <?php ')) 
			   || preg_match("/(echo|\=)([\s\t\n\r]*)\<\<\<([\w]*)[\s\t\n\r]*/", $rawFileContents)){
		    	  $outPut = $rawFileContents;
			}

					
							
			$outPut = false === strpos($outPut, base64_decode('X19oYWx0X2NvbXBpbGVyKCk7')) 
					  ? $loader->sign($outPut, [ file_get_contents($priv_key_file), $password], 'X19oYWx0X2NvbXBpbGVyKCk7', 
									 mt_rand(0, 9999999999999999).' '
									 .mt_rand(0, 9999999999999999).' '
									 .mt_rand(0, 9999999999999999))
					  : $outPut;	
			
			
			
			if((isset($_SERVER['HTTP_X_SOURCE_ENCODING']) && 'b64' === $_SERVER['HTTP_X_SOURCE_ENCODING'])
			    || 
			   (isset($_GET['source-encoding']) && 'b64' === $_GET['source-encoding'] )
			  ){
				$outPut = base64_encode($outPut);
			}
			
			
			header('Content-Type: text/plain');
		   
			$hash_check = strlen($outPut).'.'.sha1($outPut);
	    $userHash_check = sha1(((isset($_GET['salt']))?$_GET['salt']:null) .$hash_check);	
		header('X-Content-Hash: '.$hash_check);
		header('X-User-Hash: '.$userHash_check);
			
		header('Content-Md5: '.md5($outPut));
		header('Content-Sha1: '.sha1($outPut));
			
		echo $outPut;
			
			return;
		}
	}else{
			header( $_SERVER['SERVER_PROTOCOL']." 404 Not Found", true );
		    $content.='Not found - 404 ['.__LINE__.']<br />';
			//$variants = array_keys($classMaps[$source]);
			//$content.=print_r($variants,true);
					
		$content.='<legend>Variants of '.htmlentities($source).'</legend>';
		$content.='<ul>';	
		foreach($classMaps[$source] as $hash=> $i){	 	
			$content.='<li>';	
			$content.='<a href="?source='.urlencode($source).'&amp;version='.$hash.'">'
				.date('d.m.Y h.m:s', $i[1]).' '.htmlentities($hash)
				.'</a>';	 
			$content.='</li>';	
			
				Pusher::getInstance()->set(false, 
                       'https://startdir.de/install/?source='.urlencode($source).'&version='.$hash,
						[
                             'rel'=>'alternate',
                               'as'=>false

                       ]);
		}	
		$content.='</ul>';
	}
		
		
	}else{
		//$source = str_replace('\\', '\\\\', $source);
	//die(gettype($subNamespacePart).gettype($classes));
		
		header( $_SERVER['SERVER_PROTOCOL']." 404 Not Found", true );
		
			$didYouMeans[]=Helpers::getSuggestion($classes, $source);
		 	
		    $length=strlen($source);
		     $delimiters='\\./_*'.\DIRECTORY_SEPARATOR;
		  //  $nsParts = preg_split("/[\s\t\r\n".preg_quote($delimiters)."]/", $source);
		      $nsParts = preg_split("/[^A-Za-z0-9\_]/", $source);

		foreach($classes as $className){
			 $needle = $nsParts;
			$haystack = $className;
			
		    foreach($nsParts as $subNamespacePart){
				
				$didYouMeans[]=Helpers::getSuggestion($classes, $subNamespacePart);
				//$haystack = "The quick brown fox jumps over the lazy dog.";
               // $needle = array("fox", "dog", ".", "duck")
               // (multineedle_stripos($haystack, $needle));
					
			}
			
			 $a = $length;
		     $b = -1;
		  	 $token = substr($source, $b, $a);
			 $needle[]=$token;
			 while($b<$length && $a > 0 && ++$b < --$a && strlen($token)>2){
				 $token = substr($source, $b, $a);
				 if(strlen($token)>3 ){
				    $needle[]=trim($token);
				 }
			 }
			
			$foundMatches = multineedle_stripos($haystack, $needle);
				
				
			$matches=[];
			foreach($foundMatches as $match => $data){
			
				if(!empty($match)){
				   $matches[]=$className;	
					break;
				}
			}
		
			
			 if(count($matches) > 0){
				 $didYouMeans[]= $className;
				  break;
			 }
		
			$didYouMeans=cleanArray($didYouMeans);
					
			if($didYouMeans > 25){
				break;
			}
		}
		
		     
		
		
		    
		    $didYouMeans_Clean=cleanArray($didYouMeans);
		
		
		//$content.='count($classMaps):'.count($classMaps).'<br />';
		//$content.='count($classes):'.count($classes).'<br />';
		//$content.='print_r($nsParts, true):'.print_r($nsParts, true).'<br />';
		////$content.='$length:'.$length.'<br />';
		
	if(0<count($didYouMeans_Clean)){
		$content.='Did you mean...?';
		//$content.=print_r($didYouMeans_Clean, true).'<br />'.$source.'<ul>';	
		foreach($didYouMeans_Clean as $suggestion){	 	
			$content.='<li>';	
			$content.='<a href="?source='.$suggestion.'">'.$suggestion.'</a>';	 
			$content.='</li>';	
		}	
		$content.='</ul>';
	}
	}//count($didYouMeans_Clean)

}elseif(empty($content)){
	 $page=(isset($_GET['page']))?intval($_GET['page']):1;
  $results= \Wehowski\Helpers\ArrayHelper::paginate($classes, (isset($_GET['page']))?intval($_GET['page']):1,25);
$content.='<h1>PHP Classes</h1>';	
$content.=<<<PHPCODE
<p>Legacy Webfan Install &amp; Source Code - will be updated, deprecations will be gone, new versions will come ...</p>

<p>
	To consume the PHP-Classes Endpoint in a PSR-4 Autoloading from remote strategy way you may use the 
	<a href="https://github.com/frdl/remote-psr4">frdl/remote-psr4</a> package.
</p>
	
PHPCODE;
	
$content.='<ul>';
foreach($results as $class){
	 
	$content.='<li>';
	 $content.='<a href="?source='.urlencode($class).'">'.htmlentities($class).'</a>';
	$content.='</li>';
}
$content.='</ul>';
$content.='<a href="?page='.($page-1).'">'.htmlentities('<<<').'</a> <a href="?page='.($page+1).'">'.htmlentities('>>>').'</a>';
	
}//not GET source


?><!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8" />
    <title>Install - Legacy Webfan Install &amp; Source Code - will be updated...</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script charset="utf-8" src="js/app.js"></script>
<style>
    [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-    ng-cloak {
        display: none !important;
    }
</style>

</head>
<body><webfan-cookie-consent></webfan-cookie-consent>
<?php
	echo $content;
?>
	<div>
		<frdlweb-software-licenses>
			<h1>Credits</h1>
			Some of the software we use...:<br />
			<span  frdl-if-js-remove="8">loading...</span><br />
		
		</frdlweb-software-licenses>
	</div>
	
	<div>
	 <webfan-footer-links></webfan-footer-links>
	</div>
	
<script>
(async(v,w,d,h,wcsb)=>{
 var s=d.createElement('script');
 s.setAttribute('src', 'https://cdn.startdir.de/!bundle/run/' +h+'-'+v+'/@webfan3/frdlweb/webfan-website.js');
 s.setAttribute('data-webfan-config-query', 'DEBUG.enabled=true&website.consent.ads=false');
 s.async='defer'; 
 s.onload=()=>{
	  wcsb();
 };
 d.head.prepend(s);	 
})('abc46def123467467576',window, document,  (new Date()).getYear() + '-' +  (new Date()).getMonth()+ '-' +  (new Date()).getDay(),
   async ()=>{
 
});
</script>
</body>
</html><?php
