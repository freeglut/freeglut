<?php
require("../template.php");

# Now set the title of the page:
setPageTitle("API Documentation");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<p></p>
<div style="font-size: 1.6em; font-weight: bold; text-align: center;">
The Open-Source<br/>
OpenGL Utility Toolkit<br/>
(<i>freeglut</i> 2.0.0)<br/>
Application Programming Interface
</div>

<p></p>
<div style="font-size: 1.6em; font-weight: bold; text-align: center;">
Version 4.0
</div>

<p></p>
<div style="font-size: 1.2em; font-weight: bold; text-align: center;">
The <i>freeglut</i> Programming Consortium<br/>
July, 2003
</div>

<p>
OpenGL is a trademark of Silicon Graphics, Inc. X Window System is a trademark
of X Consortium, Inc.  Spaceball is a registered trademark of Spatial
Systems Inc.
</p>

<p>
The authors have taken care in preparation of this documentation but make
no expressed or implied warranty of any kind and assumes no responsibility
for errors or omissions. No liability is assumed for incidental or consequential
damages in connection with or arising from the use of information or programs
contained herein.
</p>

<h1>1. <a name="Contents"></a>Contents</h1>

<ol>
	<li><a href="#Contents">Contents</a></li>
	<li><a href="#Introduction">Introduction</a></li>
	<li><a href="#Background">Background</a>
		<ol>
			<li>Design Philosophy</li>
			<li>Conventions</li>
			<li>Terminology</li>
			<li>Differences from GLUT 3.7</li>
		</ol>
	</li>
	<li><a href="#Initialization">Initialization Functions</a>
		<ol>
			<li>glutInit</li>
			<li>glutInitWindowPosition, glutInitWindowSize</li>
			<li>glutInitDisplayMode</li>
			<li>glutInitDisplayString</li>
		</ol>
	</li>
	<li><a href="#EventProcessing">Event Processing Functions</a>
		<ol>
			<li>glutMainLoop</li>
			<li>glutMainLoopEvent</li>
			<li>glutLeaveMainLoop</li>
		</ol>
	</li>
	<li><a href="#Window">Window Functions</a>
		<ol>
			<li>glutCreateWindow</li>
			<li>glutCreateSubwindow</li>
			<li>glutDestroyWindow</li>
			<li>glutSetWindow, glutGetWindow</li>
			<li>glutSetWindowTitle, glutSetIconTitlew</li>
			<li>glutReshapeWindow</li>
			<li>glutPositionWindow</li>
			<li>glutShowWindow, glutHideWindow, glutIconifyWindow</li>
			<li>glutPushWindow, glutPopWindow</li>
			<li>glutFullScreen, glutLeaveFullScreen, glutFullScreenToggle</li>
		</ol>
	</li>
	<li><a href="#Display">Display Functions</a>
		<ol>
			<li>glutPostRedisplay</li>
			<li>glutPostWindowRedisplay</li>
			<li>glutSwapBuffers</li>
		</ol>
	</li>
	<li><a href="#MouseCursor">Mouse Cursor Functions</a>
		<ol>
			<li>glutSetCursor</li>
			<li>glutWarpPointer</li>
		</ol>
	</li>
	<li><a href="#Overlay">Overlay Functions</a>
		<ol>
			<li>glutEstablishOverlay</li>
			<li>glutRemoveOverlay</li>
			<li>glutUseLayer</li>
			<li>glutPostOverlayRedisplay</li>
			<li>glutPostWindowOverlayRedisplay</li>
			<li>glutShowOverlay, glutHideOverlay</li>
		</ol>
	</li>
	<li><a href="#Menu">Menu Functions</a>
		<ol>
			<li>glutCreateMenu</li>
			<li>glutDestroyMenu</li>
			<li>glutGetMenu, glutSetMenu</li>
			<li>glutAddMenuEntry</li>
			<li>glutAddSubMenu</li>
			<li>glutChangeToMenuEntry</li>
			<li>glutChangeToSubMenu</li>
			<li>glutRemoveMenuItem</li>
			<li>glutAttachMenu, glutDetachMenu</li>
            <li>glutMenuDestroyFunc</li>
		</ol>
	</li>
	<li><a href="#GlobalCallback">Global Callback Registration Functions</a>
		<ol>
			<li>glutTimerFunc</li>
			<li>glutIdleFunc</li>
            <li>glutMenuStatusFunc</li>
            <li>glutMenuStateFunc</li>
		</ol>
	</li>
	<li><a href="#WindowCallback">Window-Specific Callback Registration Functions</a>
		<ol>
			<li>glutDisplayFunc</li>
			<li>glutOverlayDisplayFunc</li>
			<li>glutReshapeFunc</li>
			<li>glutKeyboardFunc</li>
			<li>glutSpecialFunc</li>
			<li>glutKeyboardUpFunc</li>
			<li>glutSpecialUpFunc</li>
			<li>glutMotionFunc, glutPassiveMotionFunc</li>
			<li>glutVisibilityFunc</li>
			<li>glutEntryFunc</li>
			<li>glutJoystickFunc</li>
			<li>glutSpaceballMotionFunc</li>
			<li>glutSpaceballRotateFunc</li>
			<li>glutSpaceballButtonFunc</li>
			<li>glutButtonBoxFunc</li>
			<li>glutDialsFunc</li>
			<li>glutTabletMotionFunc</li>
			<li>glutTabletButtonFunc</li>
			<li>glutWindowStatusFunc</li>
		</ol>
	</li>
	<li><a href="#StateSetting">State Setting and Retrieval Functions</a>
		<ol>
			<li>glutSetOption</li>
			<li>glutGet</li>
			<li>glutDeviceGet</li>
			<li>glutGetModifiers</li>
			<li>glutLayerGet</li>
			<li>glutExtensionSupported</li>
			<li>glutGetProcAddress</li>
		</ol>
	</li>
	<li><a href="#FontRendering">Font Rendering Functions</a>
		<ol>
			<li>glutBitmapCharacter</li>
			<li>glutBitmapString</li>
			<li>glutBitmapWidth</li>
			<li>glutBitmapLength</li>
			<li>glutBitmapHeight</li>
			<li>glutStrokeCharacter</li>
			<li>glutStrokeString</li>
			<li>glutStrokeWidth</li>
			<li>glutStrokeLength</li>
			<li>glutStrokeHeight</li>
		</ol>
	</li>
	<li><a href="#GeometricObject">Geometric Object Rendering Functions</a>
		<ol>
			<li>glutWireSphere, glutSolidSphere</li>
			<li>glutWireTorus, glutSolidTorus</li>
			<li>glutWireCone, glutSolidCone</li>
			<li>glutWireCube, glutSolidCube</li>
			<li>glutWireTetrahedron, glutSolidTetrahedron</li>
			<li>glutWireOctahedron, glutSolidOctahedron</li>
			<li>glutWireDodecahedron, glutSolidDodecahedron</li>
			<li>glutWireIcosahedron, glutSolidIcosahedron</li>
			<li>glutWireRhombicDodecahedron, glutSolidRhombicDodecahedron</li>
			<li>glutWireTeapot, glutSolidTeapot</li>
		</ol>
	</li>
	<li><a href="#GameMode">Game Mode Functions</a>
		<ol>
			<li>glutGameModeString</li>
			<li>glutEnterGameMode, glutLeaveGameMode</li>
			<li>glutGameModeGet</li>
		</ol>
	</li>
	<li><a href="#VideoResize">Video Resize Functions</a>
		<ol>
			<li>glutVideoResizeGet</li>
			<li>glutSetupVideoResizing, glutStopVideoResizing</li>
			<li>glutVideoResize</li>
			<li>glutVideoPan</li>
		</ol>
	</li>
	<li><a href="#ColorMap">Color Map Functions</a>
		<ol>
			<li>glutSetColor, glutGetColor</li>
			<li>glutCopyColormap</li>
		</ol>
	</li>
	<li><a href="#MultiTouch">MultiTouch Functions</a>
		<ol>
			<li>glutMultiEntryFunc &larr; id, GLUT_ENTERED|GLUT_LEFT</li>
			<li>glutMultiButtonFunc &larr; id, x, y, button, GLUT_DOWN|GLUT_UP</li>
			<li>glutMultiMotionFunc &larr; id, x, y</li>
			<li>glutMultiPassiveFunc &larr; id, x, y</li>
		</ol>
	</li>
	<li><a href="#Android">Android functions</a>
		<ol>
			<li>glutInitContextFunc &larr; void</li>
			<li>glutPauseFunc &larr; void</li>
			<li>glutResumeFunc &larr; void</li>
		</ol>
	</li>
	<li><a href="#Miscellaneous">Miscellaneous Functions</a>
		<ol>
			<li>glutIgnoreKeyRepeat, glutSetKeyRepeat</li>
			<li>glutForceJoystickFunc</li>
			<li>glutReportErrors</li>
		</ol>
	</li>
	<li><a href="#UsageNotes">Usage Notes</a>
		<ol>
			<li><a href="#ImplementationNotes">Implementation Notes</a></li>
			<li><a href="#GLUT_State">GLUT State</a></li>
			<li><a href="#Freeglut.h_Header">"freeglut.h" Header File</a></li>
			<li><a href="#References">References</a></li>
			<li><a href="#Index">Index</a></li>
		</ol>
	</li>
</ol>

<h1>2. <a name="Introduction"></a>Introduction</h1>

<h1>3. <a name="Background"></a>Background</h1>

<p>
The OpenGL programming world owes a tremendous debt to Mr. Mark J. Kilgard
for writing the OpenGL Utility Toolkit, or GLUT.  The GLUT library
of functions allows an application programmer to create, control, and manipulate
windows independent of what operating system the program is running on.
By hiding the dependency on the operating system from the application programmer,
he allowed people to write truly portable OpenGL applications.
</p>

<p>
Mr. Kilgard copyrighted his library and gave it a rather unusual license.
Under his license,  people are allowed freely to copy and distribute the libraries
and the source code, but they are not allowed to modify it.  For a long time this did
not matter because the GLUT library worked so well and because Mr. Kilgard
was releasing updates on a regular basis.  But with the passage of time,
people started wanting some slightly different behaviours in their windowing
system.  When Mr. Kilgard stopped supporting the GLUT library in 1999,
having moved on to bigger and better things, this started to become a problem.
</p>

