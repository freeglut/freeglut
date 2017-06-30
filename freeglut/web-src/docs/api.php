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
(<i>freeglut</i> 3.0.0)<br/>
Application Programming Interface
</div>

<p></p>
<div style="font-size: 1.6em; font-weight: bold; text-align: center;">
Version 4.0
</div>

<p></p>
<div style="font-size: 1.2em; font-weight: bold; text-align: center;">
The <i>freeglut</i> Programming Consortium<br/>
January, 2013
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
            <li>glutInitErrorFunc, glutInitWarningFunc</li>
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
			<li>glutSetWindowTitle, glutSetIconTitle</li>
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
            <li>glutSetMenuFont</li>
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
			<li>glutPositionFunc</li>
			<li>glutCloseFunc</li>
			<li>glutKeyboardFunc</li>
			<li>glutSpecialFunc</li>
			<li>glutKeyboardUpFunc</li>
			<li>glutSpecialUpFunc</li>
			<li>glutMotionFunc, glutPassiveMotionFunc</li>
            <li>glutMouseFunc</li>
            <li>glutMouseWheelFunc</li>
			<li>glutEntryFunc</li>
			<li>glutJoystickFunc</li>
			<li>glutSpaceballMotionFunc</li>
			<li>glutSpaceballRotateFunc</li>
			<li>glutSpaceballButtonFunc</li>
			<li>glutButtonBoxFunc</li>
			<li>glutDialsFunc</li>
			<li>glutTabletMotionFunc</li>
			<li>glutTabletButtonFunc</li>
			<li>glutVisibilityFunc, glutWindowStatusFunc</li>
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
			<li>glutStrokeWidthf</li>
			<li>glutStrokeLength</li>
   			<li>glutStrokeLengthf</li>
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
			<li>glutWireTeapot, glutSolidTeapot, glutWireTeacup,
            glutSolidTeacup, glutWireTeaspoon, glutSolidTeaspoon</li>
            <li>glutSetVertexAttribCoord3, glutSetVertexAttribNormal,
            glutSetVertexAttribTexCoord2</li>
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
	<li><a href="#Mobile">Mobile functions</a>
		<ol>
			<li>glutInitContextFunc &larr; void</li>
			<li>glutAppStatusFunc &larr; event</li>
		</ol>
	</li>
	<li><a href="#Miscellaneous">Miscellaneous Functions</a>
		<ol>
			<li>glutSetKeyRepeat, glutIgnoreKeyRepeat</li>
			<li>glutForceJoystickFunc</li>
			<li>glutReportErrors</li>
		</ol>
	</li>
	<li><a href="#UsageNotes">Usage Notes</a>
		<ol>
			<li><a href="#ImplementationNotes">Implementation Notes</a></li>
			<li><a href="#GLUT_State">GLUT State</a></li>
			<li><a href="#freeglut.h_Header">"freeglut.h" Header File</a></li>
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
possible. In this section some of the important conventions of freeglut,
and their compatibility with GLUT, are made explicit.
</p>

<h3>3.2.1 Window placement and size</h3>

<p>
There is considerable confusion about the "right thing to do" concerning
window size and position. GLUT itself is not consistent between
Windows and UNIX/X11; since platform independence is a virtue for
<i>freeglut</i>, we decided to break with GLUT's behaviour. <br>
Under UNIX/X11, it is apparently not possible to get the window border
sizes in order to subtract them off the window's initial position until
some time after the window has been created.  Therefore we decided on
the following behavior, both under Windows and under UNIX/X11:
<ul><li>When you create a window with position (x,y) and size (w,h), the
upper left hand corner of the outside of the window (the non-client
area) is at (x,y) and the size of the drawable (client) area is (w,h).
The coordinates taken by <tt>glutInitPosition</tt> and
<tt>glutPositionWindow</tt>, as well as the coordinates provided by
<i>freeglut</i> when it calls the <tt>glutPositionFunc</tt> callback,
specify the top-left of the non-client area of the window. By default
only positive-signed coordinates are supported. If GLUT_ALLOW_NEGATIVE_WINDOW_POSITION
is enabled, then negative coordinates are supported. An exception
for <tt>glutPositionWindow</tt> exists as it's always supported negative
window coordinates.</li>
<li>When you query the size and position of the window using
<tt>glutGet</tt>, <i>freeglut</i> will return the size of the drawable
area--the (w,h) that you specified when you created the window--and the
coordinates of the upper left hand corner of the drawable (client)
area--which is <u>NOT</u> the (x,y) position of the window you specified
when you created it.</ul>
</p>

<h3>3.2.2 User-data callbacks</h3>

<p>
GLUT was created as a tool to help teach OpenGL programming. To simplify
development, callbacks were used for handling display, input, and other
events. But at the time it was developed, the purpose, or for some other 
unknown reason, the callbacks lacked any user-provided data argument.
This has caused considerable difficulties for any significantly advanced
usage of GLUT, and now <i>freeglut</i>. This has prevented any attempt to 
wrap <i>freeglut</i> in a C++ wrapper, make per-window, per-callback data
structure, and potentially made it undesirable to modern C developers who
tend to be well versed in "don't use globals". To combat these
complaints and <i>issues</i>, many callbacks (with some deprecated 
callbacks excluded) support user-data callbacks provided through additional
functions provided in <i>freeglut</i>. All callbacks that support user-data
callbacks are marked as such.
</p>

<p>
The general rule to follow is to take the <i>freeglut</i> callback function 
and append "Ucall" to the end of the function, add an additional <tt>void*</tt>
argument to the end of the argument list of both the <i>freeglut</i> function
and the callback function. This will pass the user-data to the callback when it's
invoked.
</p>

<p><b>Examples</b></p>

<p><tt>void glutPositionFunc ( void (* func)( int x, int y ) );</tt><br>
<tt>void glutPositionFuncUcall ( void (* func)( int x, int y, void* user_data ), void* user_data );</tt></p>

