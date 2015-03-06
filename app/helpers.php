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
	}else{
		echo '<script>document.getElementsByTagName(\'form\')[0].submit();</script>';
	}

	if( $posted )
	{
		echo createFile($file, $uri);
	}

}


function clean_chars($string){
	$string= str_replace("“", "&ldquo;", $string);
	$string= str_replace("”", "&rdquo;", $string);
	$string= str_replace("„", "&bdquo;", $string);
	$string= str_replace("‘", "&lsquo;", $string);
	$string= str_replace("’", "&rsquo;", $string);
	$string= str_replace("‚", "&sbquo;", $string);

	return $string;
}



function xcopy($source, $dest, $permissions = 0755)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, $permissions);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        xcopy("$source/$entry", "$dest/$entry", $permissions);
    }

    // Clean up
    $dir->close();
    return true;
}





function createFile($file="", $uri="")
{


	if( !is_dir("compile/") ){
		mkdir("compile");
	}

	if( !is_dir("compile/assets/") ){
		mkdir("compile/assets/");
	}
	xcopy('assets/css/', 'compile/assets/css/');
	xcopy('assets/js/', 'compile/assets/js/');
	xcopy('assets/img/', 'compile/assets/img/');
	xcopy('assets/fonts/', 'compile/assets/fonts/');
	xcopy('share/', 'compile/share/');

	$uri = empty($uri) ? "index" : $uri;
	$myfile = fopen("compile/". $uri . '.html', "w") or die("Unable to open file!");
	$file = str_replace('&lt;/textarea&gt;', '</textarea>', $file);
	$file = clean_chars($file);
	$file=utf8_encode($file);
	$file="\xEF\xBB\xBF".$file;
	$txt = $file;
	fwrite($myfile, $txt);
	fclose($myfile);
	$uri_redir = $uri == "index" ? "?" : $uri;
	return "<span style='display:block;padding: 20px;background: #71ADAF; color: #fff;
	 position: fixed; bottom: 10px; left: 10px; right: 10px;'>
		File '$uri' created. 
			Redirecting...</span>
			<script>setTimeout(function(){window.location='$uri_redir';},2000);</script>";
}


function open_compile_template(){
	return '<html><head>
<title>Compilacion en curso</title>        
<link href=\'//fonts.googleapis.com/css?family=Lato:100,400,700\' rel=\'stylesheet\' type=\'text/css\'>
<style>
body { margin: 0; padding: 0; width: 100%; height: 100%; color: #B0BEC5; 
	display: table; font-weight: 100; font-family: \'Lato\'; }
.container { text-align: center; display: table-cell; vertical-align: middle; }
.content { text-align: center; display: inline-block; }
.title { font-size: 50px; margin-bottom: 40px; }
.quote { font-size: 24px; }
</style>
</head><body>
<div class="container">
    <div class="content">
        <div class="title">Compilando</div>
        <div class="quote">Espere mientras su archivo es compilado.</div>
<form method="post"><textarea rows="10" name="file" style="position: absolute; opacity: 0;visibility: hidden;">';
}

function close_compile_template(){
	return '</textarea data-noreplace><div class="btns" style="padding-top: 20px">
<input type="submit" value="Compilar pagina" style="position:fixed; bottom: 20px; 
left: 20px; padding: 20px; border: none; color: #fff; font-size: 18px; 
background: #94B933; z-index: 999999999999; cursor: pointer;opacity:0;visibility:hidden;"></div></form></div>';
}


function end_compile_template(){
	return '</div></body></html>';
}




function compile_snackbar(){
	$buttons = "";
	$buttonstyle = 'position:fixed; bottom: 5px; 
				left: 5px; padding: 0px 20px; border: none; color: #fff; 
				background: #94B933; z-index: 999999999999; width: 175px; 
				height: 50px; line-height: 50px; box-shadow: 0px 1px 5px rgba(0,0,0,0.2);
				font-size: 10pt; font-family: sans-serif; text-transform: uppercase; opacity: 0.6;
				transition: all 0.3s';

	$buttons .= "<a href=\"?compile\" style=\"$buttonstyle\" 
					onmouseenter=\"this.style.opacity=1\" 
					onmouseleave=\"this.style.opacity=0.6\"
					>Compilar pagina</a>";

    $uri = Route::uri();
    $uri = $uri == "" || $uri == "/" ? "index" : $uri;
    if( file_exists("compile/" . $uri . ".html") ){
    	$buttons .= "<a href=\"compile/$uri.html\" style=\"$buttonstyle; bottom: 70px\"
    					onmouseenter=\"this.style.opacity=1\" 
						onmouseleave=\"this.style.opacity=0.6\"
    					>Ver archivo</a>";
    }else{
    	$buttons .= "<span style=\"position:fixed;bottom:60px;left:5px; opacity: 0.6; background: #000;
    	color: #fff; padding: 5px; border-radius: 4px; z-index: 999999999999;  font-size: 10pt;
    	\">Pagina aun no compilada.</span>";
    }

    return $buttons;

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