<?PHP 
# Freeglut Web Page
# This block ensures that template.php is always hit, no matter what subdirectory 
$slashes=substr_count ( $_SERVER['PHP_SELF'], "/");
for (;$slashes>1; $slashes--) { $require_target .= "../"; }
$require_target .= "template.php";
require($require_target);

# Now set the title of the page:
setPageTitle("Installation Instructions");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<div class="textheader">How to install freeglut...</div>
<p>It's not *that* hard...</p>

<div class="textheader">Download freeglut</div>
<p>First, you need to download a freeglut release. Newer is generally better, but unless you know what you're doing, you should use a release marked "Stable." So download the newest Stable release that you can.</p>

<div class="textheader">Untar and configure the packages</div>
<p>Copy the tarball you downloaded into a temporary directory. Untar it as follows:</p>
<blockquote>tar -jvxf freeglut-2.X.X.tar.bz2</blockquote>

<div class="textheader">Change to the resulting directory:</div>
<blockquote>cd freeglut-2.X.X</blockquote>
 
<div class="textheader">Now, configure the packages:</div>
<blockquote>./configure</blockquote>

<div class="textheader">Build and install freeglut</div>
<p>Once that completes, it's time to build and install freeglut (Note that you will have to be the superuser to make install).</p>

<p>This is as simple as:</p>

<blockquote>make all
make install</blockquote>


<div class="textheader">(Optional) Making symlinks to replace GLUT</div>
<p>Make a symlink from libfreeglut.so.0.2.0 to libglut.so.3, libglut.so.3.7 and libglut.so.3.7.0</p>

<blockquote>ln -s /usr/lib/libfreeglut.so.0.2.0 /usr/lib/libglut.so.3<br>
ln -s /usr/lib/libfreeglut.so.0.2.0 /usr/lib/libglut.so.3.7<br>
ln -s /usr/lib/libfreeglut.so.0.2.0 /usr/lib/libglut.so.3.7.0</blockquote>

<?PHP
generateFooter();
?>