<p>
In December 1999, Mr. Pawel Olzsta started work on an open-source clone of the
GLUT library.
This open-source clone, which does not use any of the GLUT source code, has
evolved into the present <i>freeglut</i> library.  This documentation
specifies the application program interface to the <i>freeglut</i> library.
</p>

<h2>3.1 Design Philosophy</h2>

<h2>3.2 <a name="Conventions"></a>Conventions</h2>

<p>
In developing the <i>freeglut</i> library, we have taken careful steps
to ensure consistency in function operation across the board, in such a
manner as to maintain compatibility with GLUT's behavior whenever
possible. In this section some of the important conventions of FreeGLUT,
and their compatibility with GLUT, are made explicit.
</p>

<h3>3.2.1 Window placement and size</h3>

<p>
There is considerable confusion about the "right thing to do" concerning
window  size and position.  GLUT itself is not consistent between
Windows and UNIX/X11; since platform independence is a virtue for
<i>freeglut</i>, we decided to break with GLUT's behaviour. <br>
Under UNIX/X11, it is apparently not possible to get the window border
sizes in order to subtract them off the window's initial position until
some time after the window has been created.  Therefore we decided on
the following behavior, both under Windows and under UNIX/X11:
<ul><li>When you create a window with position (x,y) and size (w,h), the
upper left hand corner of the outside of the window is at (x,y) and the
size of the drawable area is (w,h).
<li>When you query the size and position of the window <i>freeglut</i>
will return the size of the drawable area--the (w,h) that you specified
when you created the window--and the coordinates of the upper left hand
corner of the drawable area--which is <u>NOT</u> the (x,y) position of
the window you specified when you created it.</ul>
</p>

<h2>3.3 Terminology</h2>

<h2>3.4 Differences from GLUT 3.7</h2>

<p>
Since the <i>freeglut</i> library was developed in order to update GLUT,
it is natural that there will be some differences between the two.
Each function in the API notes any differences between the GLUT and the <i>
freeglut</i> function behaviours.  The important ones are summarized
here.
</p>

<h3>3.4.1 glutMainLoop Behaviour</h3>

<p>
One of the commonest complaints about the GLUT library was that once an
application called <tt>glutMainLoop</tt>, it never got control back.
There was no way for an application to loop in GLUT for a while, possibly
as a subloop while a specific window was open, and then return to the calling
function.  A new function, <tt>glutMainLoopEvent</tt>, has been added
to allow this functionality.  Another function, <tt>glutLeaveMainLoop</tt>,
has also been added to allow the application to tell <i>freeglut</i> to clean
up and close down.
</p>

<h3>3.4.2 Action on Window Closure</h3>

<p>
Another difficulty with GLUT, especially with multiple-window programs,
is that if the user clicks on the "x" in the window header the application
exits immediately.  The application programmer can now set an option,
<tt> GLUT_ACTION_ON_WINDOW_CLOSE</tt>, to specify whether execution should
continue, whether GLUT should return control to the main program, or whether
GLUT should simply exit (the default).
</p>

<h3>3.4.3 Fullscreen windows</h3>

<p>
Function to leave fullscreen window mode, <tt>glutLeaveFullScreen</tt>,
or to toggle between fullscreen and normal window mode,
<tt>glutFullScreenToggle</tt>, have been added.
</p>

<h3>3.4.4 Changes to Callbacks</h3>

<p>
Several new callbacks have been added and several callbacks which were specific
to Silicon Graphics hardware have not been implemented.  Most or all
of the new callbacks are listed in the GLUT Version 4 "glut.h" header file
but did not make it into the documentation.  The new callbacks consist
of regular and special key release callbacks, a joystick callback, a menu
state callback (with one argument, distinct from the menu status callback
which has three arguments), and a window status callback
 (also with one argument).  Unsupported callbacks are the two Tablet
callbacks.  If the user has a need for an unsupported callback he should
contact the <i>freeglut</i> development team.
</p>

<h3>3.4.5 String Rendering</h3>

<p>
New functions have been added to render full character strings (including
carriage returns) rather than rendering one character at a time.  More
functions return the widths of character strings and the font heights, in
pixels for bitmapped fonts and in OpenGL units for the stroke fonts.
</p>

<h3>3.4.6  Geometry Rendering</h3>

<p>
Two functions have been added to render a wireframe and a solid rhombic
dodecahedron.
</p>

<h3>3.4.7  Extension Function Queries</h3>

<p>
glutGetProcAddress is a wrapper for the glXGetProcAddressARB and wglGetProcAddress
functions.
</p>

<h1>4. <a name="Initialization"></a>Initialization Functions</h1>

<h2>4.1 glutInit</h2>

<h2>4.2 glutInitWindowPosition, glutInitWindowSize</h2>

<p>
The <tt>glutInitWindowPosition</tt> and <tt>glutInitWindowSize</tt>
functions specify a desired position and size for windows that <i>freeglut</i>
will create in the future.
</p>

<p><b>Usage</b></p>

<p><tt>void glutInitWindowPosition ( int x, int y );</tt> <br/>
   <tt>void glutInitWindowSize ( int width, int height );</tt> </p>

<p><b>Description</b></p>

<p>
The <tt>glutInitWindowPosition</tt> and <tt>glutInitWindowSize</tt>
functions specify a desired position and size for windows that
<i>freeglut</i> will create in the future.
The position is measured in pixels from the upper left hand corner of the
screen, with "x" increasing to the right and "y" increasing towards the bottom
of the screen.  The size is measured in pixels.  <i>Freeglut</i>
does not promise to follow these specifications in creating its windows,
but it certainly makes an attempt to.
</p>

<p>
The position and size of a window are a matter of some subtlety.  Most
windows have a usable area surrounded by a border and with a title bar
on the top.  The border and title bar are commonly called "decorations."
The position of the window unfortunately varies with the operating system.
On both Linux and Windows, you specify the coordinates of the upper
left-hand corner of the window's decorations. Also for both operating
systems, the size of the window is the size of the usable interior.<br>
With <tt>glutGet</tt> information can be acquired about the current
window's size, position and decorations. Note however that according to
<a href="#Conventions">FreeGLUT's conventions</a>, the information
returned about the window coordinates does not correspond to the
coordinates used when setting window position.
</p>

<p>
Windows has some additional quirks which the application programmer
should know about.  First, the minimum y-coordinate
of a window decoration is zero.  (This is a feature of <i>freeglut</i>
and can be adjusted if so desired.)  Second, there appears to be a
minimum window width on Windows which is 104 pixels.  The user may specify
a smaller width, but the Windows system calls ignore it.  It is also
impossible to make a window narrower than this by dragging on its corner.
</p>

<p><b>Changes From GLUT</b></p>

<p>
For some reason, GLUT is not affected by the 104-pixel minimum window width.
If the user clicks on the corner of a window which is narrower than this amount,
the window will immediately snap out to this width, but the application can
call <tt>glutReshapeWindow</tt> and make a window narrower again.
</p>

<h2>4.3 glutInitDisplayMode</h2>

<h2>4.4 glutInitDisplayString</h2>

<h1>5. <a name="EventProcessing"></a>Event Processing Functions</h1>

<p>
After an application has finished initializing its windows and menus, it
enters an event loop.  Within this loop, <i>freeglut</i> polls the
data entry devices (keyboard, mouse, etc.) and calls the application's appropriate
callbacks.
</p>

<p>
In GLUT, control never returned from
the event loop (as invoked by the <tt>glutMainLoop</tt> function) to the
calling function.  This prevented an application from having re-entrant
code, in which GLUT could be invoked from within a callback, and it prevented
the application from doing any post-processing (such as freeing allocated
memory) after GLUT had closed down.  <i>Freeglut</i> allows the application
programmer to specify more direct control over the event loop by means of
two new functions.  The first, <tt>glutMainLoopEvent</tt>, processes
a single iteration of the event loop and allows the application to use a different
event loop controller or to contain re-entrant code.  The second, <tt>
glutLeaveMainLoop</tt>, causes the event loop to exit nicely; this is preferable
to the application's calling <tt>exit</tt> from within a GLUT callback.
</p>

<h2>5.1 glutMainLoop</h2>

<p>
The <tt>glutMainLoop</tt> function enters the event loop.
</p>

<p><b>Usage</b></p>

<p><tt>void glutMainLoop ( void );</tt></p>

<p><b>Description</b></p>

<p>The <tt>glutMainLoop</tt> function
causes the program to enter the window event loop.  An application should
call this function at most once.  It will call any application callback
functions as required to process mouse clicks, mouse motion, key presses,
and so on.
</p>

<p><b>Changes From GLUT</b></p>

<p>
In GLUT, there was absolutely no way for the application programmer to
have control return from the <tt>glutMainLoop</tt> function to the
calling function.  <i>Freeglut</i> allows the programmer
to force this by setting the <tt>GLUT_ACTION_ON_WINDOW_CLOSE</tt> option
and invoking the <tt>glutLeaveMainLoop</tt> function from one of the callbacks.
Stopping the program this way is preferable to simply calling <tt>exit</tt>
from within a callback because this allows <i>freeglut</i> to free allocated
memory and otherwise clean up after itself.  (I know I just said this,
but I think it is important enough that it bears repeating.)
</p>

<h2>5.2  glutMainLoopEvent</h2>

<p>
The <tt>glutMainLoopEvent</tt> function processes a single iteration
in the <i>freeglut</i> event loop.
</p>

<p><b>Usage</b></p>

<p><tt>void glutMainLoopEvent ( void );</tt></p>

<p><b>Description</b></p>

<p>
The <tt>glutMainLoopEvent</tt> function causes <i>freeglut</i> to process
one iteration's worth of events in its event loop.  This allows the application
to control its own event loop and still use the <i>freeglut</i> windowing system.
</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT does not include this function.</p>

<h2>5.3  glutLeaveMainLoop</h2>

<p>
The <tt>glutLeaveMainLoop</tt> function causes <i>freeglut</i> to stop
its event loop.
</p>

