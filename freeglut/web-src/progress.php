<?php
require("template.php");

# Now set the title of the page:
setPageTitle("A Look At Progress");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<div class="textheader">freeglut 3.0 an onwards</div>
<p>
Major work, its status and planned milestone. <a
href="help.php">Help</a> on any of these plans is very welcome! Fork the
<a href="">github repository</a>, <a
href="http://lists.sourceforge.net/lists/listinfo/freeglut-developer">join</a>
and contact us on the <a
href="mailto:freeglut-developer@lists.sourceforge.net">freeglut-developer</a>
mailing list to discuss your plans, and get cracking!</p>
<table>
<tr>
<th>Feature</th><th>Status</th><th>Milestone</th></tr>
<tr><td>Android port</td><td>Basic but complete functionality by <a href="http://www.beuc.net/">Sylvain
Beucler</a>, but there are <a href="docs/android.php#roadmap">todo
points</a></td><td>Basic functionality as is now done: 3.0. Future
enhancements: 3.0 or later.</td></tr>

<tr><td>BlackBerry 10 port</td><td>Basic but complete functionality
by <a href="https://github.com/rcmaniac25">Vinnie
Simonetti</a>.</td><td>Basic functionality as is now done: 3.0. Future
enhancements: 3.0 or later.</td></tr>

<tr><td>Windows 8 touch support</td><td>Windows 8 replaced how touch
input works, so we need to implement support for that.</td><td>3.2</td></tr>

<tr><td>10bit display formats</td><td>GLUT supports that but freeglut
currently does not. Unfinished effort to implement <a
href="https://github.com/dcnieho/FreeGLUT/tree/feature_30bit_framebuffer">here</a>.</td><td>3.0
or later.</td></tr>

<tr><td>handling non-western script input to windows</td><td>For
Windows, see associated <a
href="https://github.com/dcnieho/FreeGLUT/tree/feature_IMECHAR_callback">github
branch</a>. What can be done on the Linux side?</td><td>Figure this out
for 3.2</td></tr>

<tr><td>option to not deinitialize when mainloop terminates</td><td>As
is often requested, in some usage cases, it would be advantageous if
freeglut did not automatically deinitialize when its last window is
closed. This has been implemented, but is awaiting testing on platforms
other than windows. Discussed <a
href="http://sourceforge.net/p/freeglut/mailman/message/32926301/">here</a>.</td><td>As
it has potential complications on mobile platforms, it was decided to
leave this feature until after 3.0.</td></tr>

<tr><td>High-DPI awareness.</td><td>As discussed <a
href="http://sourceforge.net/p/freeglut/mailman/message/30859054/">here</a>,
it is unclear what should be done. See also associated <a
href="https://github.com/dcnieho/FreeGLUT/tree/feature_DPI_awareness">github
branch</a>, though in the end maybe no code has to be added to freeglut
for this at all, its the host program's responsibility to decide on DPI
awareness for his work.</td><td>Figure this out for 3.2</td></tr>

<tr><td>GL2+ and GLES 1/2 clean API for fonts and menus</td><td>For
stroke fonts, we can use a similar approach as for the shapes, should
thus be straightforward. For the bitmap fonts, this is more complex.
John T suggested "using a glyph atlas texture with GL_STREAM_DRAW VBOs",
which would get us somewhere. Code could be ripped from his <a
href="http://nuclear.mutantstargoat.com/sw/libdrawtext/">libdrawtext
library</a></td><td>The sooner the better, but will have to wait for
asap after 3.0.</td></tr>

<tr><td>At runtime loading of OpenGL or OpenGL ES libraries,
like SDL.</td><td>Currently CMake variables define at compile time which
of the two the library will be compiled against. No work has been
done to move this to an at-runtime API.</td><td>After 3.0</td></tr>

<tr><td>Position callback</td><td>Done: we now have a position callback
to notify the client when the position of the window
changed.</td><td>3.0</td></tr>

<tr><td>Carbon/Cocoa port</td><td>Not started. As Carbon is legacy, one
might want to port to Cocoa first or even forget about Carbon all
together.</td><td>Undecided</td></tr>

<tr><td>Unicode support</td><td>Not started, we'd need to discuss this
carefully on the mailing list first to minimize impact on the
API.</td><td>Undecided</td></tr>

<tr><td>Complete glutInitDisplayString support</td><td>freeglut now only
partially supports glutInitDisplayString, any spec (comparator and
value) after each token are ignored. This is a significant way in which
freeglut is not compatible with GLUT. Could be a nice project for
someone who want to become familiar with the intricacies of requesting
specific display formats on at least one of the various window servers
(Windows, X11 and Android/BlackBerry 10/EGL at the
moment).</td><td>Undecided</td></tr>
</table>

<?php generateFooter(); ?>
