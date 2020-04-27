<?php

namespace Meincms\Hooks;

class DisplayOverrideHook extends BaseHook {

	protected $methods = [
		'display'
	];

	public function display()
	{
		ci()->output->_display();
	} 
}