<?PHP
global($site_title, $title);
$site_title = "The freeglut Project";
$title = $site_title . " :: The Next Generation of GLUT";

function generateHeader() {
	?>
<HTML>
<HEAD>
	<title><? echo $title ?></title>
	<link rel="stylesheet" type="text/css" href="freeglut-style.css">
</HEAD>
<BODY>
	<?
}

function generateFooter() {
	?>
</BODY>
</HTML>
	<?
}

function  setPageTitle($title_inc) {
	global($site_title, $title);
	$title = $site_title . " :: " . $title_inc;
}

?>