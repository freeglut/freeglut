<?PHP 
# Freeglut Web Page
# This block ensures that template.php is always hit, no matter what subdirectory 
$slashes=substr_count ( $_SERVER['PHP_SELF'], "/");
for (;$slashes>1; $slashes--) { $require_target .= "../"; }
$require_target .= "template.php";
require($require_target);

# Now set the title of the page:
setPageTitle("How You Can Help");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<div class="textheader">Everyone can help</div>
<p>Free software projects depend on the community around them for their growth. It's by contributions that truly wonderful pieces of open and free software come to be.</p> 

<p>Contributions can be nearly anything. The point is involvement!</p>

<p>Examples:</p>
<ul>
	<li>Use freeglut in your projects!</li>
	<li>Tell us what you like.</li>
	<li>Tell us what you'd change.</li>
	<li>Report bugs.</li>
	<li>Write documentation.</li>
	<li>Spread the word about the project.</li>
	<li>Join our development mailing list.</li>
	<li>If you changed a feature, send us a patch -- others might benefit from your work!</li>
</ul>
<p>But simply, become involved!
The project is not just software, it's the people that contribute, too.</p>

<?PHP
generateFooter();
?>