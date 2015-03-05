<?php


/**
* 
*/
class App
{
	
	public $isRoute;
	public $content = "";

	function __construct()
	{
		global $isRoute;

		if(!$isRoute)
		{
			return Route::missing(function(){
					App::template('error');
				});
		}	
	}



	public static function view($view,$vars=[])
	{
		global $partials, $title, $slogan, $path, $site_title;

		$config_vars = ["partials" => $partials];
		array_push($config_vars, $vars);

		if( is_array($vars) && !empty($vars) )
		{
			extract($vars, EXTR_OVERWRITE, "wddx");
		}

		$view_string = $view;
		$view = str_replace(" ", "-", $view);
		$view = $partials . $view . ".html";
		if( file_exists($view) )
		{
			return App::render($view, $vars);
		}
		else
		{
			return Error::ViewNotFound($view_string,"App::view:46");
		}

	}


	public static function viewName($view,$vars=[])
	{
		global $partials, $title, $slogan, $path, $site_title;

		$config_vars = ["partials" => $partials];
		array_push($config_vars, $vars);

		if( is_array($vars) && !empty($vars) )
		{
			extract($vars, EXTR_PREFIX_SAME, "wddx");
		}


		return $view;

	}





	public static function render($file, $vars=[])
	{
		global $partials, $title, $slogan, $path, $site_title;

		$compile = isset($_GET['compile']) ? true : false;

		if( is_array($vars) && !empty($vars) ){
			extract($vars, EXTR_PREFIX_SAME, "wddx");
		}

		$ajax = Route::ajax();
		$url = Route::url();

		$content = file_get_contents($file, FILE_USE_INCLUDE_PATH);

		/*$content = preg_replace ( '/@{{(|\s)([^\']+)(|\s)}}@/', '$1', $content );*/
		$content = str_replace('@{{', '<?php ', str_replace('}}@', ' ?>', ( $content ) ));

		/*$content = preg_replace ( '/{{(|\s)([^\']+)(|\s)}}/', '$1', $content );*/
		$content = str_replace('{{', '<?php echo ', str_replace('}}', '; ?>', ( $content ) ));

		if($compile){
			$content = str_replace('</textarea>', '&lt;/textarea&gt;', $content);
		}

		$name = basename($file);
		
		return eval(' ?>' . $content . '<?php ');
		// return $content;
	}







	public static function compile_file($template, $vars=[])
	{
		global $partials, $title, $slogan, $path, $site_title;


		$compiled = view('layout/header', $vars) 
			. view($template . '.template', $vars) 
			. view('layout/footer', $vars);

		// file_put_contents($myfile, $compiled);

		return $compiled;
	}

	public static function clean($str) { 
	    $str = stripslashes ( $str ); 
	    $str = htmlentities ( $str ); 
	    $str = strip_tags ( $str ); 
	    $str = utf8_encode ( $str );
	    // $str = implode("", $str); 
	    return $str; 
	} 


	public static function create_file($template="", $vars=[])
	{
		global $partials, $title, $slogan, $path, $site_title;

		if( ! is_dir("compile") )
		{
			mkdir("compile", 0777);
		}

		chmod("compile", 0777);


		$template = basename($template);
		$file = "./compile/" . $template . ".html";

		if( file_exists($file) )
		{
			unlink($file);
		}

		$myfile = fopen($file, "w")  or die("Unable to open file!");
		chmod($file, 0755);

		// var_dump($vars);

		// fwrite($myfile, $compiled);
		// $compiled_string = "\xEF\xBB\xBF";
		// echo $contents;
		
		$contents = file_get_contents("storage/" . $template . ".html");
		$compiled_string = $contents ;

		fwrite($myfile, $compiled_string);
		fclose($myfile);

		// file_put_contents($file, $compiled_string);

		// echo App::content;

		return $contents;
	}




	public static function template($template, $vars=[])
	{
		global $partials, $title, $slogan, $path, $site_title;
		
		$compile = App::compile_file($template, $vars);
		
		echo $compile;
	}




}

