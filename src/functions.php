<?php 

namespace Meincms;

if (! function_exists('Meincms\setupPsr4')) {
    /**
     * Helper for defining psr4
     *
     */
    function setupPsr4()
    {
        // Only Autoload PHP Files
        spl_autoload_extensions('.php'); 

        // available namespace under \app and \meincms
        spl_autoload_register(function($classname)
        {
            if( strpos($classname,'\\') !== false )
            {
                // Namespaced Classes
                $classfile = str_replace('\\','/',$classname).'.php';
                $class_segment = explode('/', $classfile);

                $scope = array_shift($class_segment);

                if($classname[0] !== '/') 
                {

                    if($scope == 'Site')
                    {
                        $classfile = SITEPATH.implode('/', $class_segment);
                        require_once($classfile);
                    }

                    elseif($scope == 'Shared')
                    {
                        $classfile = 'shared/'.implode('/', $class_segment);
                        require_once($classfile);
                    }

                    else if($scope == 'Core')
                    {
                        $classfile = COREPATH.implode('/', $class_segment);
                        require_once $classfile;
                    }
                }
            }
        });
    };
}