<p><b>Usage</b></p>

<p><tt>void glutLeaveMainLoop ( void );</tt>
 </p>

<p><b>Description</b></p>

<p>
The  <tt>glutLeaveMainLoop</tt> function causes <i>freeglut</i> to stop the event loop.  If the
<tt>GLUT_ACTION_ON_WINDOW_CLOSE</tt> option has been set to <tt>GLUT_ACTION_CONTINUE_EXECUTION</tt>,
control will return to the function which called <tt>glutMainLoop</tt>; otherwise the application will exit.
</p>

<p>
If the application has two nested calls to <tt>glutMainLoop</tt> and calls
<tt>glutLeaveMainLoop</tt>, the behaviour
of <i>freeglut</i> is undefined.  It may leave only the inner nested
loop or it may leave both loops.  If the reader has a strong preference
for one behaviour over the other he should contact the <i>freeglut</i> Programming
Consortium and ask for the code to be fixed.
</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT does not include this function.</p>

<h1>6. <a name="Window"></a>Window Functions</h1>

<h2>6.1 glutCreateWindow</h2>

<h2>6.2 glutCreateSubwindow</h2>

<h2>6.3 glutDestroyWindow</h2>

<h2>6.4 glutSetWindow, glutGetWindow</h2>

<h2>6.5 glutSetWindowTitle, glutSetIconTitle</h2>

<h2>6.6 glutReshapeWindow</h2>

<h2>6.7 glutPositionWindow</h2>

<h2>6.8 glutShowWindow, glutHideWindow, glutIconifyWindow</h2>

<h2>6.9 glutPushWindow, glutPopWindow</h2>

<h2>6.10 glutFullScreen, glutLeaveFullScreen, glutFullScreenToggle</h2>

<p>
The <tt>glutFullScreen</tt>, <tt>glutLeaveFullScreen</tt> and
<tt>glutFullScreenToggle</tt> functions are used to transition the
current window between fullscreen and normal mode.
</p>

<p><b>Usage</b></p>

<p><tt>void glutFullScreen ( void );</tt><br>
<tt>void glutLeaveFullScreen ( void );</tt><br>
<tt>void glutFullScreenToggle ( void );</tt>
 </p>

<p><b>Description</b></p>

<p>
The <tt>glutFullScreen</tt> function causes the current window to enter
fullscreen mode, <tt>glutLeaveFullScreen</tt> to go back to the window
size and position as it was before entering fullscreen mode, and
<tt>glutFullScreenToggle</tt> toggles between fullscreen and normal
mode.<br>
In multi-monitor setups on Windows 2000 and newer, the window will
become fullscreen on the monitor that it overlaps the most.<br>
Calls to these functions are ignored for gamemode and child windows.<br>
Use <tt>glutGet(GLUT_FULL_SCREEN)</tt> to query fullscreen state of
current window.
</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT does not include the <tt>glutLeaveFullScreen</tt> and
<tt>glutFullScreenToggle</tt> functions.</p>

<h1>7. <a name="Display"></a>Display Functions</h1>

<h2>7.1 glutPostRedisplay</h2>

<h2>7.2 glutPostWindowRedisplay</h2>

<h2>7.3 glutSwapBuffers</h2>

<h1>8. <a name="MouseCursor"></a>Mouse Cursor Functions</h1>

<h2>8.1 glutSetCursor</h2>

<h2>8.2 glutWarpPointer</h2>

<h1>9. <a name="Overlay"></a>Overlay Functions</h1>

<p>
<i>Freeglut</i> does not allow overlays, although it does "answer the mail"
with function stubs so that GLUT-based programs can compile and link against
<i>freeglut</i> without modification.
</p>

<p>
If the reader needs overlays, he should contact the <i>freeglut</i> Programming
Consortium and ask for them to be implemented.  He should also be prepared
to assist in the implementation.
</p>

<h2>9.1  glutEstablishOverlay</h2>

<p>
The <tt>glutEstablishOverlay</tt> function is not implemented in <i>freeglut</i>.
</p>

<p><b>Usage</b></p>

<p><tt>void glutEstablishOverlay ( void );</tt> </p>

<p><b>Description</b></p>

<p>
The <tt>glutEstablishOverlay</tt> function is not implemented in <i>freeglut</i>.
</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT implements this function. </p>

<h2>9.2  glutRemoveOverlay</h2>

<p>
The <tt>glutRemoveOverlay</tt> function is not implemented in <i>freeglut</i>.
</p>

<p><b>Usage</b></p>

<p><tt>void glutRemoveOverlay ( void );</tt></p>

<p><b>Description</b></p>

<p>
The <tt>glutRemoveOverlay</tt> function
is not implemented in <i>freeglut</i>.
</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT implements this function. </p>

<h2>9.3  glutUseLayer</h2>

<p>
The <tt>glutUseLayer</tt> function is not implemented in <i>freeglut</i>.
</p>

<p><b>Usage</b></p>

<p><tt>void glutUseLayer ( GLenum layer );</tt> </p>

<p><b>Description</b></p>

<p>
The <tt>glutUseLayer</tt> function
is not implemented in <i>freeglut</i>.
</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT implements this function. </p>

<h2>9.4 glutPostOverlayRedisplay</h2>

<p>
The <tt>glutPostOverlayRedisplay</tt> function is not implemented in <i>freeglut</i>.
</p>

<p><b>Usage</b></p>

<p><tt>void glutPostOverlayRedisplay ( void );</tt> </p>

<p><b>Description</b></p>

<p>
The <tt>glutPostOverlayRedisplay</tt> function is not implemented
in <i>freeglut</i>.
</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT implements this function. </p>

<h2>9.5  glutPostWindowOverlayRedisplay</h2>

<p>
The <tt>glutPostWindowOverlayRedisplay</tt> function is not implemented
in <i>freeglut</i>.
</p>

<p><b>Usage</b></p>

<p><tt>void glutPostWindowOverlayRedisplay ( int window );</tt> </p>

<p><b>Description</b></p>

<p>
The <tt>glutPostWindowOverlayRedisplay</tt> function is not implemented in <i>freeglut</i>.
</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT implements this function. </p>

<h2>9.6 glutShowOverlay, glutHideOverlay</h2>

<p>
The <tt>glutShowOverlay</tt> and <tt>glutHideOverlay</tt> functions
are not implemented in <i>freeglut</i>.
</p>

<p><b>Usage</b></p>

<p><tt>void glutShowOverlay( void );</tt>
  <br/>
 <tt>void glutHideOverlay( void );</tt>
  </p>

<p><b>Description</b></p>

<p>
The <tt>glutShowOverlay</tt> and <tt>glutHideOverlay</tt> functions are not implemented in <i>freeglut</i>.
</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT implements these functions. </p>

<h1>10. <a name="Menu"></a>Menu Functions</h1>

<h2>10.1 glutCreateMenu</h2>

<h2>10.2 glutDestroyMenu</h2>

<h2>10.3 glutGetMenu, glutSetMenu</h2>

<h2>10.4 glutAddMenuEntry</h2>

<h2>10.5 glutAddSubMenu</h2>

<h2>10.6 glutChangeToMenuEntry</h2>

<h2>10.7 glutChangeToSubMenu</h2>

<h2>10.8 glutRemoveMenuItem</h2>

<h2>10.9 glutAttachMenu, glutDetachMenu</h2>

<h2>10.10 glutMenuDestroyFunc</h2>

<h1>11. <a name="GlobalCallback"></a>Global Callback Registration Functions</h1>

<h2>11.1 glutTimerFunc</h2>

<h2>11.2 glutIdleFunc</h2>

<p>
The <tt>glutIdleFunc</tt> function sets the global idle callback. <i>
Freeglut</i>  calls the idle callback when there are no inputs from the user.
</p>

<p><b>Usage</b></p>

<p><tt>void glutIdleFunc ( void (*func)
( void ) );</tt> </p>

<p><tt>func</tt>The new
global idle callback function </p>

<p><b>Description</b></p>

<p>
The <tt>glutIdleFunc</tt> function
specifies the function that <i>freeglut</i> will call to perform background
processing tasks such as continuous animation when window system events are
not being received.  If enabled, this function is called continuously
from <i>freeglut</i> while no events are received.  The callback function
has no parameters and returns no value.  <i>Freeglut</i> does not change
the <i>current window</i> or the <i>current menu</i> before invoking the idle
callback; programs with multiple windows or menus must explicitly set the
<i>current window</i> and <i>current menu</i>
 and not rely on its current setting. <br/>
The amount of computation and rendering done in an idle
callback should be minimized to avoid affecting the program's interactive
 response.  In general, no more than a single frame of rendering should
 be done in a single invocation of an idle callback. <br/>
Calling <tt>glutIdleFunc</tt> with a NULL argument
disables the call to an idle callback.
</p>

<p><b>Changes From GLUT</b></p>

<p>Application programmers should note that
if they have specified the "continue execution" action on window closure,
<i>freeglut</i> will continue to call the
idle callback after the user has closed a window by clicking on the "x" in
the window header bar.  If the idle callback renders a particular window
(this is considered bad form but is frequently done anyway), the programmer
should supply a window closure callback for that window which changes or disables
the idle callback. </p>

<h2>11.3 glutMenuStatusFunc</h2>

<h2>11.4 glutMenuStateFunc</h2>

<h1>12. <a name="WindowCallback"></a>Window-Specific Callback Registration Functions</h1>

<h2>12.1 glutDisplayFunc</h2>

<h2>12.2 glutOverlayDisplayFunc</h2>

<h2>12.3 glutReshapeFunc</h2>

<h2>12.4 glutCloseFunc</h2>

<h2>12.5 glutKeyboardFunc</h2>

<h2>12.6 glutSpecialFunc</h2>

<p>
The <tt>glutSpecialFunc</tt> function sets the window's special key press
callback. <i>Freeglut</i> calls the special key press callback when the
user presses a special key.
</p>

<p><b>Usage</b></p>

<p><tt>void glutSpecialFunc ( void (*func)
( int key, int x, int y ) );</tt> </p>