<p><tt>void glutKeyboardUpFunc ( void (* func)( unsigned char key, int x, int y ) );</tt><br>
<tt>void glutKeyboardUpFuncUcall ( void (* func)( unsigned char key, int x, int y, void* user_data ), void* user_data );</tt></p>

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
which has three arguments), a window status callback (also with one
argument), and a window position callback.  Unsupported callbacks are
the two Tablet callbacks.  If the user has a need for an unsupported
callback he should contact the <i>freeglut</i> development team.
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
dodecahedron. Furthermore, solid and wireframe versions of the original
teacup and teaspoon that accompanied the famous Newell teapot. As these
geometry functions are often used for teaching purposes,
<tt>glutSetOption(GLUT_GEOMETRY_VISUALIZE_NORMALS,true/false)</tt> can
now be used to visualize the normal vectors for each vertex. Lastly, to
support drawing these objects with shaders, three functions have been
added with which users can provide the addresses of the Coordinate,
Normal and Texture Coordinate vertex attribs:
<tt>glutSetVertexAttribCoord3</tt>, <tt>glutSetVertexAttribNormal</tt>,
and <tt>glutSetVertexAttribTexCoord2</tt>. Texture coordinates are only
generated for the teaset.
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
of the screen.  The size is measured in pixels. <i>freeglut</i>
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
<a href="#Conventions">freeglut's conventions</a>, the information
returned about the window coordinates does not correspond to the
coordinates used when setting window position. In addition, GLUT only
accepts positive window coordinates, and ignores all negative window
coordinates. But if GLUT_ALLOW_NEGATIVE_WINDOW_POSITION is enabled,
then negative window coordinates can be used. This is useful for
multi-montitor setups where the second monitor may be in the negative
desktop space of the primary monitor, as now the window can be placed
on the additional monitors. Furthermore, this flag also determines how 
negative coordinates and sizes are interpreted for subwindows.
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

<p>
If GLUT_ALLOW_NEGATIVE_WINDOW_POSITION is enabled, <tt>glutInitWindowPosition</tt>
will accept negative window coordinates.
</p>

<h2>4.3 glutInitDisplayMode</h2>

<h2>4.4 glutInitDisplayString</h2>
<p><b>Changes From GLUT</b></p>

<p>
glutInitDisplayString support is limited: any of the tokens recognized
by GLUT are also recognized by <i>freeglut</i>, but any statements with
comparators cannot (yet: do <a href="../help.php">help develop
this!</a>) be handled. Any spec (comparator and value) after the token
is ignored. However, many of these values can be set with glutSetOption
for now...
</p>

<h2>4.5 glutInitErrorFunc, glutInitWarningFunc</h2>
<p>
The <tt>glutInitErrorFunc</tt> and <tt>glutInitWarningFunc</tt>
functions specify callbacks that will be called upon warnings and errors
issued from within <i>freeglut</i> so that the user can deal with these.
Useful for rerouting to another output sink (e.g., logging) and also to
avoid exit(1) being called upon error. As with other glutInit*
functions, these can be set before glutInit is called, so any output
from the library can be handled by the user.
</p>

<p><b>Usage</b></p>
<p><tt>void glutInitErrorFunc&nbsp;&nbsp;&nbsp;( void (* callback)( const char *fmt, va_list ap) );</tt><br/>
<tt>void glutInitWarningFunc&nbsp;( void (* callback)( const char *fmt, va_list ap) );</tt> </p>

<p>These functions have user-data callback functions.</p>

<p><b>Description</b></p>
<p>
The users callback is passed a format string and a variable argument
list that can be passed to functions such as <tt>printf</tt>.<br />
Note that there are the preprocessor definitions
<tt>FREEGLUT_PRINT_ERRORS</tt> and <tt>FREEGLUT_PRINT_WARNINGS</tt>,
which affect <i>freeglut</i>'s warning and error behavior when no user
callback is defined. If defined at library (not client app!) compile
time--by default it is, warnings and errors are printed to
<tt>stderr</tt>. If not defined, warnings and errors are muted (not
printed to stderr), though errors still trigger deinitialization and
exit. Whether <tt>FREEGLUT_PRINT_ERRORS</tt> and
<tt>FREEGLUT_PRINT_WARNINGS</tt> is defined does not affect whether the
client callback is called, it only affects whether warnings and errors
are printed to <tt>stderr</tt> when no callback is defined.
</p>

<p><b>Changes From GLUT</b></p>
<p>GLUT does not provide these functions.</p>

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
memory) after GLUT had closed down.  <i>freeglut</i> allows the application
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
calling function.  <i>freeglut</i> allows the programmer
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
<tt>GLUT_ACTION_ON_WINDOW_CLOSE</tt> option has been set to
<tt>GLUT_ACTION_GLUTMAINLOOP_RETURNS</tt> or
<tt>GLUT_ACTION_CONTINUE_EXECUTION</tt>, control will return to the
function which called <tt>glutMainLoop</tt>; otherwise the application
will exit.
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

<p>
The <tt>glutCreateSubwindow</tt> function creates a subwindow of an existing window.
</p>

<p><b>Usage</b></p>

<p>
<tt>int glutCreateSubwindow(int window, int x, int y, int width, int height);</tt>
</p>

<p><b>Description</b></p>

<p>
Creates a subwindow of <i>window</i> that is at location <i>x</i> and <i>y</i>
relative to the window's upper-left corner, and is of the specified <i>width</i> and <i>height</i>. The newly created
window ID is returned by <tt>glutCreateSubwindow</tt>. By default, the position coordinates will only allow windows within the bounds of the parent.
Negative coordinates be treated as coordinates from the opposite edge for a given axis. In addition, the width of the window will be taken into account.
For example, if the parent window is 100 pixels wide, and the <i>x</i> is 10, and <i>width</i> is 20, the subwindow will be located at <tt>x = 10</tt>.
If <i>x</i> is -10, then the subwindow will be located at 70 <tt>(parent - abs(pos) - dim)</tt>. If the <i>width</i> or <i>height</i> are negative, then the dimension is taken as a
subtraction of the parent dimension. For example, if the parent window is 100 pixels wide, and the <i>x</i> is 10, and <i>width</i> is 20, the 
subwindow will have a size of 20. If <i>width</i> is -20, then the subwindow will have a width of 70 <tt>(parent - pos - abs(dim))</tt>.
</p>

<p>
If GLUT_ALLOW_NEGATIVE_WINDOW_POSITION is enabled, the window behavior differs. Negative window coordinates are now accepted and may result in windows outside
of the viewing area, depending on the platform of operation. Negative <i>width</i> and <i>height</i> are still used as a subtraction of the parent window dimension,
but they do not take <i>x</i> or <i>y</i> into account. For example, if the parent window is 100 pixels wide, and the <i>x</i> is 10, and <i>width</i> is 20, the 
subwindow will be located at <tt>x = 10</tt>. If <i>x</i> is -10, then the subwindow will be located at <tt>x = -10</tt>. If the parent window is 100 pixels wide, 
and the <i>x</i> is 10, and <i>width</i> is 20, the subwindow will have a size of 20. If <i>width</i> is -20, then the subwindow will have a width of 80 <tt>(parent - abs(dim))</tt>.
</p>

<p><b>Changes From GLUT</b></p>

<p>
GLUT does not support negative <i>x</i> or <i>y</i>. Nor does it have GLUT_ALLOW_NEGATIVE_WINDOW_POSITION 
which changes the the functionality of <tt>glutCreateSubwindow</tt>.
</p>

<h2>6.3 glutDestroyWindow</h2>

<h2>6.4 glutSetWindow, glutGetWindow</h2>

<h2>6.5 glutSetWindowTitle, glutSetIconTitle</h2>

