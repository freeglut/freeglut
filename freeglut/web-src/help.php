<?php
require("template.php");

# Now set the title of the page:
setPageTitle("How You Can Help");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<p></p>

<div class="img-right">
<img src="images/OGL.png" alt="OpenGL" /><br/>
This is OpenGL at its finest.
</div>

<div class="textheader">This is better than GLUT.</div>
<p>freeglut started as a GLUT clone, but now it's something much better than that. It's a GLUT replacement. Here are the features you have wanted -- and if you have ideas for more, share them!</p> 

<div class="textheader">Everyone can help</div>
<p>
Free software projects depend on the community around them for their growth. 
It's by contributions that truly wonderful pieces of open and free software come to be.
</p> 

<p>Contributions can be nearly anything.  The point is involvement!</p>

<p>Examples:</p>
<ul>
	<li>Use freeglut in your projects!</li>
	<li>Tell us what you like.</li>
	<li>Tell us what you'd change.</li>
	<li><a href="http://sourceforge.net/tracker/?func=browse&amp;group_id=1032&amp;atid=101032">Report bugs.</a></li>
	<li>Write documentation.</li>
	<li>Spread the word about the project.</li>
	<li><a href="http://lists.sourceforge.net/lists/listinfo/freeglut-developer">Join</a> our development mailing list.</li>
	<li>If you changed a feature, send us a patch -- others might benefit from your work!</li>
</ul>

<p>
But simply, become involved!
The project is not just software, it's the people that contribute, too.
</p>

<div class="textheader">Go to SourceForge and...</div>

<ul>
	<li><a href="http://sourceforge.net/tracker/?func=browse&amp;group_id=1032&amp;atid=351032">Review current feature requests</a></li>
	<li><a href="http://sourceforge.net/tracker/?func=browse&amp;group_id=1032&amp;atid=101032">Review current bug reports</a></li>
</ul>

<div class="textheader"><a name="svn"></a>Anonymous SVN Access</div>
<p>To check out the most up to date development copy of freeglut, issue the following commands at your command line. See also <a href="http://sourceforge.net/svn/?group_id=1032">SourceForge's Anonymous SVN Instructions</a>.</p>

<pre>
svn co https://freeglut.svn.sourceforge.net/svnroot/freeglut/trunk/freeglut/freeglut freeglut
</pre>

<?php generateFooter(); ?>
