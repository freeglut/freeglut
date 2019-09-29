<?php
require("template.php");

# Now set the title of the page:
setPageTitle("About");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>
<div class="img-right">
	<img src="images/chessdemo.png" alt="chess demo"/><br/>
	GLUT's "Chess" demo<br/>
	running with freeglut.
</div>

<p></p>

<div class="textheader">What?</div>

<p>freeglut is a free-software/open-source alternative to the OpenGL Utility
Toolkit (GLUT) library. GLUT was originally written by Mark Kilgard to support
the sample programs in the second edition OpenGL 'RedBook'. Since then, GLUT has
been used in a wide variety of practical applications because it is simple,
widely available and highly portable.</p>

<p>GLUT (and hence freeglut) takes care of all the system-specific chores
required for creating windows, initializing OpenGL contexts, and handling input
events, to allow for trully portable OpenGL programs.</p>

<p>freeglut is released under the X-Consortium license.</p>

<div class="textheader">Why?</div>

<p>The original GLUT library seems to have been abandoned with the most recent
version (3.7) dating back to August 1998. Its license does not allow anyone to
distribute modified library code. This is really unfortunate, since GLUT is
getting old and really needs improvement. Also, GLUT's license is incompatible
with some software distributions (e.g., XFree86).</p>

<div class="textheader">Who?</div>

<p>freeglut was originally written by Pawel W. Olszta with contributions from
Andreas Umbach and Steve Baker.</p>

<p>John F. Fay, John Tsiombikas, and Diederick C. Niehorster are the current
maintainers of the freeglut project.</p>

<div class="textheader">When?</div>

<p>Pawel started freeglut development on December 1st, 1999. The project is now
virtually a 100% replacement for the original GLUT with only a few departures
(such as the abandonment of SGI-specific features such as the Dials&amp;Buttons
box and Dynamic Video Resolution) and a shrinking set of bugs.</p>

<p>freeglut adds some additional features over the basic GLUT functionality,
such as a larger set of predefined objects to use, the ability to run single
iterations of the event loop, or exit from it gracefully, mousewheel input
callbacks, optional OpenGL core/compatibility profile context creation,
multitouch/multi-pointer input, and support for a larger and growing set of
platforms, being just some of them.</p>

<div class="textheader"><a name="download"></a>Help out!</div>

<p>We are looking for developers to help out with further work on the Android
and BlackBerry 10 ports. Furthermore, ports to Cocoa on OSX, and maybe even
Wayland are planned, along with some enhancements to the API and implementation.</p>

<p>See <a href="progress.php">here</a> for an overview of the major points
on our todo list. You can easily help out by forking the unofficial clone
of our <a
href="https://sourceforge.net/p/freeglut/code/HEAD/tree/">sourceforge.net
SVN repository</a> on <a
href="https://github.com/dcnieho/FreeGLUT">github</a>. For more
information about helping out, see the <a href="help.php">Help Out
page</a> and <a
href="http://lists.sourceforge.net/lists/listinfo/freeglut-developer">join</a>
the <a
href="mailto:freeglut-developer@lists.sourceforge.net">freeglut-developer</a>
mailing list.</p>

<div class="textheader"><a name="download"></a>Downloads...</div>
<p>Below are file links for the freeglut project. README files are included. Have fun!</p>

<div class="indent">
	<div class="textheader">Testing Releases</div>
    <p>Feel free to test by downloading a <a
    href="https://sourceforge.net/p/freeglut/code/HEAD/tarball?path=/trunk/freeglut/freeglut">tarball
    of current trunk</a>, or <a href="help.php#svn">grabbing a copy from
    svn</a>, and give us feedback on how it worked for you. All this
    will eventually become a freeglut 3.4 release.</p>
	<p>There are no presently active testing releases.</p>
</div>


