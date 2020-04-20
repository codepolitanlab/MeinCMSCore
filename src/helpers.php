<?php

if ( ! function_exists('ci'))
{
	/**
	 * Get CodeIgniter instance
	 */
    function &ci()
    {
    	return get_instance();
    }
}