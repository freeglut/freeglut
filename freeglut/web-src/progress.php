<?PHP 
# Freeglut Web Page
# This block ensures that template.php is always hit, no matter what subdirectory 
$slashes=substr_count ( $_SERVER['PHP_SELF'], "/");
for (;$slashes>1; $slashes--) { $require_target .= "../"; }
$require_target .= "template.php";
require($require_target);

# Now set the title of the page:
setPageTitle("A Look At Progress");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<div class="textheader">Future Goals</div>
<p>This still needs filling in, but be assured it will contain something like, "become an even better library."</p>


<?PHP
generateFooter();
?>