<p><tt>func </tt>The window's
new special key press callback function <br/>
 <tt>key </tt>The
key whose press triggers the callback <br/>
 <tt>x
</tt>The x-coordinate of the mouse relative
to the window at the time the key is pressed <br/>
 <tt>y
</tt>The y-coordinate of the mouse relative
to the window at the time the key is pressed </p>

<p><b>Description</b></p>

<p>
The <tt>glutSpecialFunc</tt> function specifies the function
that <i>freeglut</i> will call when the user
presses a special key on the keyboard.  The callback function has one
argument: the name of the function to be invoked ("called back") at
the time at which the special key is pressed.  The function returns no
value.  <i>Freeglut</i> sets the <i>current window</i> to the window
which is active when the callback is invoked.  "Special keys" are the
function keys, the arrow keys, the Page Up and Page Down keys, and the Insert
key.  The Delete key is considered to be a regular key. <br/>
Calling <tt>glutSpecialUpFunc</tt> with a NULL argument
disables the call to the window's special key press callback. </p>

<p>
The <tt>key</tt> argument may take one of the following defined constant values:
</p>

<ul>
	<li><tt>GLUT_KEY_F1, GLUT_KEY_F2, ..., GLUT_KEY_F12</tt> - F1 through F12 keys</li>
	<li><tt>GLUT_KEY_PAGE_UP, GLUT_KEY_PAGE_DOWN</tt>        - Page Up and Page Down keys</li>
	<li><tt>GLUT_KEY_HOME, GLUT_KEY_END</tt>                 - Home and End keys</li>
	<li><tt>GLUT_KEY_LEFT, GLUT_KEY_RIGHT, GLUT_KEY_UP, GLUT_KEY_DOWN</tt> - Arrow keys</li>
	<li><tt>GLUT_KEY_INSERT</tt>                             - Insert key</li>
</ul>

<p><b>Changes From GLUT</b></p>

<p>None.</p>

<h2>12.7 glutKeyboardUpFunc</h2>

<p>
The <tt>glutKeyboardUpFunc</tt> function sets the window's key release
callback. <i>Freeglut</i> calls the key release callback when the user releases
a key.
</p>

<p><b>Usage</b></p>

<p><tt>void glutKeyboardUpFunc ( void (*func)
( unsigned char key, int x, int y ) );</tt> </p>

<p><tt>func </tt>The window's
new key release callback function <br/>
 <tt>key </tt>The
key whose release triggers the callback <br/>
 <tt>x
</tt>The x-coordinate of the mouse relative
to the window at the time the key is released <br/>
 <tt>y
</tt>The y-coordinate of the mouse relative
to the window at the time the key is released </p>

<p><b>Description</b></p>

<p>
The <tt>glutKeyboardUpFunc</tt>
function specifies the function that <i>freeglut</i> will call when the
user releases a key from the keyboard.  The callback function has one
argument:  the name of the function to be invoked ("called back") at
the time at which the key is released.  The function returns no value.
<i>Freeglut</i> sets the <i>current window</i>
  to the window which is active when the callback is invoked. <br/>
  While <i>freeglut</i> checks for upper or lower case
letters, it does not do so for non-alphabetical characters.  Nor does
it account for the Caps-Lock key being on.  The operating system may
send some unexpected characters to <i>freeglut</i>, such as "8" when the
user is pressing the Shift key.  <i>Freeglut</i> also invokes the callback
when the user releases the Control, Alt, or Shift keys, among others.
Releasing the Delete key causes this function to be invoked with a value
of 127 for <tt>key</tt>. <br/>
Calling <tt>glutKeyboardUpFunc</tt> with a NULL argument
disables the call to the window's key release callback. </p>

<p><b>Changes From GLUT</b></p>

<p>This function is not implemented in GLUT
versions before Version 4.  It has been designed to be as close to GLUT
as possible.  Users who find differences should contact the
                          <i>freeglut</i>Programming Consortium  to
have them fixed. </p>

<h2>12.8 glutSpecialUpFunc</h2>

<p>
The <tt>glutSpecialUpFunc</tt> function sets the window's special key
release callback. <i>Freeglut</i> calls the special key release callback
when the user releases a special key.
</p>

<p><b>Usage</b></p>

<p><tt>void glutSpecialUpFunc ( void (*func)
( int key, int x, int y ) );</tt> </p>

<p><tt>func </tt>The window's
new special key release callback function <br/>
 <tt>key </tt>The
key whose release triggers the callback <br/>
 <tt>x
</tt>The x-coordinate of the mouse relative
to the window at the time the key is released <br/>
 <tt>y
</tt>The y-coordinate of the mouse relative
to the window at the time the key is released </p>

<p><b>Description</b></p>

<p>
The <tt>glutSpecialUpFunc</tt>function specifies the function that <i>freeglut</i> will call when the
user releases a special key from the keyboard.  The callback function
has one argument: the name of the function to be invoked ("called back")
at the time at which the special key is released.  The function returns
no value. <i>Freeglut</i> sets the <i>current window</i> to the window
which is active when the callback is invoked.  "Special keys" are the
function keys, the arrow keys, the Page Up and Page Down keys, and the Insert
key.  The Delete key is considered to be a regular key. <br/>
 Calling <tt>glutSpecialUpFunc</tt> with a NULL argument
disables the call to the window's special key release callback. </p>

<p>
The <tt>key</tt> argument may take one of the following defined constant values: </p>

<ul>
	<li><tt>GLUT_KEY_F1, GLUT_KEY_F2, ..., GLUT_KEY_F12</tt>  - F1 through F12 keys</li>
	<li><tt>GLUT_KEY_PAGE_UP, GLUT_KEY_PAGE_DOWN</tt>  - Page Up and Page Down keys</li>
	<li><tt>GLUT_KEY_HOME, GLUT_KEY_END</tt>  - Home and End keys</li>
	<li><tt>GLUT_KEY_LEFT, GLUT_KEY_RIGHT, GLUT_KEY_UP, GLUT_KEY_DOWN</tt> - arrow keys</li>
	<li><tt>GLUT_KEY_INSERT</tt> - Insert key</li>
</ul>

<p><b>Changes From GLUT</b></p>

<p>
This function is not implemented in GLUT
versions before Version 4.  It has been designed to be as close to GLUT
as possible.  Users who find differences should contact the
<i>freeglut</i> Programming Consortium  to
have them fixed.
</p>

<h2>12.9 glutMouseFunc</h2>

<h2>12.10 glutMotionFunc, glutPassiveMotionFunc</h2>

<h2>12.11 glutVisibilityFunc</h2>

<h2>12.12 glutEntryFunc</h2>

<h2>12.13 glutJoystickFunc</h2>

<h2>12.14 glutSpaceballMotionFunc</h2>

<p>
The <tt>glutSpaceballMotionFunc</tt> function is implemented in
<i>freeglut</i> on X11 only.  On other platforms, function stubs are
provided so that GLUT-based programs can compile and link against
<i>freeglut</i> without modification.
</p>

<p><b>Usage</b></p>

<p><tt>void glutSpaceballMotionFunc ( void
(* callback)( int x, int y, int z )</tt><tt> );</tt></p>

<p><b>Description</b></p>

<p>TODO</p>

<h2>12.15 glutSpaceballRotateFunc</h2>

<p>
The <tt>glutSpaceballRotateFunc</tt> function is implemented in
<i>freeglut</i> on X11 only.  On other platforms, function stubs are
provided so that GLUT-based programs can compile and link against
<i>freeglut</i> without modification.
</p>

<p><b>Usage</b></p>

<p><tt>void glutSpaceballRotateFunc ( void
(* callback)( int x, int y, int z )</tt><tt> );</tt></p>

<p><b>Description</b></p>

<p>TODO</p>

<h2>12.16 glutSpaceballButtonFunc</h2>

<p>
The <tt>glutSpaceballButtonFunc</tt> function is implemented in
<i>freeglut</i> on X11 only.  On other platforms, function stubs are
provided so that GLUT-based programs can compile and link against
<i>freeglut</i> without modification.
</p>

<p><b>Usage</b></p>

<p><tt>void glutSpaceballButtonFunc ( void
(* callback)( int button, int updown )</tt><tt> );</tt></p>

<p><b>Description</b></p>

<p>TODO</p>

<h2>12.17 glutButtonBoxFunc</h2>

<p>
The <tt>glutDialsFunc</tt> function sets the global dials&buttons box callback. Freeglut calls the callback when there is input from the box buttons.
</p>

<p><b>Usage</b></p>

<p>
<tt>void glutButtonBoxFunc ( void (* callback)( int button, int updown ) );</tt>
</p>

<p><b>Description</b></p>

<p>
The Dials&Buttons box is an ancient device presenting several pushable
or rotatable buttons, sending the events to the computer via serial
I/O.<br />

See <a
href="http://www.reputable.com/sgipix/sgi-dialnbutton1.jpg">http://www.reputable.com/sgipix/sgi-dialnbutton1.jpg</a>
[<a href="http://www.nekochan.net/reputable/sgipix.html">1</a>]
for instance.
</p>

<h2>12.18 glutDialsFunc</h2>

<p>
The <tt>glutDialsFunc</tt> function sets the global dials&buttons box callback. Freeglut calls the callback when there is input from the box dials.
</p>

<p><b>Usage</b></p>

<p><tt>void glutDialsFunc ( void (* callback)(
int dial, int value )</tt><tt> );</tt></p>

<p><b>Description</b></p>

<p>
The Dials&Buttons box is an ancient device presenting several pushable
or rotatable buttons, sending the events to the computer via serial
I/O.<br />

See <a
href="http://www.reputable.com/sgipix/sgi-dialnbutton1.jpg">http://www.reputable.com/sgipix/sgi-dialnbutton1.jpg</a>
[<a href="http://www.nekochan.net/reputable/sgipix.html">1</a>]
for instance.
</p>

<h2>12.19 glutTabletMotionFunc</h2>

<p>
The <tt>glutTabletMotionFunc</tt> function is not implemented in <i>
freeglut</i>, although the library does "answer the mail" to the extent
that a call to the function will not produce an error..
</p>

<p><b>Usage</b></p>

