<?php

namespace Meincms\Hooks;

/**
 * Define PreSystem hooks in one class
 * 
 * Just add new method and register its name in $methods property.
 * 
 * @package PreSystem
 * @author Toni
 */
class PreSystemHook extends BaseHook {

	protected $methods = [
		'registerWhoops', 
		'registerPsr4'
	];

	/**
	 * Register Whoops class for error reporting
	 * 
	 * @package Whoops
	 * @author Toni
	 */
	public function registerWhoops()
	{
		if(ENVIRONMENT != 'production'){
			$whoops = new \Whoops\Run;
			$handler = new \Whoops\Handler\PrettyPageHandler;
			
			$CFG =& load_class('Config', 'core');
			foreach ($CFG->item('whoops_blacklist') as $globalVar => $values)
				foreach ($values as $value)
					$handler->blacklist($globalVar, $value);

			$whoops->pushHandler($handler);
			$whoops->register();
		}
	}

	/**
	 * Register PSR-4 Autoload for CodeIgniter 3 
	 * provided by MeinCMSCore package
	 * 
	 * @package Psr4Autoload
	 * @author Toni
	 */
	public function registerPsr4()
	{
		$paths = config_item('psr4') ?? ['Core' => APPPATH];

		// Only Autoload PHP Files
        spl_autoload_extensions('.php'); 

        // available namespace under \app and \meincms
        spl_autoload_register(function($classname) use ($paths)
        {
            if( strpos($classname,'\\') !== false )
            {
                // Namespaced Classes
                $classfile = str_replace('\\','/',$classname).'.php';
                $class_segment = explode('/', $classfile);

                $scope = array_shift($class_segment);

                if($classname[0] !== '/' && !empty($paths) ) 
                {
                    foreach ($paths as $key => $path) {
                        if($scope == $key)
                        {
                            $classfile = $path.implode('/', $class_segment);
                            require_once($classfile);
                        }
                    }
                }
            }
        });
	}
}