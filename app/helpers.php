<?php

require_once "autoload.php";

/**
* 
*/
class Inflate extends App
{
	
	function __construct($class="")
	{
		if( !empty($class) )
		{
			if( strpos($class, '@') != 0 )
			{
				$classexplode = explode('@', $class);
				$classname = $classexplode[0];
				$classmethod = $classexplode[1];
				$newClass = new $classname;
				if( method_exists($newClass, $classmethod) )
				{
					return $newClass->$classmethod();
				}
				else
				{
					echo 'Method not found in class: ' . $class;
					return false;
				}
			}
		}
	}
}


function view($view="", $vars=[])
{
	return App::view($view, $vars);
}

function is_active($page="")
{
	global $url, $path, $uri;
	if(!empty($page))
	{

		$page = str_replace(" ", "-", $page);

		$url = Route::url();
		$uri = Route::uri();

		$page_offset = basename($url);

		// echo $page . "=" . $uri . '=' . $page_offset . '';
		// if( ($url == $path || $page == $uri) 
		if( ($page == $uri) 
			|| ($page_offset == $uri && $uri == $page) )
		// if( $page == $uri )
		{
			return true;
		}
	}
	
	return false;
}


function is_route($page=""){
	return is_active($page);
}


function env_url($url="./"){
	$compile = isset($_REQUEST['compile']) ? true : false;

	$r_url = $url;
	if( !empty($url) ){
		if( $url == "./" ){
			$r_url = $compile ? "index" : $url;
		}else{
			$r_url = $url;
		}

		$r_url .= $compile ? ".html" : "";
	}


	return $r_url;
}



function active_class($page="",$extra_class=" ")
{
	if(!empty($page))
	{
		$class = 'class="';
		$is_active = false;

		// echo Route::uri();

		if(strpos($page, " ") > 0)
		{
			$case = explode(" ", $page);
			for($i = 0; $i < count($case); $i++)
			{
				if( is_active($case[$i]) )
				{
					$is_active = true;
					$class .= ' active p-' . $case[$i];
				}
			}
		}
		else
		{
			if( is_active($page) )
			{
				$class .= ' active';
				$is_active = true;
			}
		}

		$class .= '"';


		if( $is_active )
		{
			return $class . " " . $extra_class;
		}
	}

	return "";
}













$compile = isset($_GET['compile']) ? true : false;
	
function compile()
{
	$uri = Route::uri();
	$uri = empty($uri) || $uri == "/" ? "index" : $uri;
	$posted = false;

	if( isset($_REQUEST['file']) && !empty($_REQUEST['file']) )
	{
		$posted = true;
		$file = $_REQUEST['file'];
	}

	if( $posted )
	{
		echo createFile($file, $uri);
	}

}

function createFile($file="", $uri="")
{
	$uri = empty($uri) ? "index" : $uri;
	$myfile = fopen("compile/". $uri . '.html', "w") or die("Unable to open file!");
	$file = str_replace('&lt;/textarea&gt;', '</textarea>', $file);
	$file=utf8_encode($file);
	$file="\xEF\xBB\xBF".$file;
	$txt = $file;
	fwrite($myfile, $txt);
	fclose($myfile);
	$uri_redir = $uri == "index" ? "?" : $uri;
	return "<span style='display:block;padding: 20px;background: #71ADAF; color: #fff;'>File '$uri' created. 
			Redirecting...</span>
			<script>setTimeout(function(){window.location='$uri_redir';},2000);</script>";
}



















/**
* Error classes
*/
class Error extends App
{
	
	function __construct()
	{
		return false;
	}


	public static function ViewNotFound($view, $vars="")
	{
		$view = App::viewName($view, $vars);

		$error = "";
		if( is_string($vars) && !empty($vars) )
		{
			$error = "<br><br>Originated in <br><pre>" . $vars . "</pre>";
		}


		return "<!-- view not found: " . $view . " -->
					<div style=\"color: red; background: #000; padding: 20px; border: 2px solid #fff\">
					<b>Alert!</b><br> View \""
					 . $view . "\" cannot be handled. " . $error . "</div>";
	}
}