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


	//our impact section
	function impact()
	{
		return $this->page("our impact");
	}

	//about us section
	function about()
	{
		return $this->page("about us");
	}

	function supportNetwork()
	{
		return $this->page("support network", false);
	}

	function scholars()
	{
		return $this->page("scholars", false);
	}


	function scholarsBio()
	{
		return $this->page("scholars bio", false);
	}

	function alumni()
	{
		return $this->page("alumni", false);
	}

	function sponsors()
	{
		return $this->page("sponsors", false);
	}

	function museum()
	{
		return $this->page("museum");
	}





	function boardDirectors()
	{
		return $this->page("board of directors", false);
	}





	
	//apply page
	function apply()
	{
		return $this->page("apply");
	}
	
	//Support page
	function support()
	{
		return $this->page("support");
	}

	//Support page
	function mediakit()
	{
		return $this->page("media_kit", false);
	}
	
	function donate()
	{
		return $this->page("donate", false);
	}




	//gallery page
	function gallery()
	{
		return $this->page("gallery");
	}

	
	//gallery page
	function galleryPhotos()
	{
		return $this->page("gallery photos", false);
	}

	//gallery page
	function galleryAlbums()
	{
		return $this->page("gallery albums", false);
	}

	//gallery page
	function galleryVideos()
	{
		return $this->page("gallery videos", false);
	}

		//gallery page
	function galleryVideo()
	{
		$vars = ["video" => true];
		return $this->page("gallery videos", false,$vars);
	}



	//gallery page
	function timeline()
	{
		return $this->page("timeline hola",true,["video" => true]);
	}







	//faqs page
	function faqs()
	{
		return $this->page("faqs",false);
	}

	//terms page
	function terms()
	{
		return $this->page("terms",false);
	}

	function sitemap()
	{
		return $this->page("sitemap",false);
	}

	function news()
	{
		return $this->page("news",false);
	}
	
	function press()
	{
		return $this->page("press",false);
	}

			function press_post()
	{
		return $this->page("press_post",false);
	}
	









	function page($page="", $header = true, $vars=[])
	{
		global $site_title;
		if(!empty($page))
		{
			$site_title = ucfirst($page) . " | " . $site_title;
			$vars_extra = ['header' => $header, 'page' => $page];
			$vars = array_merge($vars, $vars_extra);

			return App::template('page', $vars);
		}
		else
		{
			return App::missing('error');
		}
	}


}