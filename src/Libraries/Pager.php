<?php namespace Meincms\Libraries;

use Symfony\Component\Yaml\Yaml;

class Pager {

	private $pageFolder = 'pages/';

	public function setPageFolder($folder)
	{
		$this->pageFolder = $folder;
	}

	public function getPageFolder()
	{
		return $this->pageFolder;
	}

	public function getPage($url = null, $parse = true)
	{
		// get page fields
		if(! $pagedata = $this->pageExist($url)){
			http_response_code(404);
			if(! $pagedata = $this->pageExist('404'))
				return false;
		}

		// The rest of uri as parameter to use inside page
		$pagedata['param_uri'] = substr_replace($url, '', 0, strlen($pagedata['uri'])+1);

		// get another md or html file as custom fields
		$files = scandir($this->pageFolder.$pagedata['uri']);

		foreach ($files as $file) {
			if(is_dir($this->pageFolder.$pagedata['uri'].'/'.$file)) continue;

			$filepath = pathinfo($this->pageFolder.$pagedata['uri'].'/'.$file);

			// Get file with extension .md, .html and twig
			if(in_array($filepath['extension'], ['php','md','html','twig']))
			{
				// Prepare page file
				$pagedata['filepath'] = $this->pageFolder.$pagedata['uri'].'/'.$file;
			}
		}

		$pagedata['url'] = site_url($pagedata['uri']);
		$file_segment = explode('/', $pagedata['uri']);
		if(! empty($pagedata['uri'])){
			$pagedata['slug'] = array_pop($file_segment);
			if(! empty($pagedata['uri']))
				$pagedata['parent'] = implode('/', $file_segment);
		}

		return $pagedata;
	}

	private function pageExist($url = null, $remain_uri = '')
	{
		if(file_exists(realpath($this->pageFolder.$url.'/properties.yml')))
		{
			$pagedata = Yaml::parseFile(realpath($this->pageFolder.$url.'/properties.yml'));
			$pagedata['uri'] = $url;

			if(!empty($remain_uri))
				if($pagedata['strict_uri'] ?? false)
					return false;

			return $pagedata;

		} else {
			if(!empty($url)){
				$url = explode('/', $url);
				$remain = array_pop($url);
				$url = implode('/', $url);
				return $this->pageExist($url, $remain);
			}
		}

		return false;
	}

	public function render($page, $return = false)
	{
		extract($page);
		ob_start();
		include($this->pageFolder.$page['filepath']);
		$buffer = ob_get_contents();
		@ob_end_clean();
		
		if($return)
			return $buffer;

		echo $buffer;
	}

}