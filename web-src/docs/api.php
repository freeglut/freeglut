<?PHP 
# Freeglut Web Page
# This block ensures that template.php is always hit, no matter what subdirectory 
$slashes=substr_count ( $_SERVER['PHP_SELF'], "/");
for (;$slashes>1; $slashes--) { $require_target .= "../"; }
$require_target .= "template.php";
require($require_target);

# Now set the title of the page:
setPageTitle("API Documentation");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<div class="textheader">Notes on usage</div>
<p>freeglut is platform independent, but specific examples here will assume the use of the GNU C compiler and the GNU C library.</p>


<?PHP
generateFooter();
?>