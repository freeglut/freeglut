<?PHP
global $site_title, $title, $sidebarentries;
$site_title = "The freeglut Project";
$title = $site_title . " :: The Next Generation of GLUT";

# 1's designate spaces in the sidebar
# Format for an entry:
# array(name=>"Name of Page", page=>"Filename/URL"), 
#
$sidebarentries = array(0, # We skip this entry anyway...
	array(name=>"About freeglut", page=>"/index.php"), 
	array(name=>"Latest News", page=>"/news.php"), 
	array(name=>"Info for Users", page=>"/uinfo.php"),
	array(name=>"Info for Developers", page=>"/dinfo.php"),
	1,
	array(name=>"Download", page=>"/index.php#download"),
	1,
	array(name=>"How You Can Help", page=>"/help.php"),
	array(name=>"Look at Progress", page=>"/progress.php"),
	1,
	array(name=>"Report a Bug", page=>"http://sourceforge.net/tracker/?func=add&group_id=1032&atid=101032"),
	array(name=>"Request a Feature", page=>"http://sourceforge.net/tracker/?func=add&group_id=1032&atid=351032"),
	1,
	array(name=>"Project Interface", page=>"http://sourceforge.net/projects/freeglut/")
	
);

function generateHeader($self) {
	global $title;
	?>
<HTML>
<HEAD>
	<title><? echo $title ?></title>
	<link rel="stylesheet" type="text/css" href="/freeglut-style.css">
</HEAD>
<BODY>
	<?
	generateSideBar($self);
}

function generateFooter() {
	?>
</BODY>
</HTML>
	<?
}

function printMenuItem($item, $current_page) {
	if ($item == 1)
		echo "<br>";
	else if ($current_page == $item[page])
		echo "<div class=\"navbar-item\"><i>$item[name]</i></div>";	
	else
		echo "<a href=\"$item[page]\"><div class=\"navbar-item\">$item[name]</div></a>";	
}

function generateSideBar($current_page) {
	global $sidebarentries;
	echo "<div class=\"navbar-left\">";
	
	echo "<div class=\"navbar-header\"><a href=\"/\"><img src=\"/images/freeglut_logo.png\" border=\"0\"></a></div>
	<div class=\"navbar-smalltext\">The Free OpenGL Utility Toolkit</div><br>";
	
	reset($sidebarentries);
	while (next($sidebarentries)) {
		printMenuItem(current($sidebarentries), $current_page);
	}
	
	echo "<br>
	<div class=\"navbar-header\"><a href=\"http://sourceforge.net/\"><img src=\"http://sourceforge.net/sflogo.php?group_id=1032&type=4\" border=\"0\"></a></div>";
	
	echo "</div>";
}

function setPageTitle($title_inc) {
	global $site_title, $title;
	$title = $site_title . " :: " . $title_inc;
}

?>
