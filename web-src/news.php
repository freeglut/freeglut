<?PHP 
# Freeglut Web Page
# This block ensures that template.php is always hit, no matter what subdirectory 
$slashes=substr_count ( $_SERVER['PHP_SELF'], "/");
for (;$slashes>1; $slashes--) { $require_target .= "../"; }
$require_target .= "template.php";
require($require_target);

# Now set the title of the page:
setPageTitle("News");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<div class="news-item">
<div class="news-header">freeglut 2.2.0 Released [12 Decmber 2003]</div>
<p>The freeglut Project proudly announces freeglut 2.2.0. This version contains:</p>
<ul>
<li>Mousewheel Support for Win32 & X11</li>
<li>More unified Win32/*nix behavior and code</li>
<li>Code style cleanups</li>
<li>Visibility support in Win32</li>
<li>Many other Good Bug Fixes&tm;</li>
</ul>
<p>[Read <a href="http://sourceforge.net/project/shownotes.php?release_id=203468">the full ChangeLog</a>.]</p>

<p>Click the Downloads link on the left and enjoy!</p>

</div>


<div class="news-item">
<div class="news-header">freeglut 2.0.1 Released [23 October 2003]</div>
<p>2.0.1 fixes a number of bugs with menus, especially in the UNIX_X11.</p>
</div>


<div class="news-item">
<div class="news-header">freeglut 2.0.0 is GOLD! [29 September 2003]</div>

<div class="img-right"><img src="images/tuxcake.png"><br>
A cake one of our developers<BR>
(John Fay) received in honor<BR>
of his freeglut work.</div>

<p>freeglut 2.0.0 marks the first true departure from Mark Kilgard's original GLUT library, adding much-requested features while still retaining not only high backward compatibility with older implementations of GLUT, but all the polish of the original -- and more!</p>

<div class="news-subheader">What have we done? A lot.</div>
<p>This isn't the GLUT you remember.</p>

<p>This is an incomplete list -- there are simply too many improvements to write!</p>
<p>General Improvements:</p>
<ul><li>You can now control program behavior when a GLUT window is closed by the user, rather than simply exiting. Options include:</li>
	<ul><li>Immediate exit() (old GLUT behavior)</li>
	<li>glutMainLoop() returns to you</li>
	<li>continue execution</li></ul>
<li>Option to share a rendering context for each new window</li>
<li>Interruptable glutMainLoop (glutMainLoopEvent, glutLeaveMainLoop)</li>
<li>Window-specific callback functions (Close, WindowManager Close, Menu destruction)</li>
<li>User-specified data in Windows and Menus</li>
<li>Additional font statistics (stroke and bitmap heights...)</li>
<li>New font functions to allow the user to print strings instead of one character at a time</li>
<li>New primitives (RhombicDodecahedron, SierpinskiSponge)</li>
<li>Good-looking menus</li></ul>

<p>XFree86 GameMode improvements:</p>
<ul><li>force re-establishment of the original video mode by the X server even if the applicatiton exits immediately after leaving game mode</li>
<li>make sure that the X server has finished game mode preparations before continuing; fixes window sliding(missed mouse grab) and application exits.</li>
<li>fix support for game mode resolution equal to maximum screen resolution</li>
<li>restore original view port and Pointer position when leaving game mode</li></ul>

<p>Win32 GameMode improvements:</p>
<ul><li>if desired display frequency for entering game mode is not found, a display mode without matching display frequency is tried.</li></ul>

<div class="news-subheader">Get involved!</div>

<p>If you like freeglut, think about helping out with the project. There's always more to be done to enhance the library!</p>

<p>Documentation, example programs, suggestions for improvement -- they're all needed.</p>

<p>Look at the "How You Can Help" section on the left for ideas on how you can do your part!</p>

</div>

<?PHP
generateFooter();
?>
