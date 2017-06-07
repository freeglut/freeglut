<?php 
# freeglut Web Page
# This block ensures that template.php is always hit, no matter what subdirectory 
$slashes=substr_count ( $_SERVER['PHP_SELF'], "/");
$require_target="";
for (;$slashes>1; $slashes--) { $require_target .= "../"; }
$require_target .= "template.php";
require($require_target);

# Now set the title of the page:
setPageTitle("Information for Users");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<?php generateFooter(); ?>
