<?php

namespace Frdlweb\Contract\Autoload;
 	
  interface CodebaseInterface
 { 
   const ALL_CHANNELS = '*';
   const ENDPOINT_DEFAULT = 'RemoteApiBaseUrl';
   const ENDPOINT_PROVIDER_IDENTITY_CENTRAL = 'io4.pid.central';
   const ENDPOINT_WEBFAT_CENTRAL = 'io4.webfat.central';
   const ENDPOINT_REMOTE_PUBLIC = 'io4.workspace.public';
   const ENDPOINT_REMOTE_PRIVATE = 'io4.workspace.private';
   const ENDPOINT_WORKSPACE_REMOTE = 'io4.workspace.remote';
   const ENDPOINT_INSTALLER_REMOTE = 'io4.installer.remote';
   const ENDPOINT_PROXY_OBJECT_REMOTE = 'io4.proxy-object.remote';
   const ENDPOINT_CONTAINER_REMOTE = 'io4.container.remote';
   const ENDPOINT_CONFIG_REMOTE = 'io4.config.remote';
   const ENDPOINT_MODULES_WEBFANSCRIPT_REMOTE = 'RemoteModulesBaseUrl';
   const ENDPOINT_AUTOLOADER_PSR4_REMOTE = 'RemotePsr4UrlTemplate';
   const ENDPOINT_UDAP = 'io4.udap';
   const ENDPOINT_RDAP = 'io4.rdap';
   const ENDPOINT_OIDIP = 'io4.rdap';

   const CHANNEL_LATEST = 'latest';
   const CHANNEL_STABLE = 'stable';
   const CHANNEL_FALLBACK = 'fallback';
   const CHANNEL_TEST = 'test';
   const CHANNELS =[
        self::CHANNEL_LATEST => self::CHANNEL_LATEST,
        self::CHANNEL_STABLE => self::CHANNEL_STABLE,
        self::CHANNEL_FALLBACK => self::CHANNEL_FALLBACK,
        self::CHANNEL_TEST => self::CHANNEL_TEST,
	];
   const DEFAULT_ENDPOINT_NAMES =[
        self::ENDPOINT_DEFAULT,
	self::ENDPOINT_PROVIDER_IDENTITY_CENTRAL,
        self::ENDPOINT_WEBFAT_CENTRAL,
	self::ENDPOINT_REMOTE_PUBLIC,
	self::ENDPOINT_REMOTE_PRIVATE, 
        self::ENDPOINT_WORKSPACE_REMOTE,
        self::ENDPOINT_INSTALLER_REMOTE,
        self::ENDPOINT_MODULES_WEBFANSCRIPT_REMOTE,
        self::ENDPOINT_PROXY_OBJECT_REMOTE,
	self::ENDPOINT_CONTAINER_REMOTE,
        self::ENDPOINT_AUTOLOADER_PSR4_REMOTE,
        self::ENDPOINT_UDAP,
        self::ENDPOINT_RDAP,
	self::ENDPOINT_OIDIP,
	self::ENDPOINT_CONFIG_REMOTE,
   ];
     
   public function loadUpdateChannel(mixed $StubRunner = null) : string;     
   public function getRemoteApiBaseUrl(?string $serviceEndpoint = self::ENDPOINT_DEFAULT) : string|bool;
   public function setUpdateChannel(string $channel);	 
   public function getUpdateChannel() : string;	  
   public function getRemotePsr4UrlTemplate() : string;	  
   public function getRemoteModulesBaseUrl() : string;
   public function getServiceEndpoints() : array;	 
   public function getServiceEndpointNames() : array;	  	 	 	 
   public function setServiceEndpoints(array $serviceEndpoints) : CodebaseInterface;	 
   public function setServiceEndpoint(string $serviceEndpointName,
									 string|\Closure|\callable $baseUrl, 
									 ?string $channel = self::ALL_CHANNELS) : CodebaseInterface;
 }

 