<p>
The <tt>glutSetWindowTitle</tt>, <tt>glutSetIconTitle</tt> set the
window title for when the window is in a visible state and when it is in
an iconified state respectively.
</p>

<p><b>Usage</b></p>

<p><tt>glutSetWindowTitle(const char* title);</tt><br>
<tt>glutSetIconTitle(const char* title);</tt>
</p>

<p><b>Description</b></p>

<p>
Normally a window system displays a title for every top-level window in
the system. The initial title is set when you call glutCreateWindow().
By means of the <tt>glutSetWindowTitle</tt> function you can set the
titles for your top-level <i>freeglut</i> windows. If you just want one
title for the window over the window's entire life, you should set it
when you open the window with glutCreateWindow().<br>
<tt>glutSetIconTitle</tt> sets the title to be displayed for the window
when it is in iconified (minimized) state.
</p>

<p><b>Changes From GLUT</b></p>

<p><tt>glutSetIconTitle</tt> does nothing in GLUT on Windows, but is
emulated on Windows by <i>freeglut</i>.</p>

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
<i>freeglut</i> does not allow overlays, although it does "answer the mail"
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

<p>Has user-data callback function.</p>

<h2>10.2 glutDestroyMenu</h2>

<h2>10.3 glutGetMenu, glutSetMenu</h2>

<h2>10.4 glutAddMenuEntry</h2>

<h2>10.5 glutAddSubMenu</h2>

<h2>10.6 glutChangeToMenuEntry</h2>

<h2>10.7 glutChangeToSubMenu</h2>

<h2>10.8 glutSetMenuFont</h2>
<p>
<tt>glutSetMenuFont</tt> sets the (bitmap) font to be used for drawing
the specified menu.
</p>

<p><b>Usage</b></p>

<p><tt>void glutSetMenuFont( int menuID, void* fontID );</tt>
  </p>

<p><b>Description</b></p>

<p>
Only bitmap fonts (<tt>GLUT_BITMAP_xxx</tt>, see <a
href="#FontRendering">here</a> for a list) can be used as menu fonts.  A
warning is issued and the request is ignored if the supplied font is a
stroke font, or an unknown font.
</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT does not provide this function.</p>

<h2>10.9 glutRemoveMenuItem</h2>

<h2>10.10 glutAttachMenu, glutDetachMenu</h2>

<h2>10.11 glutMenuDestroyFunc</h2>

<p>Has user-data callback function.</p>

<h1>11. <a name="GlobalCallback"></a>Global Callback Registration Functions</h1>

<h2>11.1 glutTimerFunc</h2>

<p>Has user-data callback function.</p>

<h2>11.2 glutIdleFunc</h2>

<p>
The <tt>glutIdleFunc</tt> function sets the global idle callback. <i>
freeglut</i>  calls the idle callback when there are no inputs from the user.
</p>

<p><b>Usage</b></p>

<p><tt>void glutIdleFunc ( void (*func ) ( void ) );</tt> </p>

<p><tt>func</tt> The new global idle callback function</p>

<p>Has user-data callback function.</p>

<p><b>Description</b></p>

<p>
The <tt>glutIdleFunc</tt> function
specifies the function that <i>freeglut</i> will call to perform background
processing tasks such as continuous animation when window system events are
not being received.  If enabled, this function is called continuously
from <i>freeglut</i> while no events are received.  The callback function
has no parameters and returns no value.  <i>freeglut</i> does not change
the <i>current window</i> or the <i>current menu</i> before invoking the idle
callback; programs with multiple windows or menus must explicitly set the
<i>current window</i> and <i>current menu</i>
 and not rely on its current setting. <br/>
The amount of computation and rendering done in an idle
callback should be minimized to avoid affecting the program's interactive
response.  In general, no more than a single frame of rendering should
be done in a single invocation of an idle callback. Note that no actual
drawing to the framebuffer should be done from the idle callback, this
is not supported. While it might work on some platforms, it will not on
others or might stop working in the future. Drawing should be done in
the <tt>glutDisplayFunc</tt> callback.<br/>
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

<p>Has user-data callback function.</p>

<h2>11.4 glutMenuStateFunc</h2>

<h1>12. <a name="WindowCallback"></a>Window-Specific Callback Registration Functions</h1>

<h2>12.1 glutDisplayFunc</h2>

<p>Has user-data callback function.</p>

<h2>12.2 glutOverlayDisplayFunc</h2>

<p>Has user-data callback function.</p>

<h2>12.3 glutReshapeFunc</h2>

<p>Has user-data callback function.</p>

<h2>12.4 glutPositionFunc</h2>

<p>
The <tt>glutPositionFunc</tt> function sets the window's position
callback. <i>freeglut</i> calls the position callback when the window is
repositioned/moved programatically or by the user.
</p>

<p><b>Usage</b></p>

<p><tt>void glutPositionFunc ( void
(* func)( int x, int y) );</tt></p>

<p>Has user-data callback function.</p>

<p><b>Description</b></p>

<p>When <i>freeglut</i> calls this callback, it provides the new
position on the screen of the top-left of the <u>non-client area</u>,
that is, the same coordinates used by <tt>glutInitPosition</tt> and
<tt>glutPositionWindow</tt>. To get the position on the screen of the
top-left of the client area, use <tt>glutGet(GLUT_WINDOW_X)</tt> and
<tt>glutGet(GLUT_WINDOW_Y)</tt>. See <a href="#Conventions">freeglut's
conventions</a> for more information.</p>

<p><b>Changes From GLUT</b></p>

<p>This function is not implemented in GLUT.</p>

<h2>12.5 glutCloseFunc</h2>

<p>
The <tt>glutCloseFunc</tt> function sets the window's close
callback. <i>freeglut</i> calls the close callback when the window is
about to be destroyed.
</p>

<p><b>Usage</b></p>

<p><tt>void glutCloseFunc ( void (*func) ( void ) );</tt> </p>

<p><tt>func</tt> The window's new closure callback function <br/>
</p>

<p>Has user-data callback function.</p>

<p><b>Description</b></p>

<p>
The <tt>glutCloseFunc</tt> function specifies the function
that <i>freeglut</i> will call to notify the application that a window
is about to be closed, either because it is requested using
<tt>glutDestroyWindow</tt>, because the user clicked on the "x" in the
window header (for top-level windows only), or due to a pending closure
of a subwindow's parent window. In the first case, the closure callback
is not invoked from the <tt>glutDestroyWindow</tt> call, but at a
later time point.<br />
<i>freeglut</i> sets the <i>current window</i> to the window
which is about to be closed when the callback is invoked. The window can
thus be retrieved in the callback using <tt>glutGetWindow</tt>.<br />
Users looking to prevent <i>freeglut</i> from exiting when a window is
closed, should look into using glutSetOption to set
<tt>GLUT_ACTION_ON_WINDOW_CLOSE</tt>. Some settings will prevent the
application from exiting when a window is closed.<br />
Please note that <tt>glutWMCloseFunc</tt>, a deprecated function, is an
alias to <tt>glutCloseFunc</tt>.

