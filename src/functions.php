<?php 

namespace Meincms;

if (! function_exists('Meincms\getHooks')) {

    /**
     * Register all hook events
     *
     * @package Meincms\getHooks
     * @author Toni Haryanto
     */
    function getHooks()
    {
        $hook['pre_system'][] = function(){
            (new Hooks\PreSystemHook)->run();

            if(file_exists(APPPATH.'hooks/PreSystemHook.php'))
                (new \App\hooks\PreSystemHook)->run();
        };

        $hook['pre_controller'][] = function(){
            (new Hooks\PreControllerHook)->run();

            if(file_exists(APPPATH.'hooks/PreControllerHook.php'))
                (new \App\hooks\PreControllerHook)->run();
        };

        $hook['post_controller_constructor'][] = function(){
            (new Hooks\PostControllerConstructorHook)->run();
            
            if(file_exists(APPPATH.'hooks/PostControllerConstructorHook.php'))
                (new \App\hooks\PostControllerConstructorHook)->run();
        };

        return $hook;
    }

}