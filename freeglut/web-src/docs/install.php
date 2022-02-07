<?php
require("../template.php");

# Now set the title of the page:
setPageTitle("Installation Instructions");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<div class="textheader">How to install freeglut...</div>
<p>It's not *that* hard...</p>

<p>Presently, there are few pre-packaged installs, so the recommended method for most
users is to follow these instructions.  Even if you have a package system that includes
freeglut, you may wish to manually install for a variety of reasons.  A likely reason is
simply that the latest version of freeglut may not have been packaged.</p>

<p>The following are simple UNIX instructions, see <code>README.cmake</code> in the freeglut
source distribution, for detailed instructions for your system.</p>

<div class="textheader">Download freeglut</div>

<p>First, you need to download a freeglut release. Newer is generally better, but unless
you know what you're doing, you should use a release marked "Stable." So download the
newest Stable release that you can.  (On the other hand, if you discover a bug and want to
report it, it is helpful to try to run the "current" freeglut, as we have been known to
fix some bugs before they are reported.)</p>

<div class="textheader">Untar and configure the packages</div>
<p>Copy the tarball you downloaded into a temporary directory. Untar it as follows:</p>
<pre>tar xzvf freeglut-X.Y.Z.tar.gz</pre>

<div class="textheader">Change to the resulting directory:</div>
<pre>cd freeglut-X.Y.Z</pre>
 
<div class="textheader">Create and change into a build directory:</div>
<pre>
mkdir build
cd build
</pre>

<div class="textheader">Run cmake to generate a Makefile</div>
<pre>cmake ..</pre>

<div class="textheader">Build and install freeglut</div>
<p>This is as simple as:</p>

<pre>
make
sudo make install
</pre>

<p>This will install the libraries, headers, and symlinks to replace GLUT.</p>

<p>For more complete build instructions for all operating systems, please see <code>README.cmake</code>.</p>

<p>For Android, please see the dedicated <a href="android.php">Android page</a>.</p>

<p>For BlackBerry support, please see <code>README.blackberry</code> in the project source code.</p>

<p>For OpenGL ES support (1.x and 2.x), please see the
dedicated <a href="gles.php">GLES page</a>.</p>

<?php generateFooter(); ?>
