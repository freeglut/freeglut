<?PHP 
require("template.php");
setPageTitle("Front Page");
generateHeader();
?>

<div class="textheader">What?</div>
<p>freeglut is a completely OpenSourced alternative to the OpenGL Utility Toolkit (GLUT) library. GLUT was originally written by Mark Kilgard to support the sample programs in the second edition OpenGL 'RedBook'. Since then, GLUT has been used in a wide variety of practical applications because it is simple, universally available and highly portable.</p>
<p>GLUT (and hence freeglut) allows the user to create and manage windows containing OpenGL contexts on a wide range of platforms and also read the mouse, keyboard and joystick functions.</p>
<p>freeglut is released under the X-Consortium license.</p>

<div class="textheader">Why?</div>
<p>The original GLUT library seems to have been abandoned with the most recent version (3.7) dating back to August 1998. It's license does not allow anyone to distribute modified library code. This would be OK, if not for the fact that GLUT is getting old and really needs improvement. Also, GLUT's license is incompatible with some software distributions (eg Xfree86).</p>

<div class="textheader">Who?</div>
<p>freeglut was originally written by Pawel W. Olszta with contributions from Andreas Umbach and Steve Baker. Steve is now the official owner/maintainer of freeglut.</p>

<div class="textheader">When?</div>
<p>Pawel started freeglut development on December 1st, 1999. The project is now a virtually 100% replacement for the original GLUT with only a few departures (such as the abandonment of SGI-specific features such as the Dials&Buttons box and Dynamic Video Resolution).</p>

<div class="textheader">Downloads...</div>
<p>Below are file links for the freeglut project. README files are included, or you can check the install documentation. Have fun!</p>

<div class="textheader">Prepackaged Releases</div>
<p>The freeglut project does not support packaged versions of freeglut excepting, of course, the tarballs distributed here. However, various members of the community have put time and effort into providing source or binary rollups, and we thank them for their efforts. Here's a list which is likely incomplete:</p>
<p>Andy Piper's RedHat Fedora RPMs</p>
<p>If you have problems with these packages, please contact their maintainers - we of the freeglut team probably can't help.</p>

<div class="textheader">Questions?</div>
<p>Don't be afraid to ask for help. We don't bite. Much.</p>
<p>Send freeglut related questions to the appropriate freeglut mailing list:</p>

<ul>
	<li>freeglut-developer [Subscribe],</li>
	<li>freeglut-announce [Subscribe], and</li>
	<li>freeglut-bugs [Subscribe]</li>
</ul>



<?PHP
generateFooter();
?>