<p>
<tt>void glutTabletMotionFunc ( void (* callback)( int x, int y ) );</tt>
</p>

<p><b>Description</b></p>

<p>The <tt>glutTabletMotionFunc</tt> function
is not implemented in <i>freeglut</i>. </p>

<p><b>Changes From GLUT</b></p>

<p>GLUT implements this function. </p>

<h2>12.20 glutTabletButtonFunc</h2>

<p>
The <tt>glutTabletButtonFunc</tt> function is not implemented in <i>
freeglut</i>, although the library does "answer the mail" to the extent
that a call to the function will not produce an error..
</p>

<p><b>Usage</b></p>

<p><tt>void glutTabletButtonFunc ( void
(* callback)( int button, int updown, int x, int y )</tt><tt> );</tt></p>

<p><b>Description</b></p>

<p>
The <tt>glutTabletButtonFunc</tt> function
is not implemented in <i>freeglut</i>.
</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT implements this function. </p>

<h2>12.21 glutWindowStatusFunc</h2>

<h1>13. <a name="StateSetting"></a>State Setting and Retrieval Functions</h1>

<h2>13.1 glutSetOption</h2>

<h2>13.2 glutGet</h2>


<p>
The following state variables may be queried with <tt>glutGet</tt>.
The returned value is an integer.
</p>

<p>
example:<br>
<tt>int windowLeft = glutGet(GLUT_WINDOW_X);</tt>
</p>

<p>
These queries are with respect to the current window:
</p>

<ul>
<li>GLUT_WINDOW_X - window X position, see <a href="#Conventions">FreeGLUT's conventions</a></li>
<li>GLUT_WINDOW_Y - window Y position, see <a href="#Conventions">FreeGLUT's conventions</a></li>
<li>GLUT_WINDOW_WIDTH - window width, see <a href="#Conventions">FreeGLUT's conventions</a></li>
<li>GLUT_WINDOW_HEIGHT - window height, see <a href="#Conventions">FreeGLUT's conventions</a></li>
<li>GLUT_WINDOW_BORDER_WIDTH - window border width</li>
<li>GLUT_WINDOW_BORDER_HEIGHT - window border height</li>
<li>GLUT_WINDOW_BUFFER_SIZE - number of color or color index bits per pixel</li>
<li>GLUT_WINDOW_STENCIL_SIZE - number of bits per stencil value</li>
<li>GLUT_WINDOW_DEPTH_SIZE - number of bits per depth value</li>
<li>GLUT_WINDOW_RED_SIZE - number of bits per red value</li>
<li>GLUT_WINDOW_GREEN_SIZE - number of bits per green value</li>
<li>GLUT_WINDOW_BLUE_SIZE - number of bits per blue value</li>
<li>GLUT_WINDOW_ALPHA_SIZE - number of bits per alpha value</li>
<li>GLUT_WINDOW_ACCUM_RED_SIZE - number of red bits in the accumulation buffer</li>
<li>GLUT_WINDOW_ACCUM_GREEN_SIZE - number of green bits in the accumulation buffer</li>
<li>GLUT_WINDOW_ACCUM_BLUE_SIZE - number of blue bits in the accumulation buffer</li>
<li>GLUT_WINDOW_ACCUM_ALPHA_SIZE - number of alpha bits in the accumulation buffer</li>
<li>GLUT_WINDOW_DOUBLEBUFFER - 1 if the color buffer is double buffered, 0 otherwise</li>
<li>GLUT_WINDOW_RGBA - 1 if the color buffers are RGB[A], 0 for color index</li>
<li>GLUT_WINDOW_PARENT - parent window ID</li>
<li>GLUT_WINDOW_NUM_CHILDREN - number of child windows</li>
<li>GLUT_WINDOW_COLORMAP_SIZE - number of entries in the window's colormap</li>
<li>GLUT_WINDOW_NUM_SAMPLES - number of samples per pixel if using multisampling</li>
<li>GLUT_WINDOW_STEREO - 1 if the window supports stereo, 0 otherwise</li>
<li>GLUT_WINDOW_CURSOR - current cursor</li>
<li>GLUT_WINDOW_FORMAT_ID - on Windows, return the pixel format number of the current window</li>
<li>GLUT_FULL_SCREEN - 1 if window is currently in fullscreen mode</li>
</ul>

<p>
These queries do not depend on the current window.
</p>

<ul>
<li>GLUT_SCREEN_WIDTH - width of the screen in pixels</li>
<li>GLUT_SCREEN_HEIGHT - height of the screen in pixels</li>
<li>GLUT_SCREEN_WIDTH_MM - width of the screen in millimeters</li>
<li>GLUT_SCREEN_HEIGHT_MM - height of the screen in millimeters</li>
<li>GLUT_MENU_NUM_ITEMS - number of items in the current menu</li>
<li>GLUT_DISPLAY_MODE_POSSIBLE - return 1 if the current display mode is supported, 0 otherwise</li>
<li>GLUT_INIT_WINDOW_X - X position last set by glutInitWindowPosition</li>
<li>GLUT_INIT_WINDOW_Y - Y position last set by glutInitWindowPosition</li>
<li>GLUT_INIT_WINDOW_WIDTH - width last set by glutInitWindowSize</li>
<li>GLUT_INIT_WINDOW_HEIGHT - height last set by glutInitWindowSize</li>
<li>GLUT_INIT_DISPLAY_MODE - display mode last set by glutInitDisplayMode</li>
<li>GLUT_ELAPSED_TIME - time (in milliseconds) elapsed since glutInit or glutGet(GLUT_ELAPSED_TIME) was first called</li>
<li>GLUT_INIT_STATE - 1 if <i>freeglut</i> has been initialized through
    a call to <tt>glutInit</tt></li>
<li>GLUT_VERSION - Return value will be X*10000+Y*100+Z where X is the
    major version, Y is the minor version and Z is the patch level.
    This query is only supported in <i>freeglut</i> (version 2.0.0 or later).</li>
</ul>

<h2>13.3 glutDeviceGet</h2>

<h2>13.4 glutGetModifiers</h2>

<h2>13.5 glutLayerGet</h2>

<h2>13.6 glutExtensionSupported</h2>

<h2>13.7 glutGetProcAddress</h2>

<p>
<tt>glutGetProcAddress</tt> returns
a pointer to a named GL or <i>freeglut</i> function.
</p>

<p><b>Usage</b></p>

<p><tt>void *glutGetProcAddress ( const char *procName );</tt></p>

<p><tt>procName</tt> Name of an OpenGL or GLUT function. </p>

<p><b>Description</b></p>

<p>
<tt>glutGetProcAddress</tt> is useful
for dealing with OpenGL extensions. If an application calls OpenGL extension
functions directly, that application will only link/run with an OpenGL library
that supports the extension. By using a function pointer returned from glutGetProcAddress(),
the application will avoid this hard dependency and be more portable and interoperate
better with various implementations of OpenGL.
</p>

<p>
Both OpenGL functions and <i>freeglut</i>
functions can be queried with this function.
</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT does not include this function.
 </p>

<h1>14. <a name="FontRendering"></a>Font Rendering Functions</h1>

<p>
<i>Freeglut</i> supports two types of font rendering:  bitmap fonts,
which are rendered using the <tt>glBitmap</tt> function call, and stroke
fonts, which are rendered as sequences of OpenGL line segments. Because
they are rendered as bitmaps, the bitmap fonts tend to render more quickly
than stroke fonts, but they are less flexible in terms of scaling and rendering.
Bitmap font characters are positioned with calls to the <tt>glRasterPos*</tt>
functions while stroke font characters use the OpenGL transformations
to position characters.
</p>

<p>
It should be noted that <i>freeglut</i> fonts are similar but not identical to GLUT fonts.
At the moment, <i>freeglut</i> fonts do not support the "`" (backquote) and
"|" (vertical line) characters; in their place it renders asterisks.
</p>

<p>
<i>Freeglut</i> supports the following bitmap fonts:
</p>

<ul>
	<li><tt>GLUT_BITMAP_8_BY_13</tt> - A variable-width font with every character fitting in a rectangle of 13 pixels high by at most 8 pixels wide.</li>
	<li><tt>GLUT_BITMAP_9_BY_15</tt> - A variable-width font with every character fitting in a rectangle of 15 pixels high by at most 9 pixels wide.</li>
	<li><tt>GLUT_BITMAP_TIMES_ROMAN_10</tt> - A 10-point variable-width Times Roman font.</li>
	<li><tt>GLUT_BITMAP_TIMES_ROMAN_24</tt> - A 24-point variable-width Times Roman font.</li>
	<li><tt>GLUT_BITMAP_HELVETICA_10</tt> - A 10-point variable-width Helvetica font.</li>
	<li><tt>GLUT_BITMAP_HELVETICA_12</tt> - A 12-point variable-width Helvetica font.</li>
	<li><tt>GLUT_BITMAP_HELVETICA_18</tt> - A 18-point variable-width Helvetica font.</li>
</ul>

<p>
<i>Freeglut</i> calls <tt>glRasterPos4v</tt> to advance the cursor by
the width of a character and to render carriage returns when appropriate.
It does not use any display lists in it rendering in bitmap fonts.
</p>

<p>
<i>Freeglut</i> supports
the following stroke fonts:
</p>

<ul>
	<li><tt>GLUT_STROKE_ROMAN</tt> - A proportionally-spaced Roman Simplex font</li>
	<li><tt>GLUT_STROKE_MONO_ROMAN</tt> - A fixed-width Roman Simplex font</li>
</ul>

<p>
<i>Freeglut</i> does not use any display lists in its rendering of stroke
fonts.  It calls <tt>glTranslatef</tt> to advance the cursor by the
width of a character and to render carriage returns when appropriate.
</p>

<h2>14.1 glutBitmapCharacter</h2>

<p>
The <tt>glutBitmapCharacter</tt> function renders a single bitmapped
character in the <i>current window</i> using the specified font.
</p>

<p><b>Usage</b></p>

<p><tt>void glutBitmapCharacter ( void *font,
int character );</tt> </p>

<p><tt>font
</tt>The bitmapped font to use in rendering
the character <br/>
 <tt>character </tt>The ASCII