<p><b>Changes From GLUT</b></p>

<p>This function is not implemented in GLUT.</p>

<h2>12.6 glutKeyboardFunc</h2>

<p>Has user-data callback function.</p>

<h2>12.7 glutSpecialFunc</h2>

<p>
The <tt>glutSpecialFunc</tt> function sets the window's special key press
callback. <i>freeglut</i> calls the special key press callback when the
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

<p>Has user-data callback function.</p>

<p><b>Description</b></p>

<p>
The <tt>glutSpecialFunc</tt> function specifies the function
that <i>freeglut</i> will call when the user
presses a special key on the keyboard.  The callback function has one
argument: the name of the function to be invoked ("called back") at
the time at which the special key is pressed.  The function returns no
value.  <i>freeglut</i> sets the <i>current window</i> to the window
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

<h2>12.8 glutKeyboardUpFunc</h2>

<p>
The <tt>glutKeyboardUpFunc</tt> function sets the window's key release
callback. <i>freeglut</i> calls the key release callback when the user releases
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

<p>Has user-data callback function.</p>

<p><b>Description</b></p>

<p>
The <tt>glutKeyboardUpFunc</tt>
function specifies the function that <i>freeglut</i> will call when the
user releases a key from the keyboard.  The callback function has one
argument:  the name of the function to be invoked ("called back") at
the time at which the key is released.  The function returns no value.
<i>Freeglut</i> sets the <i>current window</i> to the window which is
active when the callback is invoked. <br/> While <i>freeglut</i> checks
for upper or lower case letters, it does not do so for non-alphabetical
characters.  Nor does it account for the Caps-Lock key being on.
The operating system may send some unexpected characters to
<i>freeglut</i>, such as "8" when the user is pressing the Shift
key.  <i>freeglut</i> also invokes the callback when the user
releases the Control, Alt, or Shift keys, among others.  Releasing
the Delete key causes this function to be invoked with a value of
127 for <tt>key</tt>. <br/> Calling <tt>glutKeyboardUpFunc</tt> with
a NULL argument disables the call to the window's key release callback.
</p>

<p><b>Changes From GLUT</b></p>

<p>This function is not implemented in GLUT
versions before Version 4.  It has been designed to be as close to GLUT
as possible.  Users who find differences should contact the
<i>freeglut</i> Programming Consortium to have them fixed. </p>

<h2>12.9 glutSpecialUpFunc</h2>

<p>
The <tt>glutSpecialUpFunc</tt> function sets the window's special key
release callback. <i>freeglut</i> calls the special key release callback
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

<p>Has user-data callback function.</p>

<p><b>Description</b></p>

<p>
The <tt>glutSpecialUpFunc</tt>function specifies the function that <i>freeglut</i> will call when the
user releases a special key from the keyboard.  The callback function
has one argument: the name of the function to be invoked ("called back")
at the time at which the special key is released.  The function returns
no value. <i>freeglut</i> sets the <i>current window</i> to the window
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

<h2>12.10 glutMotionFunc, glutPassiveMotionFunc</h2>

<p>Both functions have user-data callback functions.</p>

<h2>12.11 glutMouseFunc</h2>

<p>Has user-data callback function.</p>

<h2>12.12 glutMouseWheelFunc</h2>

<p>
The <tt>glutMouseWheelFunc</tt> function sets the window's mouse wheel
callback. <i>freeglut</i> calls the mouse wheel callback when the user
spins the mouse wheel.
</p>

<p><b>Usage</b></p>

<p><tt>void glutMouseWheelFunc ( void( *callback )( int wheel, int
direction, int x, int y ));</tt></p>

<p>Has user-data callback function.</p>

<p><b>Description</b></p>

<p>If the mouse wheel is spun over your (sub)window, <i>freeglut</i>
will report this via the MouseWheel callback. <tt>wheel</tt> is the wheel
number, <tt>direction</tt> is +/- 1, and <tt>x</tt> and <tt>y</tt> are
the mouse coordinates.<br><br>
If you do not register a wheel callback, wheel events will be reported
as mouse buttons.
</p>

<p><b>Changes From GLUT</b></p>

<p>This function is not implemented in GLUT.</p>

<h2>12.13 glutEntryFunc</h2>

<p>Has user-data callback function.</p>

<h2>12.14 glutJoystickFunc</h2>

<p>Has user-data callback function.</p>

<h2>12.15 glutSpaceballMotionFunc</h2>

<p>
The <tt>glutSpaceballMotionFunc</tt> function is implemented in
<i>freeglut</i> on X11 and Windows only.  On other platforms, function stubs are
provided so that GLUT-based programs can compile and link against
<i>freeglut</i> without modification.
</p>
<p>The <tt>glutSpaceballMotionFunc</tt> function sets the window's Spaceball motion callback. <i>freeglut</i> invokes this callback when the user push/pull Spaceball cap in <i>x</i>, <i>y</i>, and <i>z</i> directions.
</p>

<p><b>Usage</b></p>

<p><tt>void glutSpaceballMotionFunc ( void (* callback)( int x, int y, int z ) );</tt></p>

<p>Has user-data callback function.</p>

<p><b>Description</b></p>

<p>The <i>x</i>, <i>y</i>, and <i>z</i> arguments indicate the amount of translation in integer along x, y, and z axis respectively.</p>
<p>The x, y, and z axes form a common OpenGL right-handed coordinate system. A positive value of <i>x</i>, <i>y</i>, or <i>z</i> indicates movement along the positive direction of the respective axis, while the negative one denotes movement along negative direction.</p>

<h2>12.16 glutSpaceballRotateFunc</h2>

<p>
The <tt>glutSpaceballRotateFunc</tt> function is implemented in
<i>freeglut</i> on X11 and Windows only. On other platforms, function stubs are
provided so that GLUT-based programs can compile and link against
<i>freeglut</i> without modification.
</p>
<p>The <tt>glutSpaceballRotateFunc</tt> function sets the window's Spaceball rotation callback. <i>freeglut</i> invokes this callback when the user rotates/twists Spaceball cap.
</p>

<p><b>Usage</b></p>

<p><tt>void glutSpaceballRotateFunc ( void (* callback)( int rx, int ry, int rz ) );</tt></p>

<p>Has user-data callback function.</p>

<p><b>Description</b></p>

<p>The <i>rx</i>, <i>ry</i>, and <i>rz</i> arguments indicate the amount of rotation in integer with respect to x, y, and z axis respectively.</p>
<p>The x, y, and z axes form a common OpenGL right-handed coordinate system. Positive value of <i>rx</i>, <i>ry</i>, or <i>rz</i> indicates counter-clock wise rotation along the respective axis, while negative one denotes clock wise rotation.</p>

