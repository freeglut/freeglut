<?PHP 
# Freeglut Web Page
# This block ensures that template.php is always hit, no matter what subdirectory 
$slashes=substr_count ( $_SERVER['PHP_SELF'], "/");
for (;$slashes>1; $slashes--) { $require_target .= "../"; }
$require_target .= "template.php";
require($require_target);

# Now set the title of the page:
setPageTitle("Info for Developers");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<div class="textheader">We can always use a hand.</div>
<p>Below you'll find how to get the latest CVS copy, API documentation, and probably some ideas on why you should use freeglut.</p>

<div class="textheader">Check out...</div>
<p><a href="/help.php">How You Can Help</a></p>

<div class="textheader">Go to SourceForge and...</div>
<p><a href="http://freeglut.sourceforge.net/fg/index.php?id=402">Review current feature requests</a>
<a href="http://freeglut.sourceforge.net/fg/index.php?id=403">Review current bug reports</a></p>

<a name="cvs"></a>
<div class="textheader">Anonymous CVS Access</div>
<p>To check out the most up to date development copy of freeglut, issue the following commands at your command line. When prompted for a password for anonymous, simply press the Enter key.</p>

<blockquote>cvs -d:pserver:anonymous@cvs.sourceforge.net:/cvsroot/freeglut login<br>
cvs -z3 -d:pserver:anonymous@cvs.sourceforge.net:/cvsroot/freeglut co freeglut</blockquote>

<div class="textheader">Documentation</div>
<p>Installation<br>
<a href="/docs/install.php">How to install freeglut on various platforms</a></p>
<p>Programming API<br>
<a href="/docs/api.php">How to use freeglut in your applications</a></p>

<?PHP
generateFooter();
?>