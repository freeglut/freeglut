<?PHP 
# Freeglut Web Page
# This block ensures that template.php is always hit, no matter what subdirectory 
$slashes=substr_count ( $_SERVER['PHP_SELF'], "/");
for (;$slashes>1; $slashes--) { $require_target .= "../"; }
$require_target .= "template.php";
require($require_target);

# Now set the title of the page:
setPageTitle("About");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<div class="img-right"><img src="/images/chessdemo.png"><BR>
GLUT's "Chess" demo<BR>
running with freeglut.</div>


<div class="textheader">What?</div>
<p>freeglut is a completely OpenSourced alternative to the OpenGL Utility Toolkit (GLUT) library. GLUT was originally written by Mark Kilgard to support the sample programs in the second edition OpenGL 'RedBook'. Since then, GLUT has been used in a wide variety of practical applications because it is simple, widely available and highly portable.</p>
<p>GLUT (and hence freeglut) allows the user to create and manage windows containing OpenGL contexts on a wide range of platforms and also read the mouse, keyboard and joystick functions.</p>
<p>freeglut is released under the X-Consortium license.</p>

<div class="textheader">Why?</div>
<p>The original GLUT library seems to have been abandoned with the most recent version (3.7) dating back to August 1998. Its license does not allow anyone to distribute modified library code. This would be OK, if not for the fact that GLUT is getting old and really needs improvement. Also, GLUT's license is incompatible with some software distributions (e.g., Xfree86).</p>

<div class="textheader">Who?</div>
<p>freeglut was originally written by Pawel W. Olszta with contributions from Andreas Umbach and Steve Baker. Steve is now the official owner/maintainer of freeglut.</p>

<div class="textheader">When?</div>
<p>Pawel started freeglut development on December 1st, 1999. The project is now virtually a 100% replacement for the original GLUT with only a few departures (such as the abandonment of SGI-specific features such as the Dials&Buttons box and Dynamic Video Resolution) and a shrinking set of bugs.</p>

<a name="download"></a>
<div class="textheader">Downloads...</div>
<p>Below are file links for the freeglut project. README files are included. You can also check the install documentation. Have fun!</p>
<div class="indent">
<div class="textheader">Stable Releases</div>
<p>
	<a href="http://prdownloads.sourceforge.net/freeglut/freeglut-2.2.0.tar.gz?download">Freeglut 2.2.0</a> [<i>Released: 12 December 2003</i>]<br>
	<a href="http://prdownloads.sourceforge.net/freeglut/freeglut-2.0.1.tar.gz?download">Freeglut 2.0.1</a> [<i>Released: 23 October 2003</i>]
</p>

<div class="textheader">Prepackaged Releases</div>
<p>The freeglut project does not support packaged versions of freeglut excepting, of course, the tarballs distributed here. However, various members of the community have put time and effort into providing source or binary rollups, and we thank them for their efforts. Here's a list which is likely incomplete:</p>
<p>
	<a href="http://jumpgate.homelinux.net/random/freeglut-fedora/">Andy Piper's RedHat Fedora RPMs</a>
</p>
<p>If you have problems with these packages, please contact their maintainers - we of the freeglut team probably can't help.</p>

<div class="textheader">Development Releases</div>
<p>
	<a href="http://freeglut.sourceforge.net/dist/current.tgz">Nightly CVS Tarball</a><br>
	<a href="/dinfo.php#cvs">Anonymous CVS Instructions</a>
</p>
</div>

<div class="textheader">Questions?</div>
<p>Don't be afraid to ask for help. We don't bite. Much.</p>
<p>Send freeglut related questions to the appropriate freeglut mailing list:</p>

<ul>
	<li><a href="freeglut-developer@lists.sourceforge.net">freeglut-developer</a> [<a href="http://lists.sourceforge.net/lists/listinfo/freeglut-developer">Subscribe</a>],</li>
	<li><a href="freeglut-announce@lists.sourceforge.net">freeglut-announce</a> [<a href="http://lists.sourceforge.net/lists/listinfo/freeglut-announce">Subscribe</a>], and</li>
	<li><a href="freeglut-bugs@lists.sourceforge.net">freeglut-bugs</a> [<a href="http://lists.sourceforge.net/lists/listinfo/freeglut-bugs">Subscribe</a>]</li>
</ul>



<?PHP
generateFooter();
?>