<h2>12.17 glutSpaceballButtonFunc</h2>

<p>
The <tt>glutSpaceballButtonFunc</tt> function is implemented in
<i>freeglut</i> on X11 and Windows only.  On other platforms, function stubs are
provided so that GLUT-based programs can compile and link against
<i>freeglut</i> without modification.
</p>
<p>
The <tt>glutSpaceballButtonFunc</tt> function sets the window's Spaceball button callback. <i>freeglut</i> invokes this callback when the user presses/releases one of the Spaceball buttons.
</p>

<p><b>Usage</b></p>

<p><tt>void glutSpaceballButtonFunc ( void
(* callback)( int button, int updown )</tt><tt> );</tt></p>

<p>Has user-data callback function.</p>

<p><b>Description</b></p>

<p>The <i>button</i> argument may take one of the following defined
constant values:</p>
<ul>
	<li></tt>GLUT_SPACEBALL_BUTTON_A</tt> (0x00000001)</li>
    <li></tt>GLUT_SPACEBALL_BUTTON_B</tt> (0x00000002)</li>
    <li></tt>GLUT_SPACEBALL_BUTTON_C</tt> (0x00000004)</li>
    <li></tt>GLUT_SPACEBALL_BUTTON_D</tt> (0x00000008)</li>
    <li></tt>GLUT_SPACEBALL_BUTTON_E</tt> (0x00000010)</li>
</ul>
<p>The <i>updown</i> argument may take one of the two defined constant
values:</p>
<ul>
	<li>
		GLUT_DOWN, GLUT_UP indicating if button is pressed or released.
	</li>
</ul>

<p><b>Changes From GLUT</b></p>

<p>
The <tt>GLUT_SPACEBALL_BUTTON_</tt> defines for the button argument of
the callback are not provided by GLUT, but the numerical values returned
are the same.
</p>

<h2>12.18 glutButtonBoxFunc</h2>

<p>
The <tt>glutDialsFunc</tt> function sets the global dials&buttons box callback. freeglut calls the callback when there is input from the box buttons.
</p>

<p><b>Usage</b></p>

<p>
<tt>void glutButtonBoxFunc ( void (* callback)( int button, int updown ) );</tt>
</p>

<p>Has user-data callback function.</p>

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

<h2>12.19 glutDialsFunc</h2>

<p>
The <tt>glutDialsFunc</tt> function sets the global dials&buttons box callback. freeglut calls the callback when there is input from the box dials.
</p>

<p><b>Usage</b></p>

<p><tt>void glutDialsFunc ( void (* callback)(
int dial, int value )</tt><tt> );</tt></p>

<p>Has user-data callback function.</p>

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

<h2>12.20 glutTabletMotionFunc</h2>

<p>
The <tt>glutTabletMotionFunc</tt> function is not implemented in <i>
freeglut</i>, although the library does "answer the mail" to the extent
that a call to the function will not produce an error..
</p>

<p><b>Usage</b></p>

<p>
<tt>void glutTabletMotionFunc ( void (* callback)( int x, int y ) );</tt>
</p>

<p>Has user-data callback function.</p>

<p><b>Description</b></p>

<p>The <tt>glutTabletMotionFunc</tt> function
is not implemented in <i>freeglut</i>. </p>

<p><b>Changes From GLUT</b></p>

<p>GLUT implements this function. </p>

<h2>12.21 glutTabletButtonFunc</h2>

<p>
The <tt>glutTabletButtonFunc</tt> function is not implemented in <i>
freeglut</i>, although the library does "answer the mail" to the extent
that a call to the function will not produce an error..
</p>

<p><b>Usage</b></p>

<p><tt>void glutTabletButtonFunc ( void
(* callback)( int button, int updown, int x, int y )</tt><tt> );</tt></p>

<p>Has user-data callback function.</p>

<p><b>Description</b></p>

<p>
The <tt>glutTabletButtonFunc</tt> function
is not implemented in <i>freeglut</i>.
</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT implements this function. </p>

<h2>12.22 glutVisibilityFunc, glutWindowStatusFunc</h2>

<p>
The <tt>glutVisibilityFunc</tt> and the <tt>glutWindowStatusFunc</tt>
functions set the window's visibility and windowStatus callbacks for the
current window. Setting one overwrites the other. <i>freeglut</i> calls
these callbacks when the visibility status of a window changes.
</p>

<p><b>Usage</b></p>

<p><tt>void glutVisibilityFunc ( void( *callback )( int state ));</tt>
<br><tt>void glutWindowStatusFunc ( void( *callback )( int state ));</tt>
</p>

<p>Both functions have user-data callback functions.</p>

<p><b>Description</b></p>

<p>
<tt>glutVisibilityFunc</tt> is deprecated and superseded by the more
informative <tt>glutWindowStatusFunc</tt>.<br>
For <tt>glutWindowStatusFunc</tt>, the state callback parameter is one
of GLUT_HIDDEN, GLUT_FULLY_RETAINED, GLUT_PARTIALLY_RETAINED, or
GLUT_FULLY_COVERED depending on the current window status of the window.
GLUT_HIDDEN means that the window is not shown (often meaning that the
window is iconified). GLUT_FULLY_RETAINED means that the window is fully
retained (no pixels belonging to the window are covered by other
windows). GLUT_PARTIALLY_RETAINED means that the window is partially
retained (some but not all pixels belonging to the window are covered by
other windows). GLUT_FULLY_COVERED means the window is shown but no part
of the window is visible, i.e., until the window's status changes, all
further rendering to the window is discarded.<br>
GLUT considers a window visible if any pixel of the window is visible or
any pixel of any descendant window is visible on the screen.<br>
GLUT applications are encouraged to disable rendering and/or animation
when windows have a status of either GLUT_HIDDEN or
GLUT_FULLY_COVERED.<br>
If the window status callback for a window is disabled and later
re-enabled, the window status of the window is undefined; any change in
window window status will be reported, that is if you disable a window
status callback and re-enable the callback, you are guaranteed the next
window status change will be reported.<br>
Setting the window status callback for a window disables the visibility
callback set for the window (and vice versa). The visibility callback is
set with <tt>glutVisibilityFunc</tt>, and its
state callback parameter is either GLUT_NOT_VISIBLE or GLUT_VISIBLE
depending on the current visibility of the window. GLUT_VISIBLE does not
distinguish a window being totally versus partially visible.
GLUT_NOT_VISIBLE means no part of the window is visible, i.e., until the
window's visibility changes, all further rendering to the window is
discarded.<br>
Not all window managers support such finegrained callback messages or
can even ensure basic correctness. On Windows, there are no
notifications if the visibility status of a window changes and
<i>freeglut</i> might be in visible state even if the window is fully
obscured by other windows.
</p>