code of the character to be rendered </p>

<p><b>Description</b></p>

<p>
The <tt>glutBitmapCharacter</tt> function renders the given character in the specified bitmap font.
<i>Freeglut</i> automatically sets the necessary
pixel unpack storage modes and restores the existing modes when it has finished.
Before the first call to <tt>glutBitMapCharacter</tt> the application
program should call <tt>glRasterPos*</tt> to set the  position of the character
in the window. The <tt>glutBitmapCharacter</tt>  function advances
the cursor position as part of its call to <tt>glBitmap</tt>  and so the
application does not need to call <tt>glRasterPos*</tt> again  for successive
characters on the same line. </p>

<p><b>Changes From GLUT</b></p>

<p>Nonexistent characters are rendered as
asterisks. The rendering position in <i>freeglut</i> is apparently off
from GLUT's position by a few pixels vertically and one or two pixels horizontally.
</p>

<h2>14.2 glutBitmapString</h2>

<p>
The <tt>glutBitmapString</tt> function renders a string of bitmapped
characters in the <i>current window</i> using the specified font.
</p>

<p><b>Usage</b></p>

<p><tt>void glutBitmapString ( void *font,
char *string );</tt> </p>

<p><tt>font
</tt>The bitmapped font to use in rendering
the character string <br/>
 <tt>string </tt>String
of characters to be rendered </p>

<p><b>Description</b></p>

<p>
The <tt>glutBitmapString</tt> function renders the given character
string in the specified bitmap font.
<i>Freeglut</i> automatically sets the necessary
pixel unpack storage modes and restores the existing modes when it has finished.
Before calling <tt>glutBitMapString</tt> the application program should
call <tt>glRasterPos*</tt> to set the position of the string in the window.
The <tt>glutBitmapString</tt> function handles carriage returns.
Nonexistent characters are rendered as asterisks. </p>

<p><b>Changes From GLUT</b></p>

<p>GLUT does not include this function.
 </p>

<h2>14.3 glutBitmapWidth</h2>

<p>
The <tt>glutBitmapWidth</tt> function returns the width in pixels of
a single bitmapped character in the specified font.
</p>

<p><b>Usage</b></p>

<p><tt>int glutBitmapWidth ( void *font,
int character );</tt> </p>

<p><tt>font
</tt>The bitmapped font to use in calculating
the character width <br/>
 <tt>character </tt>The ASCII
code of the character </p>

<p><b>Description</b></p>

<p>The <tt>glutBitmapWidth</tt>
function returns the width of the given character in the specified bitmap
font. Because the font is bitmapped, the width is an exact integer.
 </p>

<p><b>Changes From GLUT</b></p>

<p>Nonexistent characters return the width
of an asterisk. </p>

<h2>14.4 glutBitmapLength</h2>

<p>
The <tt>glutBitmapLength</tt> function returns the width in pixels of
a string of bitmapped characters in the specified font.
</p>

<p><b>Usage</b></p>

<p><tt>int glutBitmapLength ( void *font,
char *string );</tt> </p>

<p><tt>font </tt>The bitmapped
font to use in calculating the character width <br/>
 <tt>string </tt>String of characters
whose width is to be calculated </p>

<p><b>Description</b></p>

<p>The <tt>glutBitmapLength</tt> function returns the width in pixels of the given character string in
the specified bitmap font. Because the font is bitmapped, the width
is an exact integer: the return value is identical to the sum of the
character widths returned by a series of calls to <tt>glutBitmapWidth</tt>.
The width of nonexistent characters is counted to be the width of
an asterisk. </p>

<p> If the string contains
one or more carriage returns, <i>freeglut</i> calculates the widths in pixels
of the lines separately and returns the largest width. </p>

<p><b>Changes From GLUT</b></p>

<p>GLUT does not include this function.
 </p>

<h2>14.5 glutBitmapHeight</h2>

<p>
The <tt>glutBitmapHeight</tt> function returns the height in pixels of
the specified font.
</p>

<p><b>Usage</b></p>

<p><tt>int glutBitmapHeight ( void *font
);</tt> </p>

<p><tt>font
</tt>The bitmapped font to use in calculating
the character height </p>

<p><b>Description</b></p>

<p>The <tt>glutBitmapHeight</tt> function returns the height of a character in the specified bitmap font.
Because the font is bitmapped, the height is an exact integer. The fonts
are designed such that all characters have (nominally) the same height.
                                 </p>

<p><b>Changes From GLUT</b></p>

<p>GLUT does not include this function.
 </p>

<h2>14.6 glutStrokeCharacter</h2>

<p>
The <tt>glutStrokeCharacter</tt> function renders a single stroke character
in the <i>current window</i> using the specified font.
</p>

<p><b>Usage</b></p>

<p>
<tt>void glutStrokeCharacter ( void *font, int character );</tt>
</p>

<p><tt>font</tt>The stroke font to use in rendering the character <br/>
   <tt>character</tt>The ASCII code of the character to be rendered
</p>

<p><b>Description</b></p>

<p>
The <tt>glutStrokeCharacter</tt> function renders the given character in the specified stroke font.
Before the first call to <tt>glutStrokeCharacter</tt> the application program
should call the OpenGL transformation (positioning and scaling) functions
to set the position of the character in the window. The <tt>glutStrokeCharacter</tt>
function advances the cursor position by a call to <tt>glTranslatef</tt>
and so the application does not need to call the OpenGL positioning functions
again for successive characters on the same line.
</p>

<p><b>Changes From GLUT</b></p>

<p>Nonexistent characters are rendered as
asterisks. </p>

<h2>14.7 glutStrokeString</h2>

<p>
The <tt>glutStrokeString</tt> function renders a string of characters
in the <i>current window</i> using the specified stroke font.
</p>

<p><b>Usage</b></p>

<p><tt>void glutStrokeString ( void *font,
char *string );</tt> </p>

<p><tt>font
</tt>The stroke font to use in rendering
the character string <br/>
 <tt>string </tt>String
of characters to be rendered </p>

<p><b>Description</b></p>

<p>
The <tt>glutStrokeString</tt> function renders the given character string in the specified stroke font.
Before calling <tt>glutStrokeString</tt> the application program should
call the OpenGL transformation (positioning and scaling) functions to set
the position of the string in the window. The <tt>glutStrokeString</tt>
function handles carriage returns. Nonexistent characters are rendered
as asterisks. </p>

<p><b>Changes From GLUT</b></p>

<p>GLUT does not include this function.
 </p>

<h2>14.8 glutStrokeWidth</h2>

<p>
The <tt>glutStrokeWidth</tt> function returns the width in pixels of
a single character in the specified stroke font.
</p>

<p><b>Usage</b> </p>

<p><tt>int glutStrokeWidth ( void *font,
int character );</tt> </p>

<p><tt>font
</tt>The stroke font to use in calculating
the character width <br/>
 <tt>character </tt>The ASCII
code of the character </p>

<p><b>Description</b></p>

<p>
The <tt>glutStrokeWidth</tt>
function returns the width of the given character in the specified stroke
font. Because the font is a stroke font, the width is actually a floating-point
number; the function rounds it to the nearest integer for the return value.
 </p>

<p><b>Changes From GLUT</b></p>

<p>Nonexistent characters return the width
of an asterisk. </p>

<h2>14.9 glutStrokeLength</h2>

<p>
The <tt>glutStrokeLength</tt> function returns the width in pixels of
a string of characters in the specified stroke font.
</p>

<p><b>Usage</b></p>

<p><tt>int glutStrokeLength ( void *font,
char *string );</tt> </p>

<p><tt>font </tt>The stroke
font to use in calculating the character width <br/>
 <tt>string </tt>String of characters
whose width is to be calculated </p>

<p><b>Description</b></p>

<p>
The <tt>glutStrokeLength</tt> function returns the width in pixels of the given character string in
the specified stroke font. Because the font is a stroke font, the width
of an individual character is a floating-point number. <i>Freeglut</i>
  adds the floating-point widths and rounds the funal result to return the
integer value. Thus the return value may differ from the sum of the
character widths returned by a series of calls to <tt>glutStrokeWidth</tt>.
The width of nonexistent characters is counted to be the width
of an asterisk. </p>

<p>If the string contains
one or more carriage returns, <i>freeglut</i> calculates the widths in pixels
of the lines separately and returns the largest width. </p>

<p><b>Changes From GLUT</b></p>

<p>GLUT does not include this function.
 </p>

<h2>14.10 glutStrokeHeight</h2>

<p>
The <tt>glutStrokeHeight</tt> function returns the height in pixels of
the specified font.
</p>

<p><b>Usage</b></p>

<p><tt>GLfloat glutStrokeHeight ( void *font
);</tt> </p>

<p><tt>font
</tt>The stroke font to use in calculating
the character height </p>

<p><b>Description</b></p>

<p>
The <tt>glutStrokeHeight</tt> function returns the height of a character in the specified stroke font.
The application programmer should note that, unlike the other <i>freeglut</i>
  font functions, this one returns a floating-point number.  The fonts
are designed such that all characters have (nominally) the same height. </p>

<p><b>Changes From GLUT</b></p>

<p>GLUT does not include this function.
 </p>

<h1>15. <a name="GeometricObject"></a>Geometric Object Rendering Functions</h1>

<p>
<i>Freeglut</i> includes eighteen routines for generating easily-recognizable
 3-d geometric objects.  These routines are effectively the same ones
 that are included in the GLUT library, and reflect the functionality available
 in the <i>aux</i> toolkit described in the <i>OpenGL Programmer's Guide</i>
  . They are included to allow programmers to create with a single
line of code a three-dimensional object which can be used to test a variety
of OpenGL functionality.  None of the routines generates a display list
for the object which it draws.  The functions generate normals appropriate
for lighting but, except for the teapon functions, do not generate texture
coordinates. Do note that depth testing (GL_LESS) should be enabled for
the correct drawing of the nonconvex objects, i.e., the glutTorus,
glutSierpinskiSponge and glutTeapot.
</p>

<h2>15.1  glutWireSphere, glutSolidSphere</h2>

