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

<p>Presently, there are few pre-packaged installs, so the recommended method for most users is to follow these instructions.  Even if you have a package system that includes freeglut, you may wish to manually install for a variety of reasons.  A likely reason is simply that the latest version of freeglut may not have been packaged.</p>

<p>So...</p>

<div class="textheader">Download freeglut</div>
<p>First, you need to download a freeglut release. Newer is generally better, but unless you know what you're doing, you should use a release marked "Stable." So download the newest Stable release that you can.  (On the other hand, if you discover a bug and want to report it, it is helpful to try to run the "current" freeglut, as we have been known to fix some bugs before they are reported.)</p>

<div class="textheader">Untar and configure the packages</div>
<p>Copy the tarball you downloaded into a temporary directory. Untar it as follows:</p>
<tt><blockquote>bzcat freeglut-2.2.0.tar.bz2 | tar -vxf -</blockquote></tt>

<div class="textheader">Change to the resulting directory:</div>
<tt><blockquote>cd freeglut-2.X.X</blockquote></tt>
 
<div class="textheader">Now, configure the packages:</div>
<tt><blockquote>./configure</blockquote></tt>

<p>NetBSD users may need a slightly different command:</p>

<blockquote><tt>LDFLAGS=-Wl,-R/usr/X11R6/lib ./configure</tt></blockquote>

<p>...to properly handle things.</p>

<div class="textheader">Build and install freeglut</div>
<p>Once that completes, it's time to build and install freeglut (Note that you will have to be the superuser to make install).</p>

<p>This is as simple as:</p>

<tt><blockquote>make all<br>
make install</blockquote></tt>


<div class="textheader">(Optional) Making symlinks to replace GLUT</div>
<p>Make a symlink from libfreeglut.so.0.2.0 to libglut.so.3, libglut.so.3.7 and libglut.so.3.7.0</p>

<tt><blockquote>ln -s /usr/lib/libfreeglut.so.0.2.0 /usr/lib/libglut.so.3<br>
ln -s /usr/lib/libfreeglut.so.0.2.0 /usr/lib/libglut.so.3.7<br>
ln -s /usr/lib/libfreeglut.so.0.2.0 /usr/lib/libglut.so.3.7.0</blockquote></tt>

<?PHP
generateFooter();
?>