<p><b>Changes From GLUT</b></p>

<p>None.</p>

<h1>13. <a name="StateSetting"></a>State Setting and Retrieval Functions</h1>

<h2>13.1 glutSetOption</h2>

<p>
Allows you to set some general state/option variables.
</p>

<p><b>Usage</b></p>

<p>
<tt>void glutSetOption ( GLenum eWhat, int value );</tt>
</p>

<p><b>Description</b></p>

<p>Stores the <tt>value</tt> into a state variable named by
<tt>eWhat</tt>.</p>
<p>
The following state variables can be set:
<ul>
<li>GLUT_ACTION_ON_WINDOW_CLOSE - Controls what happens when a window is
closed by the user or system. GLUT_ACTION_EXIT will immediately exit the
application (default, GLUT's behavior). GLUT_ACTION_GLUTMAINLOOP_RETURNS
will immediately return from the main loop.
GLUT_ACTION_CONTINUE_EXECUTION will contine execution of remaining
windows.</li>
<li>GLUT_INIT_DISPLAY_MODE - Set the display mode for a new window, see
<tt>glutInitDisplayMode</tt></li>
<li>GLUT_INIT_WINDOW_X - Set the initial horizontal position of new
windows.</li>
<li>GLUT_INIT_WINDOW_Y - Set the initial vertical position of new
windows.</li>
<li>GLUT_INIT_WINDOW_WIDTH - Set the width of new windows.</li>
<li>GLUT_INIT_WINDOW_HEIGHT - Set the height of new windows.</li>
<li>GLUT_RENDERING_CONTEXT - Set to either GLUT_CREATE_NEW_CONTEXT or
GLUT_USE_CURRENT_CONTEXT to indicate whether to share the current OpenGL
rendering context with new windows.</li>
<li>GLUT_WINDOW_CURSOR - Set the current window's cursor as if by
glutSetCursor().</li>
<li>GLUT_AUX - Set the number of auxiliary buffers requested for new
windows if GLUT_AUX was set in the displayMode.</li>
<li>GLUT_MULTISAMPLE - Set the number of samples to request for new
windows if GLUT_MULTISAMPLE was set in the displayMode.</li>
<li>GLUT_GEOMETRY_VISUALIZE_NORMALS - Set whether <a
href="#GeometricObject"><i>freeglut</i>'s geometric object rendering
functions</a> also visualize the object's normals or not.</li>
<li>GLUT_STROKE_FONT_DRAW_JOIN_DOTS - Set whether join dots are drawn
between line segments when drawing letters of stroke fonts or not.</li>
<li>GLUT_ALLOW_NEGATIVE_WINDOW_POSITION - Set if negative positions can be
used for window coordinates.</li>
</ul>
</p>

<p><b>Changes From GLUT</b></p>

<p>This function is not implemented in GLUT.</p>

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
<li>GLUT_WINDOW_X - window X position, see <a href="#Conventions">freeglut's conventions</a></li>
<li>GLUT_WINDOW_Y - window Y position, see <a href="#Conventions">freeglut's conventions</a></li>
<li>GLUT_WINDOW_WIDTH - window width, see <a href="#Conventions">freeglut's conventions</a></li>
<li>GLUT_WINDOW_HEIGHT - window height, see <a href="#Conventions">freeglut's conventions</a></li>
<li>GLUT_WINDOW_BORDER_WIDTH - window border width</li>
<li>GLUT_WINDOW_BORDER_HEIGHT - height of non-client area above window,
including both border and caption (if any)</li>
<li>GLUT_WINDOW_BUFFER_SIZE - number of color (including alpha) or color index bits per pixel</li>
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
<li>GLUT_INIT_DISPLAY_MODE - display mode last set by
glutInitDisplayMode or glutSetOption(GLUT_INIT_DISPLAY_MODE, value)</li>
<li>GLUT_ELAPSED_TIME - time (in milliseconds) elapsed since glutInit or glutGet(GLUT_ELAPSED_TIME) was first called</li>
<li>GLUT_INIT_STATE - 1 if <i>freeglut</i> has been initialized through
    a call to <tt>glutInit</tt></li>
<li>GLUT_VERSION - Return value will be X*10000+Y*100+Z where X is the
    major version, Y is the minor version and Z is the patch level.
    This query is only supported in <i>freeglut</i> (version 2.0.0 or later).</li>
<li>GLUT_ALLOW_NEGATIVE_WINDOW_POSITION - 1 if negative window positions are enabled, 0 otherwise</li>
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
<i>freeglut</i> supports two types of font rendering:  bitmap fonts,
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
<i>freeglut</i> supports the following bitmap fonts:
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
<i>freeglut</i> calls <tt>glRasterPos4v</tt> to advance the cursor by
the width of a character and to render carriage returns when appropriate.
It does not use any display lists in it rendering in bitmap fonts.
</p>

<p>
<i>freeglut</i> supports
the following stroke fonts:
</p>

<ul>
	<li><tt>GLUT_STROKE_ROMAN</tt> - A proportionally-spaced Roman Simplex font</li>
	<li><tt>GLUT_STROKE_MONO_ROMAN</tt> - A fixed-width Roman Simplex font</li>
</ul>

<p>
<i>freeglut</i> does not use any display lists in its rendering of stroke
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
<i>freeglut</i> automatically sets the necessary
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
<i>freeglut</i> automatically sets the necessary
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
The <tt>glutStrokeWidth</tt> function returns the width in model units of
a single character in the specified stroke font, rounded to an int.
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

<h2>14.9 glutStrokeWidthf</h2>

<p>
The <tt>glutStrokeWidthf</tt> function returns the width in model units of
a single character in the specified stroke font.
</p>

<p><b>Usage</b> </p>

<p><tt>GLfloat glutStrokeWidthf ( void *font,
int character );</tt> </p>

<p><tt>font
</tt>The stroke font to use in calculating
the character width <br/>
 <tt>character </tt>The ASCII
code of the character </p>

<p><b>Description</b></p>

<p>
The <tt>glutStrokeWidthf</tt>
function returns the width of the given character in the specified stroke
font. Function was included in an unreleased GLUT 3.8.
 </p>

<p><b>Changes From GLUT</b></p>

<p>Nonexistent characters return the width
of an asterisk. </p>

<h2>14.10 glutStrokeLength</h2>

<p>
The <tt>glutStrokeLength</tt> function returns the width in model units of
a string of characters in the specified stroke font, rounded to an int.
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
The <tt>glutStrokeLength</tt> function returns the width in model units of the given character string in
the specified stroke font. Because the font is a stroke font, the width
of an individual character is a floating-point number. <i>freeglut</i>
  adds the floating-point widths and rounds the final result to return the
integer value. Thus the return value may differ from the sum of the
character widths returned by a series of calls to <tt>glutStrokeWidth</tt>.
The width of nonexistent characters is counted to be the width
of an asterisk. </p>

<p>If the string contains
one or more carriage returns, <i>freeglut</i> calculates the widths in pixels
of the lines separately and returns the largest width. </p>

<h2>14.11 glutStrokeLengthf</h2>

<p>
The <tt>glutStrokeLengthf</tt> function returns the width in model units of
a string of characters in the specified stroke font.
</p>

<p><b>Usage</b></p>

<p><tt>GLfloat glutStrokeLengthf ( void *font,
char *string );</tt> </p>

<p><tt>font </tt>The stroke
font to use in calculating the character width <br/>
 <tt>string </tt>String of characters
whose width is to be calculated </p>

<p><b>Description</b></p>

<p>
The <tt>glutStrokeLengthf</tt> function returns the width in model units of the given character string in
the specified stroke font. The return value is equal to the sum of the
character widths returned by a series of calls to
<tt>glutStrokeWidthf</tt>. Function was included in an unreleased GLUT 3.8.
The width of nonexistent characters is counted to be the width
of an asterisk. </p>

<p>If the string contains
one or more carriage returns, <i>freeglut</i> calculates the widths in pixels
of the lines separately and returns the largest width. </p>

<h2>14.12 glutStrokeHeight</h2>

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
<i>freeglut</i> includes twenty two routines for generating
easily-recognizable 3-d geometric objects.  These routines are
effectively the same ones that are included in the GLUT library, and
reflect the functionality available in the <i>aux</i> toolkit described
in the <i>OpenGL Programmer's Guide</i>. They are included to allow
programmers to create with a single line of code a three-dimensional
object which can be used to test a variety of OpenGL functionality.
None of the routines generates a display list for the object which it
draws.  The functions generate normals appropriate for lighting but,
except for the teapot functions, do not generate texture coordinates. Do
note that depth testing (GL_LESS) should be enabled for the correct
drawing of the nonconvex objects, i.e., the glutTorus,
glutSierpinskiSponge, glutTeapot, glutTeacup and glutTeaspoon.<br>
Also see the <tt>GLUT_GEOMETRY_VISUALIZE_NORMALS</tt> option that can be
set with <tt>glutSetOption</tt>. Lastly, see
<tt>glutSetVertexAttribCoord3</tt>, <tt>glutSetVertexAttribNormal</tt>,
and <tt>glutSetVertexAttribTexCoord2</tt> if you wish to use these
objects with shaders.
</p>

<h2>15.1  glutWireSphere, glutSolidSphere</h2>

<p>
The <tt>glutWireSphere</tt> and <tt>glutSolidSphere</tt> functions
draw a wireframe and solid sphere respectively.
</p>

<p><b>Definition</b></p>

<p><tt>
void glutWireSphere (double dRadius, GLint slices, GLint stacks);<br>
void glutSolidSphere(double dRadius, GLint slices, GLint stacks);</tt> </p>

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
void glutWireTorus (double dInnerRadius, double dOuterRadius, GLint
nSides, GLint nRings);<br>
void glutSolidTorus(double dInnerRadius, double dOuterRadius, GLint
nSides, GLint nRings);</tt> </p>

<p><b>Arguments</b></p>
<p><tt>dInnerRadius&nbsp;&nbsp;</tt>The radius of the tube.</p>

<p><tt>dOuterRadius&nbsp;&nbsp;</tt>The distance from the center of the
Torus to the center of the tube.</p>

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
void glutWireCylinder (double base, double height, GLint slices, GLint
stacks);<br>
void glutSolidCylinder(double base, double height, GLint slices, GLint
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
void glutWireCone (double base, double height, GLint slices, GLint
stacks);<br>
void glutSolidCone(double base, double height, GLint slices, GLint
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
void glutWireCube (double dSize);<br>
void glutSolidCube(double dSize);</tt> </p>

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

<h2>15.11 glutWireTeapot, glutSolidTeapot, glutWireTeacup,
glutSolidTeacup, glutWireTeaspoon, glutSolidTeaspoon</h2>

<p>
The <tt>glutWireTeapot</tt> and <tt>glutSolidTeapot</tt> functions draw
a wireframe and solid teapot respectively, the <tt>glutWireTeacup</tt>
and <tt>glutSolidTeacup</tt> functions a wireframe and solid teacup, and
the <tt>glutWireTeaspoon</tt> and <tt>glutSolidTeaspoon</tt> functions a
wireframe and solid teaspoon.
</p>

<p><b>Definition</b></p>

<p><tt>
void glutWireTeapot&nbsp;&nbsp;&nbsp;(double dSize);<br>
void glutSolidTeapot&nbsp;&nbsp;(double dSize);<br>
void glutWireTeacup&nbsp;&nbsp;&nbsp;(double dSize);<br>
void glutSolidTeacup&nbsp;&nbsp;(double dSize);<br>
void glutWireTeaspoon&nbsp;(double dSize);<br>
void glutSolidTeaspoon(double dSize);</tt></p>

<p><b>Arguments</b></p>
<p><tt>dSize&nbsp;&nbsp;</tt>The desired size of the teapot, teacup and
teaspoon - relative to a "standard" size</p>

<p><b>Description</b></p>

<p>The <tt>glutWireTeapot</tt> and <tt> glutSolidTeapot</tt> functions
render a teapot of the desired size, centered at the origin. This is the
famous teapot created by Martin Newell. The other functions render the
teacup and teaspoon he used in the table scene figure in his PhD thesis.
Vertex data retrieved from: <a
href="ftp://ftp.funet.fi/pub/sci/graphics/packages/objects/teasetorig.gz">ftp://ftp.funet.fi/pub/sci/graphics/packages/objects/teasetorig.gz</a>.</p>

<p><b>Bugs</b></p>
<p>OpenGL's default <tt>glFrontFace</tt> state assumes that front facing
polygons (for the purpose of face culling) have vertices that wind
counter clockwise when projected into window space. This teapot, teacup
and teaspoon are rendered with their front facing polygon vertices
winding clockwise. For OpenGL's default back face culling to work, you
should use:</p>

<p><tt>&nbsp;&nbsp;glFrontFace(GL_CW);<br>
&nbsp;&nbsp;glutSolidTeapot(size);<br>
&nbsp;&nbsp;glFrontFace(GL_CCW);</tt></p>

<p>This bug reflect issues in the original teaset's vertex data
(and is thus present in GLUT too).</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT only has the teapot and misses the rest of the teaset. </p>

<h2>15.12 glutSetVertexAttribCoord3, glutSetVertexAttribNormal, glutSetVertexAttribTexCoord2</h2>
<p>
To draw shapes with shaders (OpenGL 2 and later), one need to upload
vertices and associated normal vectors and texture coordinates to
vertex attributes of your shaders. Use these functions to set the
indices (addresses) of the vertex attributes in your currently active
shaders before calling the above geometry functions, and <i>freeglut</i>
will upload the object geometry there. Texture coordinates are only
generated for the teapot, teacup and teaspoon.
</p>

<p><b>Definition</b></p>

<p><tt>
void glutSetVertexAttribCoord3&nbsp;&nbsp;&nbsp;(GLint attrib);<br>
void glutSetVertexAttribNormal&nbsp;&nbsp;&nbsp;(GLint attrib);<br>
void glutSetVertexAttribTexCoord2(GLint attrib);</tt></p>

<p><b>Arguments</b></p>
<p><tt>attrib&nbsp;&nbsp;</tt>The index (address) of the vertex
attribute</p>

<p><b>Changes From GLUT</b></p>

<p>GLUT does not include these functions.</p>

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

<h1>19. <a name="MultiTouch"></a>MultiTouch Functions</h1>

MultiTouch callbacks are used to handle environments with multiple
inputs, such as a multi-touch screen, multi-touch touchpad, or
multiple mouses.<br />

<h2>19.1 glutMultiEntryFunc &larr; id, GLUT_ENTERED|GLUT_LEFT</h2>
<h2>19.2 glutMultiButtonFunc &larr; id, x, y, button, GLUT_DOWN|GLUT_UP</h2>
<h2>19.3 glutMultiMotionFunc &larr; id, x, y</h2>
<h2>19.4 glutMultiPassiveFunc &larr; id, x, y</h2>

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

All these functions have user-data callback functions.

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

<p>Since this extra support comes at the cost of extra complexity, we're
<a href="http://sourceforge.net/mailarchive/forum.php?thread_name=20120518071314.GA28061%40perso.beuc.net&forum_name=freeglut-developer">considering</a>
whether/how to implement it.</p>

<h1>20. <a name="Mobile"></a>Mobile Functions</h1>

<p>These new callbacks were added:</p>

<ul>
<li><code>glutInitContextFunc &larr; void</code> : called when the context
is initialized or re-initialized (e.g. after a pause). Has user-data callback 
function.</li>
<li><code>glutAppStatusFunc &larr; event</code> : called when the
application's status changes, with event identifying the state entered. Has 
user-data callback function.
Possible states:
<ul>
<li>application goes on a pause (or a stop) &rarr; GLUT_APPSTATUS_PAUSE</li>
<li>application comes back from a pause &rarr; GLUT_APPSTATUS_RESUME. Is
called after the <code>glutInitContextFunc</code> callback.</li>
</ul>
</ul>

<p>Supported mobile platforms</p>
<ul>
<li>Android support is further described at
the <a href="android.php">Android page</a>.</li>
<li>BlackBerry 10/BlackBerry PlayBook</li>
</ul>

<h1>21. <a name="Miscellaneous"></a>Miscellaneous Functions</h1>

<h2>21.1 glutSetKeyRepeat, glutIgnoreKeyRepeat</h2>

<p>
The <tt>glutSetKeyRepeat</tt> and <tt>glutIgnoreKeyRepeat</tt> functions
set whether repeated key presses (generated by keeping a key depressed)
are passed on to the keyboard callbacks. <tt>glutSetKeyRepeat</tt>
allows to globally switch off key repeat, while
<tt>glutIgnoreKeyRepeat</tt> offers control over this behavior on a
per-window basis.
</p>

<p><b>Definition</b></p>

<p><tt>
void glutSetKeyRepeat&nbsp;&nbsp;&nbsp;(int repeatMode);<br>
void glutIgnoreKeyRepeat(int ignore);</tt></p>

<p><b>Arguments</b></p>
<p><tt>glutSetKeyRepeat's repeatMode&nbsp;&nbsp;</tt> 
GLUT_KEY_REPEAT_OFF to globally switch key repeat off, or
GLUT_KEY_REPEAT_ON and GLUT_KEY_REPEAT_DEFAULT to globally switch key
repeat on.<br>
<tt>glutIgnoreKeyRepeat's ignore&nbsp;&nbsp;&nbsp;</tt> if non-zero, key
repeat is switched off for the current window.</p>

<p><b>Notes</b></p>

<p>If key repeat is globally switched off through
<tt>glutSetKeyRepeat</tt>, it cannot be reenabled on a per-window basis
with <tt>glutIgnoreKeyRepeat</tt>. If you want per-window control of key
repeat, set <tt>glutSetKeyRepeat</tt> to <tt>GLUT_KEY_REPEAT_ON</tt> and
use <tt>glutIgnoreKeyRepeat(GL_TRUE)</tt> to switch off key repeat for
the windows for which you don't want it.</p>

<p><b>Changes From GLUT</b></p>

<p>Nate Robbins' port of GLUT to win32 did not implement
<tt>glutSetKeyRepeat</tt>, but <i>freeglut</i>'s behavior should conform on all
platforms to GLUT's behavior on X11.</p>

<h2>21.2 glutForceJoystickFunc</h2>

<h2>21.3 glutReportErrors</h2>

<h1>22. <a name="UsageNotes"></a>Usage Notes</h1>

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
</ul>

<p>
Furthermore, on windows, there is a resource file identifier GLUT_ICON
that you can specify for your executable file. It specifies the icon
that goes in the upper left-hand corner of the <i>freeglut</i> windows.
Your application's resource file should contain the line:<br>
<tt>GLUT_ICON   ICON    DISCARDABLE     "icon.ico"</tt><br> where
icon.ico is the filename of your icon. The One demo includes such an
icon as an example.
</p>

<h2>21.1 <a name="ImplementationNotes"></a>Implementation Notes</h2>

<h2>21.2 <a name="GLUT_State"></a>GLUT State</h2>

<h2>21.3 <a name="freeglut.h_Header"></a>"freeglut.h" Header File</h2>

<p>
Application programmers who are porting their GLUT programs to <i>freeglut</i> may continue
to include <tt>&lt;GL/glut.h&gt;</tt> in their programs.
Programs which use the <i>freeglut</i>-specific extensions to GLUT should include
<tt>&lt;GL/freeglut.h&gt;</tt>.  One possible arrangement is as follows:
</p>

<pre>
#ifdef FREEGLUT
#include &lt;GL/freeglut.h&gt;
#else
#include &lt;GL/glut.h&gt;
#endif
</pre>

<p>
It was initially planned to
define <code>FREEGLUT_VERSION_2_0</code>, <code>FREEGLUT_VERSION_2_1</code>, <code>FREEGLUT_VERSION_2_2</code>,
etc., but this was only done for <code>FREEGLUT_VERSION_2_0</code>.
This constant still exist in current freeglut releases but is
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

<h2>21.4 <a name="References"></a>References</h2>

<h2>21.5 <a name="Index"></a>Index</h2>

<?php generateFooter(); ?>