<p>
The <tt>glutWireSphere</tt> and <tt>glutSolidSphere</tt> functions
draw a wireframe and solid sphere respectively.
</p>

<p><b>Definition</b></p>

<p><tt>
void glutWireSphere (GLdouble dRadius, GLint slices, GLint stacks);<br>
void glutSolidSphere(GLdouble dRadius, GLint slices, GLint stacks);</tt> </p>

<p><b>Arguments</b></p>
<p><tt>dRadius&nbsp;&nbsp;</tt>The desired radius of the sphere </p>

<p><tt>slices&nbsp;&nbsp;&nbsp;</tt>The desired number of slices (divisions
in the longitudinal direction) in the sphere </p>

<p><tt>stacks&nbsp;&nbsp;&nbsp;</tt>The desired number of stacks (divisions
in the latitudinal direction) in the sphere.  The number of points in
this direction, including the north and south poles, is <tt>stacks+1</tt></p>

<p><b>Description</b></p>

<p>The <tt>glutWireSphere</tt> and <tt>
  glutSolidSphere</tt> functions render a sphere centered at the origin
of the modeling coordinate system. The north and south poles of the
sphere are on the positive and negative Z-axes respectively and the prime
meridian crosses the positive X-axis. </p>

<p><b>Changes From GLUT</b></p>

<p>None that we know of. </p>

<h2>15.2 glutWireTorus, glutSolidTorus</h2>


<p>
The <tt>glutWireTorus</tt> and <tt>glutSolidTorus</tt> functions draw
a wireframe and solid torus (donut shape) respectively.
</p>

<p><b>Definition</b></p>

<p><tt>
void glutWireTorus (GLdouble dInnerRadius, GLdouble dOuterRadius, GLint
nSides, GLint nRings);<br>
void glutSolidTorus(GLdouble dInnerRadius, GLdouble dOuterRadius, GLint
nSides, GLint nRings);</tt> </p>

<p><b>Arguments</b></p>
<p><tt>dInnerRadius&nbsp;&nbsp;</tt>The desired inner radius of the torus,
from the origin to the circle defining the centers of the outer circles </p>

<p><tt>dOuterRadius&nbsp;&nbsp;</tt>The desired outer radius of the torus,
from the center of the outer circle to the actual surface of the torus </p>

<p><tt>nSides&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</tt>The desired number of segments in a
single outer circle of the torus </p>

<p><tt>nRings&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</tt>The desired number of outer circles
around the origin of the torus </p>

<p><b>Description</b></p>

<p>The <tt>glutWireTorus</tt> and <tt>
  glutSolidTorus</tt> functions render a torus centered at the origin of
the modeling coordinate system. The torus is circularly symmetric about
the Z-axis and starts at the positive X-axis. </p>

<p><b>Changes From GLUT</b></p>

<p>None that we know of. </p>

<h2>15.3 glutWireCylinder, glutSolidCylinder</h2>

<p>
The <tt>glutWireCylinder</tt> and <tt>glutSolidCylinder</tt> functions draw
a wireframe and solid cone respectively.
</p>

<p><b>Definition</b></p>

<p><tt>
void glutWireCylinder (GLdouble base, GLdouble height, GLint slices, GLint
stacks);<br>
void glutSolidCylinder(GLdouble base, GLdouble height, GLint slices, GLint
stacks);</tt> </p>

<p><b>Arguments</b></p>
<p><tt>radius&nbsp;&nbsp;</tt>The desired radius of the cylinder </p>

<p><tt>height&nbsp;&nbsp;</tt>The desired height of the cylinder </p>

<p><tt>slices&nbsp;&nbsp;</tt>The desired number of slices around
the cylinder </p>

<p><tt>stacks&nbsp;&nbsp;</tt>The desired number of segments between
the base and the top of the cylinder (the number of points, including
the tip, is <tt>stacks + 1</tt>) </p>

<h2>15.4 glutWireCone, glutSolidCone</h2>

<p>
The <tt>glutWireCone</tt> and <tt>glutSolidCone</tt> functions draw
a wireframe and solid cone respectively.
</p>

<p><b>Definition</b></p>

<p><tt>
void glutWireCone (GLdouble base, GLdouble height, GLint slices, GLint
stacks);<br>
void glutSolidCone(GLdouble base, GLdouble height, GLint slices, GLint
stacks);</tt> </p>

<p><b>Arguments</b></p>
<p><tt>base&nbsp;&nbsp;&nbsp;&nbsp;</tt>The desired radius of the base of the cone </p>

<p><tt>height&nbsp;&nbsp;</tt>The desired height of the cone </p>

<p><tt>slices&nbsp;&nbsp;</tt>The desired number of slices around
the base of the cone </p>

<p><tt>stacks&nbsp;&nbsp;</tt>The desired number of segments between
the base and the tip of the cone (the number of points, including the tip,
is <tt>stacks + 1</tt>) </p>

<p><b>Description</b></p>

<p>The <tt>glutWireCone</tt> and <tt>
  glutSolidCone</tt> functions render a right circular cone with a base
centered at the origin and in the X-Y plane and its tip on the positive Z-axis.
The wire cone is rendered with triangular elements. </p>

<p><b>Changes From GLUT</b></p>

<p>None that we know of. </p>

<h2>15.5 glutWireCube, glutSolidCube</h2>

<p>
The <tt>glutWireCube</tt> and <tt>glutSolidCube</tt> functions draw a
wireframe and solid cube respectively.
</p>

<p><b>Definition</b></p>

<p><tt>
void glutWireCube (GLdouble dSize);<br>
void glutSolidCube(GLdouble dSize);</tt> </p>

<p><b>Arguments</b></p>
<p><tt>dSize&nbsp;&nbsp;</tt>The desired length of an edge of the cube</p>

<p><b>Description</b></p>

<p>The <tt>glutWireCube</tt> and <tt>
  glutSolidCube</tt> functions render a cube of the desired size, centered
at the origin. Its faces are normal to the coordinate directions. </p>

<p><b>Changes From GLUT</b></p>

<p>None that we know of. </p>

<h2>15.6 glutWireTetrahedron, glutSolidTetrahedron</h2>

<p>
The <tt>glutWireTetrahedron</tt> and <tt>glutSolidTetrahedron</tt>
functions draw a wireframe and solid tetrahedron (four-sided Platonic solid)
respectively.
</p>

<p><b>Definition</b></p>

<p><tt>
void glutWireTetrahedron (void);<br>
void glutSolidTetrahedron(void);</tt></p>

<p><b>Description</b></p>

<p>The <tt>glutWireTetrahedron</tt> and
<tt>glutSolidTetrahedron</tt> functions render a tetrahedron whose corners
are each a distance of one from the origin.  The length of each side
is 2/3 sqrt(6).  One corner is on the positive X-axis and another is
in the X-Y plane with a positive Y-coordinate. </p>

<p><b>Changes From GLUT</b></p>

<p>None that we know of. </p>

<h2>15.7 glutWireOctahedron, glutSolidOctahedron</h2>

<p>
The <tt>glutWireOctahedron</tt> and <tt>glutSolidOctahedron</tt> functions
draw a wireframe and solid octahedron (eight-sided Platonic solid) respectively.
</p>

<p><b>Definition</b></p>

<p><tt>
void glutWireOctahedron (void);<br>
void glutSolidOctahedron(void);</tt></p>

<p><b>Description</b></p>

<p>The <tt>glutWireOctahedron</tt> and
<tt>glutSolidOctahedron</tt> functions render an octahedron whose corners
are each a distance of one from the origin.  The length of each side
is sqrt(2).  The corners are on the positive and negative coordinate
axes. </p>

<p><b>Changes From GLUT</b></p>

<p>None that we know of. </p>

<h2>15.8 glutWireDodecahedron, glutSolidDodecahedron</h2>

<p>
The <tt>glutWireDodecahedron</tt> and <tt>glutSolidDodecahedron</tt>
functions draw a wireframe and solid dodecahedron (twelve-sided Platonic
solid) respectively.
</p>

<p><b>Definition</b></p>

<p><tt>
void glutWireDodecahedron (void);<br>
void glutSolidDodecahedron(void);</tt></p>

<p><b>Description</b></p>

<p>The <tt>glutWireDodecahedron</tt> and
<tt>glutSolidDodecahedron</tt> functions render a dodecahedron whose corners
are each a distance of sqrt(3) from the origin.  The length of each
side is sqrt(5)-1.  There are twenty corners; interestingly enough,
eight of them coincide with the corners of a cube with sizes of length 2.
</p>

<p><b>Changes From GLUT</b></p>

<p>None that we know of. </p>

<h2>15.9  glutWireIcosahedron, glutSolidIcosahedron</h2>

<p>
The <tt>glutWireIcosahedron</tt> and <tt>glutSolidIcosahedron</tt>
functions draw a wireframe and solid icosahedron (twenty-sided Platonic solid)
respectively.
</p>

<p><b>Definition</b></p>

<p><tt>
void glutWireIcosahedron (void);<br>
void glutSolidIcosahedron(void);</tt></p>

<p><b>Description</b></p>

<p>
The <tt>glutWireIcosahedron</tt> and
<tt>glutSolidIcosahedron</tt> functions render an icosahedron whose corners
are each a unit distance from the origin. The length of each side is
slightly greater than one.  Two of the corners lie on the positive and
negative X-axes.
</p>

<p><b>Changes From GLUT</b></p>

<p>None that we know of. </p>

<h2>15.10 glutWireRhombicDodecahedron, glutSolidRhombicDodecahedron</h2>

<p>
The <tt>glutWireRhombicDodecahedron</tt> and <tt>glutSolidRhombicDodecahedron</tt>
functions draw a wireframe and solid rhombic dodecahedron (twelve-sided
semi-regular solid) respectively.
</p>

<p><b>Definition</b></p>

<p><tt>
void glutWireRhombicDodecahedron (void);<br>
void glutSolidRhombicDodecahedron(void);</tt></p>

<p><b>Description</b></p>

