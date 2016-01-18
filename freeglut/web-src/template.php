<?php
global $site_title, $title, $sidebarentries;
$site_title = "The freeglut Project";
$title = $site_title . " :: The Next Generation of GLUT";

# 1's designate spaces in the sidebar
# Format for an entry:
# array(name=>"Name of Page", page=>"Filename/URL"), 
#
$sidebarentries = array(0, # We skip this entry anyway...
	array("name"=>"About",        "page"=>"/index.php"), 
	array("name"=>"News",         "page"=>"/news.php"), 
	array("name"=>"Progress",     "page"=>"/progress.php"),
	array("name"=>"Help Out",     "page"=>"/help.php"),
	1,
	array("name"=>"Download",     "page"=>"/index.php#download"),
	1,
	array("name"=>"Install",      "page"=>"/docs/install.php"),
	array("name"=>"API",          "page"=>"/docs/api.php"),
	array("name"=>"OpenGL Wikibook",     "page"=>"http://en.wikibooks.org/wiki/OpenGL_Programming"),
	1,
	array("name"=>"Report a Bug",        "page"=>"http://sourceforge.net/p/freeglut/bugs/"),
	array("name"=>"Request a Feature",   "page"=>"http://sourceforge.net/p/freeglut/feature-requests/"),
	array("name"=>"Project Interface",   "page"=>"http://sourceforge.net/projects/freeglut/")
);

function generateHeader($self) 
{
	global $title;

	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	echo "<?xml-stylesheet href=\"http://freeglut.sourceforge.net/freeglut-style.css\" type=\"text/css\"?>\n";
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="http://freeglut.sourceforge.net/freeglut-style.css" />
	<title><? echo $title ?></title>
</head>
<body>
<?php
	generateSideBar($self);
}

function generateFooter() 
{
?>
</body>
</html>
<?php
}

function printMenuItem($item, $current_page) 
{
	if ($item == 1)
		echo "\t<br/>\n";
	else if ($current_page == $item["page"])
		echo "\t<div class=\"navbar-item\"><i>$item[name]</i></div>\n";	
	else
		echo "\t<div class=\"navbar-item\"><a class=\"navbar-item\" href=\"$item[page]\">$item[name]</a></div>\n";	
}

function generateSideBar($current_page) 
{
	global $sidebarentries;

	# Print out the logo and title
?>
<div class="navbar-left">
	<span class="navbar-header"><a href="/"><img src="/images/freeglut_logo.png" alt="freeglut logo" style="border: 0;" /></a></span><br/>
	<span class="navbar-smalltext">The Free OpenGL Utility Toolkit</span><br/><br/>
<?php
	# Print out each sidebar entry one by one...
	reset($sidebarentries);
	while (next($sidebarentries))
		printMenuItem(current($sidebarentries), $current_page);
	
	# This allows a user to view our PHP source... it's in SVN anyway, and why not spread the love of
	# slick web page design? ;)
	$svn_location = "http://freeglut.svn.sourceforge.net/viewvc/freeglut/trunk/freeglut/web-src$current_page?view=markup";
	
	# Print the SourceForge logo button and the "View source" link	
?>
	<br/>
	<span class="navbar-header">
		<a href="http://www.opengl.org/">
		<img src="/images/opengl.png" alt="OpenGL.org" height="31" width="88" style="border: 0;"/></a>
	</span>
	<span class="navbar-header">
		<a href="http://sourceforge.net/">
 		<img src="http://sourceforge.net/sflogo.php?group_id=1032" width="88" height="31" alt="SourceForge" style="border: 0;" /></a>
	</span>
	<span class="navbar-header">
		<a href="http://validator.w3.org/check/referer">
		<img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0!" height="31" width="88" style="border: 0;"/></a>
	</span>
	<br/><br/>
	<span class="navbar-smalltext" style="font-style: normal;">
	<? echo "\t<a href=\"$svn_location\">View PHP Source</a>"; ?>
	</span>
</div>
<?php
}

function setPageTitle($title_inc) 
{
	global $site_title, $title;
	$title = $site_title . " :: " . $title_inc;
}

?>
