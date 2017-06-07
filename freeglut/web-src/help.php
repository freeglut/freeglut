<?php
require("template.php");

# Now set the title of the page:
setPageTitle("How You Can Help");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>
<div class="img-right">
<img src="images/OGL.png" alt="OpenGL" /><br/>
This is OpenGL at its finest.
</div>

<div class="textheader">This is better than GLUT.</div>
<p>freeglut started out as a GLUT clone, but now it's something much
better than that. It's a GLUT replacement. Here are the features you
have wanted -- and if you have ideas for more, share them (see below),
or fork our <a href="https://github.com/dcnieho/FreeGLUT">github
repository</a> and implement them! freeglut is under continuous
development. Currently, work on 3.0 is in full swing.  See <a
href=progress.php>here</a> for the list of work that is still to be
done.</p> 

<div class="textheader">Everyone can help</div>
<p>
Free software projects depend on the community around them for their growth. 
It's by contributions that truly wonderful pieces of open and free software come to be.
Contributions can be nearly anything.  The point is involvement!</p>

<p>Examples:
<ul>
    <li>Attack one of the <a href="progress.php">points on our todo
    list</a></li>
	<li>Use freeglut in your projects!</li>
	<li>Tell us what you like.</li>
	<li>Tell us what you'd change.</li>
	<li><a href="http://sourceforge.net/p/freeglut/bugs/">Report bugs.</a></li>
	<li>Write documentation.</li>
	<li>Spread the word about the project.</li>
	<li><a href="http://lists.sourceforge.net/lists/listinfo/freeglut-developer">Join</a> our development mailing list.</li>
    <li>If you changed or added a feature, send us <a
    href="http://sourceforge.net/p/freeglut/feature-requests/">a
    patch on sourceforge.net</a>, or <a
    href="https://github.com/dcnieho/FreeGLUT">a pull request on
    github</a> -- others might benefit from your work!</li>
</ul></p>

<p>
But simply, become involved!
The project is not just software, it's the people that contribute, too.
</p>

<div class="textheader">freeglut 3.0</div>
<p>
<a href=progress.php>Click here</a> for an overview of the major work,
its status and planned milestones for freeglut 3.0 and beyond. Help on
any of these plans, as well as suggestions and/or patches for anything
else are very welcome!
</p>

<div class="textheader">Go to SourceForge and...</div>

<ul>
	<li><a href="http://sourceforge.net/p/freeglut/feature-requests/">Review current feature requests</a></li>
	<li><a href="http://sourceforge.net/p/freeglut/bugs/">Review current bug reports</a></li>
</ul>

<div class="textheader"><a name="svn"></a>Anonymous SVN Access</div>
<p>To check out the most up to date development copy of freeglut, issue the following commands at your command line. See also <a href="http://sourceforge.net/p/freeglut/code/HEAD/tree/">SourceForge's Anonymous SVN Instructions</a>.</p>

<pre>
svn co http://svn.code.sf.net/p/freeglut/code/trunk/freeglut/freeglut freeglut
</pre>

<?php generateFooter(); ?>