<p>
The <tt>glutWireRhombicDodecahedron</tt> and <tt>glutSolidRhombicDodecahedron</tt>
functions render a rhombic
dodecahedron whose corners are at most a distance of one from the origin.
The rhombic dodecahedron has faces which are identical rhombuses (rhombi?)
but which have some vertices at which three faces meet and some vertices at
which four faces meet.  The length of each side is sqrt(3)/2.  Vertices
at which four faces meet are found at (0, 0, +/- 1) and (+/- sqrt(2)/2,
+/- sqrt(2)/2, 0).
</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT does not include these functions.
 </p>

<h2>15.11  glutWireTeapot, glutSolidTeapot</h2>

<p>
The <tt>glutWireTeapot</tt> and <tt>glutSolidTeapot</tt> functions
draw a wireframe and solid teapot respectively.
</p>

<p><b>Definition</b></p>

<p><tt>
void glutWireTeapot (GLdouble dSize);<br>
void glutSolidTeapot(GLdouble dSize);</tt></p>

<p><b>Arguments</b></p>
<p><tt>dSize&nbsp;&nbsp;</tt>The desired size of the teapot </p>

<p><b>Description</b></p>

<p>The <tt>glutWireTeapot</tt> and <tt>
  glutSolidTeapot</tt> functions render a teapot of the desired size,
  centered at the origin.  This is the famous OpenGL teapot [add
  reference]. </p>

<p><b>Bugs</b></p>
<p>OpenGL's default <tt>glFrontFace</tt> state assumes that front facing
polygons (for the purpose of face culling) have vertices that wind
counter clockwise when projected into window space. This teapot is
rendered with its front facing polygon vertices winding clockwise. For
OpenGL's default back face culling to work, you should use:</p>

<p><tt>&nbsp;&nbsp;glFrontFace(GL_CW);<br>
&nbsp;&nbsp;glutSolidTeapot(size);<br>
&nbsp;&nbsp;glFrontFace(GL_CCW);</tt></p>

<p>Both these bugs reflect issues in the original aux toolkit's teapot
rendering routines (GLUT used the same teapot rendering routine).</p>

<p><b>Changes From GLUT</b></p>

<p>None that we know of. </p>

<h1>16. <a name="GameMode"></a>Game Mode Functions</h1>

<h2>16.1 glutGameModeString</h2>

<p>
Specify the display mode that should be entered when GameMode is
entered. Default is the current display mode of the monitor on which the
GameMode screen will be opened.
</p>

<p><b>Usage</b><br>
A string is passed to this function that specifies a combination of
resolution, pixel depth (ignored on Linux) and refresh rate. Valid
formats are:
<ul>
<li>WxH:D@R</li>
<li>WxH:D</li>
<li>WxH@R</li>
<li>WxH</li>
<li>:D@R</li>
<li>:D</li>
<li>@R</li>
</ul>
Where W, H, D and R are placeholders for horizontal resolution, vertical
resolution, pixel depth and refresh rate respectively, as integers. Note
the "x", ":" and "@" characters.
</p>

<h2>16.2 glutEnterGameMode, glutLeaveGameMode</h2>

<p>
Attempt to change to the requested display mode and open the GameMode
window, or close the GameMode window and return to the original display
mode.
For multi-monitor display setups, <i>freeglut</i> can be told on which
monitor the gamemode window should be opened by providing the
<tt>-display</tt> command line option to <tt>glutInit</tt>.
</p>

<h2>16.3 glutGameModeGet</h2>

<p>
The following state variables may be queried with <tt>glutGet</tt>.
The returned value is an integer.
</p>

<p>
example:<br>
<tt>int windowLeft = glutGet(GLUT_WINDOW_X);</tt>
</p>

<ul>
<li>GLUT_GAME_MODE_ACTIVE - 1 if currently in GameMode</li>
<li>GLUT_GAME_MODE_DISPLAY_CHANGED - 1 if currently in GameMode</li>
<li>GLUT_GAME_MODE_POSSIBLE - 1 if display mode requested with
    <tt>glutGameModeString</tt> is possible</li>
</ul>

<p>
These queries return information about the current display mode if in
GameMode, or about the requested display mode <u>before</u> entering
GameMode:
</p>

<ul>
<li>GLUT_GAME_MODE_WIDTH - (requested) width of GameMode window</li>
<li>GLUT_GAME_MODE_HEIGHT - (requested) height of GameMode window</li>
<li>GLUT_GAME_MODE_PIXEL_DEPTH - (requested) pixel depth of GameMode window</li>
<li>GLUT_GAME_MODE_REFRESH_RATE - (requested) refresh rate of GameMode window</li>
</ul>

<h1>17. <a name="VideoResize"></a>Video Resize Functions</h1>

<p>These functions are not implemented in <i>freeglut</i>.</p>

<h2>17.1 glutVideoResizeGet</h2>

<h2>17.2 glutSetupVideoResizing,
glutStopVideoResizing</h2>

<h2>17.3 glutVideoResize</h2>

<h2>17.4 glutVideoPan</h2>

<h1>18. <a name="ColorMap"></a>Color Map Functions</h1>

<h2>18.1 glutSetColor, glutGetColor</h2>

<h2>18.2 glutCopyColormap</h2>

<h1><a name="MultiTouch"></a>MultiTouch Functions</h1>

MultiTouch callbacks are used to handle environments with multiple
inputs, such as a multi-touch screen, multi-touch touchpad, or
multiple mouses.<br />

<h2>glutMultiEntryFunc &larr; id, GLUT_ENTERED|GLUT_LEFT</h2>
<h2>glutMultiButtonFunc &larr; id, x, y, button, GLUT_DOWN|GLUT_UP</h2>
<h2>glutMultiMotionFunc &larr; id, x, y</h2>
<h2>glutMultiPassiveFunc &larr; id, x, y</h2>

These functions work like their non-multi variants, with an additional
'deviceid' parameter describing the current input device (mouse or
finger).<br />

Exception: in MultiButtonFunc, the order of callback parameters is
different (x,y,button,state instead of button,state,x,y).<br />

Currently, under X11, the non-multi callback variants are also called
on X11 for each event.<br />

Currently, under windows, the first (oldest) touch point also controls
the mouse cursor, which triggers the non-multi callbacks as
usual.<br />

<br />

Limitation: currently on the cursor id is provided.  It may be
desirable to get the device id as well in the following situations:

<ul>

  <li>Support a 2-fingers gesture such as pinch-to-zoom on the
  touchscreen, while the mouse pointer or touchpad is performing an
  unrelated action, not part of the touchscreen gesture (may not be
  possible on some platforms).</li>

  <li>Avoid touch id conflicts between touchscreen and
  touchpad/gamepad on Android; a possible work-around is to shift the
  device id in the touch id.</li>

</ul>

Since this extra support comes at the cost of extra complexity, we're
<a href="http://sourceforge.net/mailarchive/forum.php?thread_name=20120518071314.GA28061%40perso.beuc.net&forum_name=freeglut-developer">considering</a>
whether/how to implement it.

<h1><a name="Android"></a>Android Functions</h1>

<p>These new callbacks were added:</p>

<ul>
<li><code>glutInitContextFunc &larr; void</code> : called when the context
is initialized or re-initialized (e.g. after a pause)</li>
<li><code>glutPauseFunc &larr; void</code> : called when the application
goes on a pause (or a stop)</li>
<li><code>glutResumeFunc &larr; void</code> : called when the application
comes back from a pause (after <code>glutInitContextFunc</code>)</li>
</ul>

<p>Android support is further described at
the <a href="android.php">Android page</a>.</p>

<h1>19. <a name="Miscellaneous"></a>Miscellaneous Functions</h1>

<h2>19.1 glutIgnoreKeyRepeat, glutSetKeyRepeat</h2>

<h2>19.2 glutForceJoystickFunc</h2>

<h2>19.3 glutReportErrors</h2>

<h1>20. <a name="UsageNotes"></a>Usage Notes</h1>

<p> The following environment variables
are recognized by <i>freeglut</i>:
</p>

<ul>
  <li>DISPLAY - specifies a display name.</li>
  <li>GLUT_FPS - specifies a time interval
	(in milliseconds) for reporting framerate messages to stderr.  For example,
	if FREEGLUT_FPS is set to 5000, every 5 seconds a message will be printed
	to stderr showing the current frame rate.  The frame rate is measured by counting
	the number of times glutSwapBuffers() is called over the time interval.</li>
  <li>GLUT_ICON - specifies the icon that
	goes in the upper left-hand corner of the <i>freeglut</i><i> </i>windows </li>
</ul>

<h1>21. <a name="ImplementationNotes"></a>Implementation Notes</h1>

<h1>22. <a name="GLUT_State"></a>GLUT State</h1>

<h1>23. <a name="Freeglut.h_Header"></a>"freeglut.h" Header File</h1>

<p>
Application programmers who are porting their GLUT programs to <i>freeglut</i> may continue
to include <tt>&lt;GL/glut.h&gt;</tt> in their programs.
Programs which use the <i>freeglut</i>-specific extensions to GLUT should include
<tt>&lt;GL/freeglut.h&gt;</tt>.  One possible arrangement is as follows:
</p>

<pre>
#ifdef FREEGLUT
#include &lt;GL/freeglut_ext.h&gt;
#else
#include &lt;GL/glut.h&gt;
#endif
</pre>

<p>
It was initially planned to
define <code>FREEGLUT_VERSION_2_0</code>, <code>FREEGLUT_VERSION_2_1</code>, <code>FREEGLUT_VERSION_2_2</code>,
etc., but this was only done for <code>FREEGLUT_VERSION_2_0</code>.
This constant still exist in recent FreeGLUT releases but is
deprecated.
</p>

<p>
The <i>freeglut</i> version can be queried at runtime by calling
glutGet(GLUT_VERSION).
The result will be X*10000+Y*100+Z where X is the major version, Y is the
minor version and Z is the patch level.
</p>
<p>
This may be used as follows:
</p>

<pre>
if (glutGet(GLUT_VERSION) &lt; 20001) {
    printf("Sorry, you need freeglut version 2.0.1 or later to run this program.\n");
    exit(1);
}
</pre>

<h1>24. <a name="References"></a>References</h1>

<h1>25. <a name="Index"></a>Index</h1>

<?php generateFooter(); ?>
