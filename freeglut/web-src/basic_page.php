<?PHP 
# Freeglut Web Page
# This block ensures that template.php is always hit, no matter what subdirectory 
$slashes=substr_count ( $_SERVER['PHP_SELF'], "/");
for (;$slashes>1; $slashes--) { $require_target .= "../"; }
$require_target .= "template.php";
require($require_target);

# Now set the title of the page:
setPageTitle("Basic Page");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<div class="textheader">What?</div>
<p>freeglut is a completely OpenSourced alternative to the OpenGL Utility Toolkit (GLUT) library. GLUT was originally written by Mark Kilgard to support the sample programs in the second edition OpenGL 'RedBook'. Since then, GLUT has been used in a wide variety of practical applications because it is simple, widely available and highly portable.</p>


<?PHP
generateFooter();
?>