<div class="indent">
	<div class="textheader">Stable Releases</div>
	<p>
	<a href="http://prdownloads.sourceforge.net/freeglut/freeglut-3.2.1.tar.gz?download">freeglut 3.2.1</a> [<i>Released: 29 September 2019</i>]<br/>
	<a href="http://prdownloads.sourceforge.net/freeglut/freeglut-3.2.0.tar.gz?download">freeglut 3.2.0</a> [<i>Released: 16 September 2019</i>]<br/>
	<a href="http://prdownloads.sourceforge.net/freeglut/freeglut-3.0.0.tar.gz?download">freeglut 3.0.0</a> [<i>Released: 7 March 2015</i>]<br/>
	<a href="http://prdownloads.sourceforge.net/freeglut/freeglut-2.8.1.tar.gz?download">freeglut 2.8.1</a> [<i>Released: 5 April 2013</i>]<br/>
	<a href="http://prdownloads.sourceforge.net/freeglut/freeglut-2.8.0.tar.gz?download">freeglut 2.8.0</a> [<i>Released: 2 January 2012</i>]<br/>
	<a href="http://prdownloads.sourceforge.net/freeglut/freeglut-2.6.0.tar.gz?download">freeglut 2.6.0</a> [<i>Released: 27 November 2009</i>]<br/>
	<a href="http://prdownloads.sourceforge.net/freeglut/freeglut-2.4.0.tar.gz?download">freeglut 2.4.0</a> [<i>Released: 9 June 2005</i>]<br/>
	<a href="http://prdownloads.sourceforge.net/freeglut/freeglut-2.2.0.tar.gz?download">freeglut 2.2.0</a> [<i>Released: 12 December 2003</i>]<br/>
	<a href="http://prdownloads.sourceforge.net/freeglut/freeglut-2.0.1.tar.gz?download">freeglut 2.0.1</a> [<i>Released: 23 October 2003</i>]
	</p>

	<div class="textheader">Prepackaged Releases</div>

	<p>The freeglut project does not support packaged versions of freeglut
excepting, of course, the tarballs distributed here. However, various members of
the community have put time and effort into providing source or binary rollups,
and we thank them for their efforts. Here's a list which is likely
incomplete:</p>

<!--
	<p>
		Andy Piper's <a href="http://jumpgate.homelinux.net/random/freeglut-fedora/">RedHat Fedora RPMs</a><br/>
		Gentoo <a href="http://bugs.gentoo.org/show_bug.cgi?id=36783">freeglut-2.2.0.ebuild</a><br/>
		Nigel Stewart's <a href="http://www.nigels.com/glt/devpak/">DevPak</a> for <a href="http://www.bloodshed.net/dev/devcpp.html">Dev C++</a>
	</p>
-->
	<p>
		<a href="http://www.transmissionzero.co.uk/software/freeglut-devel/">Martin Payne's Windows binaries (MSVC and MinGW)</a><br/>
		<a href="http://tisch.sf.net/freeglut-2.6.0-mpx-latest.patch">Florian Echtler's MPX Patch</a>
	</p>

	<p>If you have problems with these packages, please contact their maintainers - we of the freeglut team probably can't help.</p>
	
	<div class="textheader">Development Releases</div>
	<p>
		<a href="https://sourceforge.net/p/freeglut/code/HEAD/tarball?path=/trunk/freeglut/freeglut">SVN trunk tarball</a><br/>
		<a href="help.php#svn">Anonymous SVN Instructions</a>
	</p>
</div>

<div class="textheader">Questions?</div>
<p>Don't be afraid to ask for help. We don't bite. Much.</p>
<p>Send freeglut related questions to the appropriate freeglut mailing list:</p>

<ul>
	<li><a href="mailto:freeglut-developer@lists.sourceforge.net">freeglut-developer</a> [<a href="http://lists.sourceforge.net/lists/listinfo/freeglut-developer">Subscribe</a>],</li>
	<li><a href="mailto:freeglut-announce@lists.sourceforge.net">freeglut-announce</a> [<a href="http://lists.sourceforge.net/lists/listinfo/freeglut-announce">Subscribe</a>], and</li>
	<li><a href="mailto:freeglut-bugs@lists.sourceforge.net">freeglut-bugs</a> [<a href="http://lists.sourceforge.net/lists/listinfo/freeglut-bugs">Subscribe</a>]</li>
</ul>

<p>Please note that <a
href="http://sourceforge.net/p/freeglut/mailman/?source=navbar">you must
subscribe before you can post</a> to our mailing lists. Sorry for the
inconvenience.</p>


<?php generateFooter(); ?>
