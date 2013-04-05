<?php
require("template.php");

# Now set the title of the page:
setPageTitle("A Look At Progress");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<div class="textheader">FreeGLUT 3.0</div>
<p>
Major work, its status and planned milestone. <a
href="help.php">Help</a> on any of these plans is very welcome!</p>
<table>
<tr>
<th>Feature</th><th>Status</th><th>Milestone</th></tr>
<tr><td>Andriod port</td><td>Basic but complete functionality by Sylvain
Beucler, but there are <a href=docs/android.php#roadmap>todo
points</a></td><td>Basic functionality as is now done: 3.0. Future
enhancements: 3.0 or later.</td></tr>

<tr><td>GL2+ and GLES 1/2 clean API for geometry</td><td>Done for all (geometry like
cube, cone, etc, as well as the teapot and other parts of the
teaset)</td><td>3.0</td></tr>

<tr><td>GL2+ and GLES 1/2 clean API for fonts and menus</td><td>Not sure what can be done
here really. Menus will continue to work fine with OpenGL 3+ exclusive
applications as long as the platform provides legacy contexts, as menus
have their own rendering context. For text rendering, its probably best if the
user looks at freetype and the like and avoids the use of FreeGLUT's
text API</td><td>Probably impossible to do anything here</td></tr>

<tr><td>At runtime loading of OpenGL, OpenGL ES1 or OpenGL ES2 libraries,
like SDL.</td><td>Currently CMake variables define at compile time which
of these three the library will be compiled against. No work has been
done on moving this to an at-runtime API.</td><td>Having this in 3.0
would be nice, but not essential.</td></tr>

<tr><td>Code reorganized such that platform and display server specific code
are decoupled</td><td>Done, thanks John Fay! This already lead to a port
to Android and support for GLES 2 on X11 (thanks Sylvain for both!), and
paves the way for implementing native OSX and Wayland
support</td><td>3.0</td></tr>

<tr><td>Position callback</td><td>Done: we now have a position callback
notifying when position of the window changed.</td><td>3.0</td></tr>
<tr><td>

<tr><td>Carbon/Cocoa port</td><td>Not started. As Carbon is legacy, one
might one to port to Cocoa first or even forget about Carbon all
together.</td><td>Undecided</td></tr>

<tr><td>Wayland port</td><td>Not started.</td><td>Undecided</td></tr>
</table>
</p>

<?php generateFooter(); ?>
