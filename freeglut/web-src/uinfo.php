<?PHP 
# Freeglut Web Page
# This block ensures that template.php is always hit, no matter what subdirectory 
$slashes=substr_count ( $_SERVER['PHP_SELF'], "/");
for (;$slashes>1; $slashes--) { $require_target .= "../"; }
$require_target .= "template.php";
require($require_target);

# Now set the title of the page:
setPageTitle("Information for Users");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<div class="img-right"><img src="/images/OGL.png"><BR>
This is OpenGL at its finest.</div>

<div class="textheader">This is better than GLUT.</div>
<p>freeglut started as a GLUT clone, but now it's something much better than that. It's a GLUT replacement. Here are the features you have wanted -- and if you have ideas for more, share them!</p> 
<p>Below, find documentation for freeglut...</p>

<div class="textheader">Check out...</div>
<p><a href="/help.php">How You Can Help</a></p>

<div class="textheader">Documentation</div>
<h2><a href="/docs/install.php">The Installation Instructions.</a></h2>
<p class="indent">How to install freeglut on various platforms.</p>

<h2><a href="/docs/api.php">The Application Programming Interface Documentation</a></h2>
<p class="indent">How to use freeglut in your applications.</p>


<?PHP
generateFooter();
?>