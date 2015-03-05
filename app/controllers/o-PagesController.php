<?php

/**
* 
*/
class PagesController extends App
{
	
	function __construct()
	{
		// echo 'pages';
	}

	function home()
	{
		return App::template('index');
	}

	function about()
	{
		return $this->page("about us");
	}


	function page($page="")
	{
		global $site_title;
		if(!empty($page))
		{
			$site_title = ucfirst($page) . " | " . $site_title;
			return App::template('page', ["page" => $page]);
		}
		else
		{
			return App::missing('error');
		}
	}


}