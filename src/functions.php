<?php 

namespace Meincms;

if (! function_exists('Meincms\setupPsr4')) {
    /**
     * Helper for defining psr4
     *
     */
    function setupPsr4($paths = [])
    {
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
    };
}