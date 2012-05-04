<?php
require("../template.php");

# Now set the title of the page:
setPageTitle("Android");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<ul>
<li><a href="#roadmap">Roadmap</a></li>
<li><a href="#api">New API</a></li>
<li><a href="#compiling">Compiling</a></li>
<li><a href="#using">Using in your projects</a></li>
<li><a href="#links">Links</a></li>
</ul>


<a name="roadmap"></a>
<h1>Roadmap</h1>

Done:
<ul>
<li>Initialize context with EGL</li>
<li>Keyboard support</li>
<li>Mouse support</li>
<li>Virtual keypad (on touchscreen)</li>
<li>Extract assets in cache dir on start-up</li>
<li>Make EGL support reusable by Mesa X11</li>
<li>freeglut_std.h can be used with GLES1 or GLES2 or non-ES headers<br />
(using -DFREEGLUT_GLES1 and -DFREEGLUT_GLES2)</li>
<li>GLES1 and GLES2 support for geometry</li>
<li>Pause/resume application support</li>
</ul>

TODO:
<ul>
<li>Open new windows (if that's possible)</li>
<li>Joystick support (xperia play...)</li>
<li>Display translucent keys on virtual keypad</li>
<li>API to detect touchscreen presence</li>
<li>API to disable assets extraction</li>
<li>Callback to reload OpenGL resources lost during a pause</li>
<li>Callback for pause/resume notifications</li>
</ul>

Possibly implemented later:
<ul>
<li>Support for menus and basic fonts</li>
</ul>

<a name="api"></a>
<h1>New API</h1>

New functions will be necessary to :
<ul>
<li>detect touchscreen presence</li>
<li>disable assets extraction</li>
</ul>

(Work In Progress)

<a name="compiling"></a>
<h1>Compiling</h1>

<h2>Create a module compatible with the NDK build-system</h2>
<h2>Compile FreeGLUT for a traditional cross-compiler environment</h2>

<a name="using"></a>
<h1>Using in your projects</h1>

<a name="links"></a>
<h1>Links</h1>

<ul>

  <li>http://pygame.renpy.org/ : Pygame Subset for Android, it
    designed an API for managing Android app lifecycle
    (<code>android.check_pause</code> and
    <code>android.wait_for_resume</code>)</li>

</u>

<?php generateFooter(); ?>
