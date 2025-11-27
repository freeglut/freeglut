# freeglut API

## Table of Contents

- [1. Contents](#1-contents)
- [2. Introduction](#2-introduction)
- [3. Background](#3-background)
	- [3.1 Design Philosophy](#31-design-philosophy)
	- [3.2 Conventions](#32-conventions)
		- [3.2.1 Window placement and size](#321-window-placement-and-size)
		- [3.2.2 User-data callbacks](#322-userdata-callbacks)
	- [3.3 Terminology](#33-terminology)
	- [3.4 Differences from GLUT 3.7](#34-differences-from-glut-37)
		- [3.4.1 glutMainLoop Behaviour](#341-glutmainloop-behaviour)
		- [3.4.2 Action on Window Closure](#342-action-on-window-closure)
		- [3.4.3 Fullscreen windows](#343-fullscreen-windows)
		- [3.4.4 Changes to Callbacks](#344-changes-to-callbacks)
		- [3.4.5 String Rendering](#345-string-rendering)
		- [3.4.6  Geometry Rendering](#346-geometry-rendering)
		- [3.4.7  Extension Function Queries](#347-extension-function-queries)
- [4. Initialization Functions](#4-initialization-functions)
	- [4.1 glutInit](#41-glutinit)
	- [4.2 glutInitWindowPosition, glutInitWindowSize](#42-glutinitwindowposition-glutinitwindowsize)
	- [4.3 glutInitDisplayMode](#43-glutinitdisplaymode)
	- [4.4 glutInitDisplayString](#44-glutinitdisplaystring)
	- [4.5 glutInitErrorFunc, glutInitWarningFunc](#45-glutiniterrorfunc-glutinitwarningfunc)
- [5. Event Processing Functions](#5-event-processing-functions)
	- [5.1 glutMainLoop](#51-glutmainloop)
	- [5.2  glutMainLoopEvent](#52-glutmainloopevent)
	- [5.3  glutLeaveMainLoop](#53-glutleavemainloop)
- [6. Window Functions](#6-window-functions)
	- [6.1 glutCreateWindow](#61-glutcreatewindow)
	- [6.2 glutCreateSubwindow](#62-glutcreatesubwindow)
	- [6.3 glutDestroyWindow](#63-glutdestroywindow)
	- [6.4 glutSetWindow, glutGetWindow](#64-glutsetwindow-glutgetwindow)
	- [6.5 glutSetWindowTitle, glutSetIconTitle](#65-glutsetwindowtitle-glutseticontitle)
	- [6.6 glutReshapeWindow](#66-glutreshapewindow)
	- [6.7 glutPositionWindow](#67-glutpositionwindow)
	- [6.8 glutShowWindow, glutHideWindow, glutIconifyWindow](#68-glutshowwindow-gluthidewindow-gluticonifywindow)
	- [6.9 glutPushWindow, glutPopWindow](#69-glutpushwindow-glutpopwindow)
	- [6.10 glutFullScreen, glutLeaveFullScreen, glutFullScreenToggle](#610-glutfullscreen-glutleavefullscreen-glutfullscreentoggle)
- [7. Display Functions](#7-display-functions)
	- [7.1 glutPostRedisplay](#71-glutpostredisplay)
	- [7.2 glutPostWindowRedisplay](#72-glutpostwindowredisplay)
	- [7.3 glutSwapBuffers](#73-glutswapbuffers)
	- [7.4 glutSwapInterval](#74-glutswapinterval)
- [8. Mouse Cursor Functions](#8-mouse-cursor-functions)
	- [8.1 glutSetCursor](#81-glutsetcursor)
	- [8.2 glutWarpPointer](#82-glutwarppointer)
- [9. Overlay Functions](#9-overlay-functions)
	- [9.1  glutEstablishOverlay](#91-glutestablishoverlay)
	- [9.2  glutRemoveOverlay](#92-glutremoveoverlay)
	- [9.3  glutUseLayer](#93-glutuselayer)
	- [9.4 glutPostOverlayRedisplay](#94-glutpostoverlayredisplay)
	- [9.5  glutPostWindowOverlayRedisplay](#95-glutpostwindowoverlayredisplay)
	- [9.6 glutShowOverlay, glutHideOverlay](#96-glutshowoverlay-gluthideoverlay)
- [10. Menu Functions](#10-menu-functions)
	- [10.1 glutCreateMenu](#101-glutcreatemenu)
	- [10.2 glutDestroyMenu](#102-glutdestroymenu)
	- [10.3 glutGetMenu, glutSetMenu](#103-glutgetmenu-glutsetmenu)
	- [10.4 glutAddMenuEntry](#104-glutaddmenuentry)
	- [10.5 glutAddSubMenu](#105-glutaddsubmenu)
	- [10.6 glutChangeToMenuEntry](#106-glutchangetomenuentry)
	- [10.7 glutChangeToSubMenu](#107-glutchangetosubmenu)
	- [10.8 glutSetMenuFont](#108-glutsetmenufont)
	- [10.9 glutRemoveMenuItem](#109-glutremovemenuitem)
	- [10.10 glutAttachMenu, glutDetachMenu](#1010-glutattachmenu-glutdetachmenu)
	- [10.11 glutMenuDestroyFunc](#1011-glutmenudestroyfunc)
- [11. Global Callback Registration Functions](#11-global-callback-registration-functions)
	- [11.1 glutTimerFunc](#111-gluttimerfunc)
	- [11.2 glutIdleFunc](#112-glutidlefunc)
	- [11.3 glutMenuStatusFunc](#113-glutmenustatusfunc)
	- [11.4 glutMenuStateFunc](#114-glutmenustatefunc)
- [12. Window-Specific Callback Registration Functions](#12-windowspecific-callback-registration-functions)
	- [12.1 glutDisplayFunc](#121-glutdisplayfunc)
	- [12.2 glutOverlayDisplayFunc](#122-glutoverlaydisplayfunc)
	- [12.3 glutReshapeFunc](#123-glutreshapefunc)
	- [12.4 glutPositionFunc](#124-glutpositionfunc)
	- [12.5 glutCloseFunc](#125-glutclosefunc)
	- [12.6 glutKeyboardFunc](#126-glutkeyboardfunc)
	- [12.7 glutSpecialFunc](#127-glutspecialfunc)
	- [12.8 glutKeyboardUpFunc](#128-glutkeyboardupfunc)
	- [12.9 glutSpecialUpFunc](#129-glutspecialupfunc)
	- [12.10 glutMotionFunc, glutPassiveMotionFunc](#1210-glutmotionfunc-glutpassivemotionfunc)
	- [12.11 glutMouseFunc](#1211-glutmousefunc)
	- [12.12 glutMouseWheelFunc](#1212-glutmousewheelfunc)
	- [12.13 glutEntryFunc](#1213-glutentryfunc)
	- [12.14 glutJoystickFunc](#1214-glutjoystickfunc)
	- [12.15 glutSpaceballMotionFunc](#1215-glutspaceballmotionfunc)
	- [12.16 glutSpaceballRotateFunc](#1216-glutspaceballrotatefunc)
	- [12.17 glutSpaceballButtonFunc](#1217-glutspaceballbuttonfunc)
	- [12.18 glutButtonBoxFunc](#1218-glutbuttonboxfunc)
	- [12.19 glutDialsFunc](#1219-glutdialsfunc)
	- [12.20 glutTabletMotionFunc](#1220-gluttabletmotionfunc)
	- [12.21 glutTabletButtonFunc](#1221-gluttabletbuttonfunc)
	- [12.22 glutVisibilityFunc, glutWindowStatusFunc](#1222-glutvisibilityfunc-glutwindowstatusfunc)
- [13. State Setting and Retrieval Functions](#13-state-setting-and-retrieval-functions)
	- [13.1 glutSetOption](#131-glutsetoption)
	- [13.2 glutGet](#132-glutget)
	- [13.3 glutDeviceGet](#133-glutdeviceget)
	- [13.4 glutGetModifiers](#134-glutgetmodifiers)
	- [13.5 glutLayerGet](#135-glutlayerget)
	- [13.6 glutExtensionSupported](#136-glutextensionsupported)
	- [13.7 glutGetProcAddress](#137-glutgetprocaddress)
	- [13.8 glutGetModeValues](#138-glutgetmodevalues)
- [14. Font Rendering Functions](#14-font-rendering-functions)
	- [14.1 glutBitmapCharacter](#141-glutbitmapcharacter)
	- [14.2 glutBitmapString](#142-glutbitmapstring)
	- [14.3 glutBitmapWidth](#143-glutbitmapwidth)
	- [14.4 glutBitmapLength](#144-glutbitmaplength)
	- [14.5 glutBitmapHeight](#145-glutbitmapheight)
	- [14.6 glutStrokeCharacter](#146-glutstrokecharacter)
	- [14.7 glutStrokeString](#147-glutstrokestring)
	- [14.8 glutStrokeWidth](#148-glutstrokewidth)
	- [14.9 glutStrokeWidthf](#149-glutstrokewidthf)
	- [14.10 glutStrokeLength](#1410-glutstrokelength)
	- [14.11 glutStrokeLengthf](#1411-glutstrokelengthf)
	- [14.12 glutStrokeHeight](#1412-glutstrokeheight)
- [15. Geometric Object Rendering Functions](#15-geometric-object-rendering-functions)
	- [15.1  glutWireSphere, glutSolidSphere](#151-glutwiresphere-glutsolidsphere)
	- [15.2 glutWireTorus, glutSolidTorus](#152-glutwiretorus-glutsolidtorus)
	- [15.3 glutWireCylinder, glutSolidCylinder](#153-glutwirecylinder-glutsolidcylinder)
	- [15.4 glutWireCone, glutSolidCone](#154-glutwirecone-glutsolidcone)
	- [15.5 glutWireCube, glutSolidCube](#155-glutwirecube-glutsolidcube)
	- [15.6 glutWireTetrahedron, glutSolidTetrahedron](#156-glutwiretetrahedron-glutsolidtetrahedron)
	- [15.7 glutWireOctahedron, glutSolidOctahedron](#157-glutwireoctahedron-glutsolidoctahedron)
	- [15.8 glutWireDodecahedron, glutSolidDodecahedron](#158-glutwiredodecahedron-glutsoliddodecahedron)
	- [15.9  glutWireIcosahedron, glutSolidIcosahedron](#159-glutwireicosahedron-glutsolidicosahedron)
	- [15.10 glutWireRhombicDodecahedron, glutSolidRhombicDodecahedron](#1510-glutwirerhombicdodecahedron-glutsolidrhombicdodecahedron)
	- [15.11 glutWireTeapot, glutSolidTeapot, glutWireTeacup,](#1511-glutwireteapot-glutsolidteapot-glutwireteacup)
	- [15.12 glutSetVertexAttribCoord3, glutSetVertexAttribNormal, glutSetVertexAttribTexCoord2](#1512-glutsetvertexattribcoord3-glutsetvertexattribnormal-glutsetvertexattribtexcoord2)
- [16. Game Mode Functions](#16-game-mode-functions)
	- [16.1 glutGameModeString](#161-glutgamemodestring)
	- [16.2 glutEnterGameMode, glutLeaveGameMode](#162-glutentergamemode-glutleavegamemode)
	- [16.3 glutGameModeGet](#163-glutgamemodeget)
- [17. Video Resize Functions](#17-video-resize-functions)
	- [17.1 glutVideoResizeGet](#171-glutvideoresizeget)
	- [17.2 glutSetupVideoResizing,](#172-glutsetupvideoresizing)
	- [17.3 glutVideoResize](#173-glutvideoresize)
	- [17.4 glutVideoPan](#174-glutvideopan)
- [18. Color Map Functions](#18-color-map-functions)
	- [18.1 glutSetColor, glutGetColor](#181-glutsetcolor-glutgetcolor)
	- [18.2 glutCopyColormap](#182-glutcopycolormap)
- [19. MultiTouch Functions](#19-multitouch-functions)
	- [19.1 glutMultiEntryFunc &larr; id, GLUT_ENTERED|GLUT_LEFT](#191-glutmultientryfunc-larr-id-glutenteredglutleft)
	- [19.2 glutMultiButtonFunc &larr; id, x, y, button, GLUT_DOWN|GLUT_UP](#192-glutmultibuttonfunc-larr-id-x-y-button-glutdownglutup)
	- [19.3 glutMultiMotionFunc &larr; id, x, y](#193-glutmultimotionfunc-larr-id-x-y)
	- [19.4 glutMultiPassiveFunc &larr; id, x, y](#194-glutmultipassivefunc-larr-id-x-y)
- [20. Mobile Functions](#20-mobile-functions)
- [21. Miscellaneous Functions](#21-miscellaneous-functions)
	- [21.1 glutSetKeyRepeat, glutIgnoreKeyRepeat](#211-glutsetkeyrepeat-glutignorekeyrepeat)
	- [21.2 glutForceJoystickFunc](#212-glutforcejoystickfunc)
	- [21.3 glutReportErrors](#213-glutreporterrors)
- [22. Usage Notes](#22-usage-notes)
	- [21.1 Implementation Notes](#211-implementation-notes)
	- [21.2 GLUT State](#212-glut-state)
	- [21.3 "freeglut.h" Header File](#213-freegluth-header-file)
	- [21.4 References](#214-references)
	- [21.5 Index](#215-index)

---

## 2. Introduction

## 3. Background


The OpenGL programming world owes a tremendous debt to Mr. Mark J. Kilgard
for writing the OpenGL Utility Toolkit, or GLUT.  The GLUT library
of functions allows an application programmer to create, control, and manipulate
windows independent of what operating system the program is running on.
By hiding the dependency on the operating system from the application programmer,
he allowed people to write truly portable OpenGL applications.


Mr. Kilgard copyrighted his library and gave it a rather unusual license.
Under his license,  people are allowed freely to copy and distribute the libraries
and the source code, but they are not allowed to modify it.  For a long time this did
not matter because the GLUT library worked so well and because Mr. Kilgard
was releasing updates on a regular basis.  But with the passage of time,
people started wanting some slightly different behaviours in their windowing
system.  When Mr. Kilgard stopped supporting the GLUT library in 1999,
having moved on to bigger and better things, this started to become a problem.


In December 1999, Mr. Pawel Olzsta started work on an open-source clone of the
GLUT library.
This open-source clone, which does not use any of the GLUT source code, has
evolved into the present *freeglut* library.  This documentation
specifies the application program interface to the *freeglut* library.

### 3.1 Design Philosophy

### 3.2 Conventions


In developing the *freeglut* library, we have taken careful steps
to ensure consistency in function operation across the board, in such a
manner as to maintain compatibility with GLUT's behavior whenever
possible. In this section some of the important conventions of freeglut,
and their compatibility with GLUT, are made explicit.

#### 3.2.1 Window placement and size


There is considerable confusion about the "right thing to do" concerning
window size and position. GLUT itself is not consistent between
Windows and UNIX/X11; since platform independence is a virtue for
*freeglut*, we decided to break with GLUT's behaviour.


**Important:** Window positioning behavior is highly dependent on the
window manager (WM) on X11/UNIX systems. The window system has significant
discretion in how it handles window placement requests and is not obligated
to honor them precisely. Different window managers may interpret positioning
requests differently, making exact window positioning inherently ambiguous
on these platforms.


Under UNIX/X11, it is not possible to get the window border
sizes in order to subtract them off the window's initial position until
some time after the window has been created. *freeglut* follows
the following conventions, both under Windows and under UNIX/X11:

- **Window positioning (setting):** When you create a window with
position (x,y) and size (w,h), or call `glutPositionWindow`,
*freeglut* __requests__ that the upper left corner of the
window frame (non-client area, including decorations) be placed at (x,y),
and that the drawable (client) area have size (w,h). The coordinates taken
by `glutInitPosition` and `glutPositionWindow`, as well as
the coordinates provided by *freeglut* when it calls the
`glutPositionFunc` callback, specify the intended top-left of the
non-client area (window frame).  
  

However, on X11/UNIX systems, the window manager may:
  - Position the client area (drawable area) at the requested coordinates, offsetting the frame accordingly
  - Position the window frame (including decorations) at the requested coordinates
  - Ignore the request entirely (e.g., tiling window managers)
  - Apply its own positioning policies
  - Spawn xeyes and look intently at the user

  

By default only positive-signed coordinates are supported. If
GLUT_ALLOW_NEGATIVE_WINDOW_POSITION is enabled, then negative coordinates
are supported. An exception for `glutPositionWindow` exists as it
always supports negative window coordinates.
- **Window positioning (querying):** When you query the position of
the window using `glutGet` with `GLUT_WINDOW_X` or
`GLUT_WINDOW_Y`, *freeglut* returns the coordinates of the
upper left corner of the drawable (client) area, __NOT__ the window
frame position that was specified in the positioning request. Similarly,
`glutGet` with `GLUT_WINDOW_WIDTH` or
`GLUT_WINDOW_HEIGHT` returns the size of the drawable area as
specified when the window was created.


This mismatch between the frame coordinates used for positioning and the
client coordinates returned by queries can cause windows to drift when
repositioned. Applications that need to reposition windows reliably should
measure the actual offset between requested and reported positions after
window creation, then compensate for this offset in subsequent positioning
calls.

#### 3.2.2 User-data callbacks


GLUT was created as a tool to help teach OpenGL programming. To simplify
development, callbacks were used for handling display, input, and other
events. But at the time it was developed, the purpose, or for some other
unknown reason, the callbacks lacked any user-provided data argument.
This has caused considerable difficulties for any significantly advanced
usage of GLUT, and now *freeglut*. This has prevented any attempt to
wrap *freeglut* in a C++ wrapper, make per-window, per-callback data
structure, and potentially made it undesirable to modern C developers who
tend to be well versed in "don't use globals". To combat these
complaints and *issues*, many callbacks (with some deprecated
callbacks excluded) support user-data callbacks provided through additional
functions provided in *freeglut*. All callbacks that support user-data
callbacks are marked as such.


The general rule to follow is to take the *freeglut* callback function
and append "Ucall" to the end of the function, add an additional `void*`
argument to the end of the argument list of both the *freeglut* function
and the callback function. This will pass the user-data to the callback when it's
invoked.


**Examples**


`void glutPositionFunc ( void (* func)( int x, int y ) );`


`void glutPositionFuncUcall ( void (* func)( int x, int y, void* user_data ), void* user_data );`


`void glutKeyboardUpFunc ( void (* func)( unsigned char key, int x, int y ) );`


`void glutKeyboardUpFuncUcall ( void (* func)( unsigned char key, int x, int y, void* user_data ), void* user_data );`

### 3.3 Terminology

### 3.4 Differences from GLUT 3.7


Since the *freeglut* library was developed in order to update GLUT,
it is natural that there will be some differences between the two.
Each function in the API notes any differences between the GLUT and the *
freeglut* function behaviours.  The important ones are summarized
here.

#### 3.4.1 glutMainLoop Behaviour


One of the commonest complaints about the GLUT library was that once an
application called `glutMainLoop`, it never got control back.
There was no way for an application to loop in GLUT for a while, possibly
as a subloop while a specific window was open, and then return to the calling
function.  A new function, `glutMainLoopEvent`, has been added
to allow this functionality.  Another function, `glutLeaveMainLoop`,
has also been added to allow the application to tell *freeglut* to clean
up and close down.

#### 3.4.2 Action on Window Closure


Another difficulty with GLUT, especially with multiple-window programs,
is that if the user clicks on the "x" in the window header the application
exits immediately.  The application programmer can now set an option,
` GLUT_ACTION_ON_WINDOW_CLOSE`, to specify whether execution should
continue, whether GLUT should return control to the main program, or whether
GLUT should simply exit (the default).

#### 3.4.3 Fullscreen windows


Function to leave fullscreen window mode, `glutLeaveFullScreen`,
or to toggle between fullscreen and normal window mode,
`glutFullScreenToggle`, have been added.

#### 3.4.4 Changes to Callbacks


Several new callbacks have been added and several callbacks which were specific
to Silicon Graphics hardware have not been implemented.  Most or all
of the new callbacks are listed in the GLUT Version 4 "glut.h" header file
but did not make it into the documentation.  The new callbacks consist
of regular and special key release callbacks, a joystick callback, a menu
state callback (with one argument, distinct from the menu status callback
which has three arguments), a window status callback (also with one
argument), and a window position callback.  Unsupported callbacks are
the two Tablet callbacks.  If the user has a need for an unsupported
callback he should contact the *freeglut* development team.

#### 3.4.5 String Rendering


New functions have been added to render full character strings (including
carriage returns) rather than rendering one character at a time.  More
functions return the widths of character strings and the font heights, in
pixels for bitmapped fonts and in OpenGL units for the stroke fonts.

#### 3.4.6  Geometry Rendering


Two functions have been added to render a wireframe and a solid rhombic
dodecahedron. Furthermore, solid and wireframe versions of the original
teacup and teaspoon that accompanied the famous Newell teapot. As these
geometry functions are often used for teaching purposes,
`glutSetOption(GLUT_GEOMETRY_VISUALIZE_NORMALS,true/false)` can
now be used to visualize the normal vectors for each vertex. Lastly, to
support drawing these objects with shaders, three functions have been
added with which users can provide the addresses of the Coordinate,
Normal and Texture Coordinate vertex attribs:
`glutSetVertexAttribCoord3`, `glutSetVertexAttribNormal`,
and `glutSetVertexAttribTexCoord2`. Texture coordinates are only
generated for the teaset.

#### 3.4.7  Extension Function Queries


glutGetProcAddress is a wrapper for the glXGetProcAddressARB and wglGetProcAddress
functions.

## 4. Initialization Functions

### 4.1 glutInit

### 4.2 glutInitWindowPosition, glutInitWindowSize


The `glutInitWindowPosition` and `glutInitWindowSize`
functions specify a desired position and size for windows that *freeglut*
will create in the future.


**Usage**


`void glutInitWindowPosition ( int x, int y );`


`void glutInitWindowSize ( int width, int height );`


**Description**


The `glutInitWindowPosition` and `glutInitWindowSize`
functions specify a desired position and size for windows that
*freeglut* will create in the future.
The position is measured in pixels from the upper left hand corner of the
screen, with "x" increasing to the right and "y" increasing towards the bottom
of the screen.  The size is measured in pixels. *freeglut*
does not promise to follow these specifications in creating its windows,
but it certainly makes an attempt to.


The position and size of a window are a matter of some subtlety.  Most
windows have a usable area surrounded by a border and with a title bar
on the top.  The border and title bar are commonly called "decorations."


**Window Positioning:** When setting window position, you specify the
intended coordinates of the upper left-hand corner of the window's decorations
(frame). The size parameter specifies the size of the usable interior (client
area).


However, **on X11/UNIX systems**, the window manager has final authority
over window placement and may not honor the requested position exactly. The
window manager may position the frame at the requested location, adjust it to
account for decorations, or apply its own positioning policies. See
[freeglut's conventions](#32-conventions) for detailed information
about this window manager discretion.


**On Windows**, the positioning behavior is more predictable, with the
window frame being positioned at the requested coordinates.


With `glutGet` information can be acquired about the current
window's size, position and decorations. Note however that according to
[freeglut's conventions](#32-conventions), the information
returned about the window coordinates does not correspond to the
coordinates used when setting window position. In addition, GLUT only
accepts positive window coordinates, and ignores all negative window
coordinates. But if GLUT_ALLOW_NEGATIVE_WINDOW_POSITION is enabled,
then negative window coordinates can be used. This is useful for
multi-monitor setups where the second monitor may be in the negative
desktop space of the primary monitor, as now the window can be placed
on the additional monitors. Furthermore, this flag also determines how
negative coordinates and sizes are interpreted for subwindows.


Windows has some additional quirks which the application programmer
should know about.  First, the minimum y-coordinate
of a window decoration is zero.  (This is a feature of *freeglut*
and can be adjusted if so desired.)  Second, there appears to be a
minimum window width on Windows which is 104 pixels.  The user may specify
a smaller width, but the Windows system calls ignore it.  It is also
impossible to make a window narrower than this by dragging on its corner.


**Changes From GLUT**


For some reason, GLUT is not affected by the 104-pixel minimum window width.
If the user clicks on the corner of a window which is narrower than this amount,
the window will immediately snap out to this width, but the application can
call `glutReshapeWindow` and make a window narrower again.


If GLUT_ALLOW_NEGATIVE_WINDOW_POSITION is enabled, `glutInitWindowPosition`
will accept negative window coordinates.

### 4.3 glutInitDisplayMode

### 4.4 glutInitDisplayString


**Changes From GLUT**


glutInitDisplayString support is limited: any of the tokens recognized
by GLUT are also recognized by *freeglut*, but any statements with
comparators cannot (yet: do [help develop
this!](../help.php)) be handled. Any spec (comparator and value) after the token
is ignored. However, many of these values can be set with glutSetOption
for now...

### 4.5 glutInitErrorFunc, glutInitWarningFunc


The `glutInitErrorFunc` and `glutInitWarningFunc`
functions specify callbacks that will be called upon warnings and errors
issued from within *freeglut* so that the user can deal with these.
Useful for rerouting to another output sink (e.g., logging) and also to
avoid exit(1) being called upon error. As with other glutInit*
functions, these can be set before glutInit is called, so any output
from the library can be handled by the user.*


**Usage**


`void glutInitErrorFunc   ( void (* callback)( const char *fmt, va_list ap) );`


`void glutInitWarningFunc ( void (* callback)( const char *fmt, va_list ap) );`


These functions have user-data callback functions.


**Description**


The users callback is passed a format string and a variable argument
list that can be passed to functions such as `printf`.


Note that there are the preprocessor definitions
`FREEGLUT_PRINT_ERRORS` and `FREEGLUT_PRINT_WARNINGS`,
which affect *freeglut*'s warning and error behavior when no user
callback is defined. If defined at library (not client app!) compile
time--by default it is, warnings and errors are printed to
`stderr`. If not defined, warnings and errors are muted (not
printed to stderr), though errors still trigger deinitialization and
exit. Whether `FREEGLUT_PRINT_ERRORS` and
`FREEGLUT_PRINT_WARNINGS` is defined does not affect whether the
client callback is called, it only affects whether warnings and errors
are printed to `stderr` when no callback is defined.


**Changes From GLUT**


GLUT does not provide these functions.

## 5. Event Processing Functions


After an application has finished initializing its windows and menus, it
enters an event loop.  Within this loop, *freeglut* polls the
data entry devices (keyboard, mouse, etc.) and calls the application's appropriate
callbacks.


In GLUT, control never returned from
the event loop (as invoked by the `glutMainLoop` function) to the
calling function.  This prevented an application from having re-entrant
code, in which GLUT could be invoked from within a callback, and it prevented
the application from doing any post-processing (such as freeing allocated
memory) after GLUT had closed down.  *freeglut* allows the application
programmer to specify more direct control over the event loop by means of
two new functions.  The first, `glutMainLoopEvent`, processes
a single iteration of the event loop and allows the application to use a different
event loop controller or to contain re-entrant code.  The second, `
glutLeaveMainLoop`, causes the event loop to exit nicely; this is preferable
to the application's calling `exit` from within a GLUT callback.

### 5.1 glutMainLoop


The `glutMainLoop` function enters the event loop.


**Usage**


`void glutMainLoop ( void );`


**Description**


The `glutMainLoop` function
causes the program to enter the window event loop.  An application should
call this function at most once.  It will call any application callback
functions as required to process mouse clicks, mouse motion, key presses,
and so on.


**Changes From GLUT**


In GLUT, there was absolutely no way for the application programmer to
have control return from the `glutMainLoop` function to the
calling function.  *freeglut* allows the programmer
to force this by setting the `GLUT_ACTION_ON_WINDOW_CLOSE` option
and invoking the `glutLeaveMainLoop` function from one of the callbacks.
Stopping the program this way is preferable to simply calling `exit`
from within a callback because this allows *freeglut* to free allocated
memory and otherwise clean up after itself.  (I know I just said this,
but I think it is important enough that it bears repeating.)

### 5.2  glutMainLoopEvent


The `glutMainLoopEvent` function processes a single iteration
in the *freeglut* event loop.


**Usage**


`void glutMainLoopEvent ( void );`


**Description**


The `glutMainLoopEvent` function causes *freeglut* to process
one iteration's worth of events in its event loop.  This allows the application
to control its own event loop and still use the *freeglut* windowing system.


**Changes From GLUT**


GLUT does not include this function.

### 5.3  glutLeaveMainLoop


The `glutLeaveMainLoop` function causes *freeglut* to stop
its event loop.


**Usage**


`void glutLeaveMainLoop ( void );`


**Description**


The  `glutLeaveMainLoop` function causes *freeglut* to stop the event loop.  If the
`GLUT_ACTION_ON_WINDOW_CLOSE` option has been set to
`GLUT_ACTION_GLUTMAINLOOP_RETURNS` or
`GLUT_ACTION_CONTINUE_EXECUTION`, control will return to the
function which called `glutMainLoop`; otherwise the application
will exit.


If the application has two nested calls to `glutMainLoop` and calls
`glutLeaveMainLoop`, the behaviour
of *freeglut* is undefined.  It may leave only the inner nested
loop or it may leave both loops.  If the reader has a strong preference
for one behaviour over the other he should contact the *freeglut* Programming
Consortium and ask for the code to be fixed.


**Changes From GLUT**


GLUT does not include this function.

## 6. Window Functions

### 6.1 glutCreateWindow

### 6.2 glutCreateSubwindow


The `glutCreateSubwindow` function creates a subwindow of an existing window.


**Usage**


`int glutCreateSubwindow(int window, int x, int y, int width, int height);`


**Description**


Creates a subwindow of *window* that is at location *x* and *y*
relative to the window's upper-left corner, and is of the specified *width* and *height*. The newly created
window ID is returned by `glutCreateSubwindow`. By default, the position coordinates will only allow windows within the bounds of the parent.
Negative coordinates be treated as coordinates from the opposite edge for a given axis. In addition, the width of the window will be taken into account.
For example, if the parent window is 100 pixels wide, and the *x* is 10, and *width* is 20, the subwindow will be located at `x = 10`.
If *x* is -10, then the subwindow will be located at 70 `(parent - abs(pos) - dim)`. If the *width* or *height* are negative, then the dimension is taken as a
subtraction of the parent dimension. For example, if the parent window is 100 pixels wide, and the *x* is 10, and *width* is 20, the
subwindow will have a size of 20. If *width* is -20, then the subwindow will have a width of 70 `(parent - pos - abs(dim))`.


If GLUT_ALLOW_NEGATIVE_WINDOW_POSITION is enabled, the window behavior differs. Negative window coordinates are now accepted and may result in windows outside
of the viewing area, depending on the platform of operation. Negative *width* and *height* are still used as a subtraction of the parent window dimension,
but they do not take *x* or *y* into account. For example, if the parent window is 100 pixels wide, and the *x* is 10, and *width* is 20, the
subwindow will be located at `x = 10`. If *x* is -10, then the subwindow will be located at `x = -10`. If the parent window is 100 pixels wide,
and the *x* is 10, and *width* is 20, the subwindow will have a size of 20. If *width* is -20, then the subwindow will have a width of 80 `(parent - abs(dim))`.


**Changes From GLUT**


GLUT does not support negative *x* or *y*. Nor does it have GLUT_ALLOW_NEGATIVE_WINDOW_POSITION
which changes the the functionality of `glutCreateSubwindow`.

### 6.3 glutDestroyWindow

### 6.4 glutSetWindow, glutGetWindow

### 6.5 glutSetWindowTitle, glutSetIconTitle


The `glutSetWindowTitle`, `glutSetIconTitle` set the
window title for when the window is in a visible state and when it is in
an iconified state respectively.


**Usage**


`glutSetWindowTitle(const char* title);`


`glutSetIconTitle(const char* title);`


**Description**


Normally a window system displays a title for every top-level window in
the system. The initial title is set when you call glutCreateWindow().
By means of the `glutSetWindowTitle` function you can set the
titles for your top-level *freeglut* windows. If you just want one
title for the window over the window's entire life, you should set it
when you open the window with glutCreateWindow().


`glutSetIconTitle` sets the title to be displayed for the window
when it is in iconified (minimized) state.


**Changes From GLUT**


`glutSetIconTitle` does nothing in GLUT on Windows, but is
emulated on Windows by *freeglut*.

### 6.6 glutReshapeWindow

### 6.7 glutPositionWindow

### 6.8 glutShowWindow, glutHideWindow, glutIconifyWindow

### 6.9 glutPushWindow, glutPopWindow

### 6.10 glutFullScreen, glutLeaveFullScreen, glutFullScreenToggle


The `glutFullScreen`, `glutLeaveFullScreen` and
`glutFullScreenToggle` functions are used to transition the
current window between fullscreen and normal mode.


**Usage**


`void glutFullScreen ( void );`


`void glutLeaveFullScreen ( void );`


`void glutFullScreenToggle ( void );`


**Description**


The `glutFullScreen` function causes the current window to enter
fullscreen mode, `glutLeaveFullScreen` to go back to the window
size and position as it was before entering fullscreen mode, and
`glutFullScreenToggle` toggles between fullscreen and normal
mode.


In multi-monitor setups on Windows 2000 and newer, the window will
become fullscreen on the monitor that it overlaps the most.


Calls to these functions are ignored for gamemode and child windows.


Use `glutGet(GLUT_FULL_SCREEN)` to query fullscreen state of
current window.


**Changes From GLUT**


GLUT does not include the `glutLeaveFullScreen` and
`glutFullScreenToggle` functions.

## 7. Display Functions

### 7.1 glutPostRedisplay

### 7.2 glutPostWindowRedisplay

### 7.3 glutSwapBuffers

### 7.4 glutSwapInterval


`glutSwapInterval` allows the application to control the synchronization of
buffer swaps with display frames (v-sync).


**Usage**


`void glutSwapInterval(int interval);`


**Description**


An `interval` of 0 requests no synchronization, buffers should be swapped
immediately, even if the display device is in the middle of scanning out the
previous frame. This may result in visible artifacts (tearing).


An `interval` of 1 is the standard v-sync setting. The swap is performed during
the next vertical blanking period, after the current frame is done being scanned
out to the display. Any other positive `interval` greater than 1, will wait for
that many v-blank periods before swapping.


Negative `interval` values are for "adaptive v-sync". If drawing is completed
and the swap is ready to be executed before the target v-blank, the swap will be
performed at the target v-blank, exactly as if the `interval` was positive. If
the target interval has elapsed and the swap is running late, instead of
waiting more, the swap will be performed immediately, unsynchronzied with the
display.


Not all GL implementations support all of these semantics. Freeglut will attempt
to fall-back to the nearest-equivalent supported swap interval. If adaptive
v-sync is not supported, negative values will be flipped to their positive
equivalent. If running unsynchronized is not possible, an interval of 1 will be
used instead.


**Changes From GLUT**


GLUT does not include this function.

## 8. Mouse Cursor Functions

### 8.1 glutSetCursor

### 8.2 glutWarpPointer

## 9. Overlay Functions


*freeglut* does not allow overlays, although it does "answer the mail"
with function stubs so that GLUT-based programs can compile and link against
*freeglut* without modification.


If the reader needs overlays, he should contact the *freeglut* Programming
Consortium and ask for them to be implemented.  He should also be prepared
to assist in the implementation.

### 9.1  glutEstablishOverlay


The `glutEstablishOverlay` function is not implemented in *freeglut*.


**Usage**


`void glutEstablishOverlay ( void );`


**Description**


The `glutEstablishOverlay` function is not implemented in *freeglut*.


**Changes From GLUT**


GLUT implements this function.

### 9.2  glutRemoveOverlay


The `glutRemoveOverlay` function is not implemented in *freeglut*.


**Usage**


`void glutRemoveOverlay ( void );`


**Description**


The `glutRemoveOverlay` function
is not implemented in *freeglut*.


**Changes From GLUT**


GLUT implements this function.

### 9.3  glutUseLayer


The `glutUseLayer` function is not implemented in *freeglut*.


**Usage**


`void glutUseLayer ( GLenum layer );`


**Description**


The `glutUseLayer` function
is not implemented in *freeglut*.


**Changes From GLUT**


GLUT implements this function.

### 9.4 glutPostOverlayRedisplay


The `glutPostOverlayRedisplay` function is not implemented in *freeglut*.


**Usage**


`void glutPostOverlayRedisplay ( void );`


**Description**


The `glutPostOverlayRedisplay` function is not implemented
in *freeglut*.


**Changes From GLUT**


GLUT implements this function.

### 9.5  glutPostWindowOverlayRedisplay


The `glutPostWindowOverlayRedisplay` function is not implemented
in *freeglut*.


**Usage**


`void glutPostWindowOverlayRedisplay ( int window );`


**Description**


The `glutPostWindowOverlayRedisplay` function is not implemented in *freeglut*.


**Changes From GLUT**


GLUT implements this function.

### 9.6 glutShowOverlay, glutHideOverlay


The `glutShowOverlay` and `glutHideOverlay` functions
are not implemented in *freeglut*.


**Usage**


`void glutShowOverlay( void );`


`void glutHideOverlay( void );`


**Description**


The `glutShowOverlay` and `glutHideOverlay` functions are not implemented in *freeglut*.


**Changes From GLUT**


GLUT implements these functions.

## 10. Menu Functions

### 10.1 glutCreateMenu


Has user-data callback function.

### 10.2 glutDestroyMenu

### 10.3 glutGetMenu, glutSetMenu

### 10.4 glutAddMenuEntry

### 10.5 glutAddSubMenu

### 10.6 glutChangeToMenuEntry

### 10.7 glutChangeToSubMenu

### 10.8 glutSetMenuFont


`glutSetMenuFont` sets the (bitmap) font to be used for drawing
the specified menu.


**Usage**


`void glutSetMenuFont( int menuID, void* fontID );`


**Description**


Only bitmap fonts (`GLUT_BITMAP_xxx`, see [here](#FontRendering) for a list) can be used as menu fonts.  A
warning is issued and the request is ignored if the supplied font is a
stroke font, or an unknown font.


**Changes From GLUT**


GLUT does not provide this function.

### 10.9 glutRemoveMenuItem

### 10.10 glutAttachMenu, glutDetachMenu

### 10.11 glutMenuDestroyFunc


Has user-data callback function.

## 11. Global Callback Registration Functions

### 11.1 glutTimerFunc


Has user-data callback function.

### 11.2 glutIdleFunc


The `glutIdleFunc` function sets the global idle callback. *
freeglut*  calls the idle callback when there are no inputs from the user.


**Usage**


`void glutIdleFunc ( void (*func ) ( void ) );`


`func` The new global idle callback function


Has user-data callback function.


**Description**


The `glutIdleFunc` function
specifies the function that *freeglut* will call to perform background
processing tasks such as continuous animation when window system events are
not being received.  If enabled, this function is called continuously
from *freeglut* while no events are received.  The callback function
has no parameters and returns no value.  *freeglut* does not change
the *current window* or the *current menu* before invoking the idle
callback; programs with multiple windows or menus must explicitly set the
*current window* and *current menu*
and not rely on its current setting.


The amount of computation and rendering done in an idle
callback should be minimized to avoid affecting the program's interactive
response.  In general, no more than a single frame of rendering should
be done in a single invocation of an idle callback. Note that no actual
drawing to the framebuffer should be done from the idle callback, this
is not supported. While it might work on some platforms, it will not on
others or might stop working in the future. Drawing should be done in
the `glutDisplayFunc` callback.


Calling `glutIdleFunc` with a NULL argument
disables the call to an idle callback.


**Changes From GLUT**


Application programmers should note that
if they have specified the "continue execution" action on window closure,
*freeglut* will continue to call the
idle callback after the user has closed a window by clicking on the "x" in
the window header bar.  If the idle callback renders a particular window
(this is considered bad form but is frequently done anyway), the programmer
should supply a window closure callback for that window which changes or disables
the idle callback.

### 11.3 glutMenuStatusFunc


Has user-data callback function.

### 11.4 glutMenuStateFunc

## 12. Window-Specific Callback Registration Functions

### 12.1 glutDisplayFunc


Has user-data callback function.

### 12.2 glutOverlayDisplayFunc


Has user-data callback function.

### 12.3 glutReshapeFunc


Has user-data callback function.

### 12.4 glutPositionFunc


The `glutPositionFunc` function sets the window's position
callback. *freeglut* calls the position callback when the window is
repositioned/moved programatically or by the user.


**Usage**


`void glutPositionFunc ( void
(* func)( int x, int y) );`


Has user-data callback function.


**Description**


When *freeglut* calls this callback, it attempts to provide the new
position on the screen of the top-left of the __non-client area__ (window
frame), that is, the same frame coordinates as specified by
`glutInitPosition` and `glutPositionWindow`. However, on
X11/UNIX systems, the actual position reported depends on the window manager's
interpretation and may vary. To get the position on the screen of the top-left
of the client area, use `glutGet(GLUT_WINDOW_X)` and
`glutGet(GLUT_WINDOW_Y)`. See [freeglut's
conventions](#32-conventions) for more information.


**Changes From GLUT**


This function is not implemented in GLUT.

### 12.5 glutCloseFunc


The `glutCloseFunc` function sets the window's close
callback. *freeglut* calls the close callback when the window is
about to be destroyed.


**Usage**


`void glutCloseFunc ( void (*func) ( void ) );`


`func` The window's new closure callback function


Has user-data callback function.


**Description**


The `glutCloseFunc` function specifies the function
that *freeglut* will call to notify the application that a window
is about to be closed, either because it is requested using
`glutDestroyWindow`, because the user clicked on the "x" in the
window header (for top-level windows only), or due to a pending closure
of a subwindow's parent window. In the first case, the closure callback
is not invoked from the `glutDestroyWindow` call, but at a
later time point.


*freeglut* sets the *current window* to the window
which is about to be closed when the callback is invoked. The window can
thus be retrieved in the callback using `glutGetWindow`.


Users looking to prevent *freeglut* from exiting when a window is
closed, should look into using glutSetOption to set
`GLUT_ACTION_ON_WINDOW_CLOSE`. Some settings will prevent the
application from exiting when a window is closed.


Please note that `glutWMCloseFunc`, a deprecated function, is an
alias to `glutCloseFunc`.


**Changes From GLUT**


This function is not implemented in GLUT.

### 12.6 glutKeyboardFunc


Has user-data callback function.

### 12.7 glutSpecialFunc


The `glutSpecialFunc` function sets the window's special key press
callback. *freeglut* calls the special key press callback when the
user presses a special key.


**Usage**


`void glutSpecialFunc ( void (*func)
( int key, int x, int y ) );`


`func `The window's
new special key press callback function


`key `The
key whose press triggers the callback


`x
`The x-coordinate of the mouse relative
to the window at the time the key is pressed


`y
`The y-coordinate of the mouse relative
to the window at the time the key is pressed


Has user-data callback function.


**Description**


The `glutSpecialFunc` function specifies the function
that *freeglut* will call when the user
presses a special key on the keyboard.  The callback function has one
argument: the name of the function to be invoked ("called back") at
the time at which the special key is pressed.  The function returns no
value.  *freeglut* sets the *current window* to the window
which is active when the callback is invoked.  "Special keys" are the
function keys, the arrow keys, the Page Up and Page Down keys, and the Insert
key.  The Delete key is considered to be a regular key.


Calling `glutSpecialUpFunc` with a NULL argument
disables the call to the window's special key press callback.


The `key` argument may take one of the following defined constant values:

- `GLUT_KEY_F1, GLUT_KEY_F2, ..., GLUT_KEY_F12` - F1 through F12 keys
- `GLUT_KEY_PAGE_UP, GLUT_KEY_PAGE_DOWN`        - Page Up and Page Down keys
- `GLUT_KEY_HOME, GLUT_KEY_END`                 - Home and End keys
- `GLUT_KEY_LEFT, GLUT_KEY_RIGHT, GLUT_KEY_UP, GLUT_KEY_DOWN` - Arrow keys
- `GLUT_KEY_INSERT`                             - Insert key


**Changes From GLUT**


None.

### 12.8 glutKeyboardUpFunc


The `glutKeyboardUpFunc` function sets the window's key release
callback. *freeglut* calls the key release callback when the user releases
a key.


**Usage**


`void glutKeyboardUpFunc ( void (*func)
( unsigned char key, int x, int y ) );`


`func `The window's
new key release callback function


`key `The
key whose release triggers the callback


`x
`The x-coordinate of the mouse relative
to the window at the time the key is released


`y
`The y-coordinate of the mouse relative
to the window at the time the key is released


Has user-data callback function.


**Description**


The `glutKeyboardUpFunc`
function specifies the function that *freeglut* will call when the
user releases a key from the keyboard.  The callback function has one
argument:  the name of the function to be invoked ("called back") at
the time at which the key is released.  The function returns no value.
*Freeglut* sets the *current window* to the window which is
active when the callback is invoked.
While *freeglut* checks
for upper or lower case letters, it does not do so for non-alphabetical
characters.  Nor does it account for the Caps-Lock key being on.
The operating system may send some unexpected characters to
*freeglut*, such as "8" when the user is pressing the Shift
key.  *freeglut* also invokes the callback when the user
releases the Control, Alt, or Shift keys, among others.  Releasing
the Delete key causes this function to be invoked with a value of
127 for `key`.
Calling `glutKeyboardUpFunc` with
a NULL argument disables the call to the window's key release callback.


**Changes From GLUT**


This function is not implemented in GLUT
versions before Version 4.  It has been designed to be as close to GLUT
as possible.  Users who find differences should contact the
*freeglut* Programming Consortium to have them fixed.

### 12.9 glutSpecialUpFunc


The `glutSpecialUpFunc` function sets the window's special key
release callback. *freeglut* calls the special key release callback
when the user releases a special key.


**Usage**


`void glutSpecialUpFunc ( void (*func)
( int key, int x, int y ) );`


`func `The window's
new special key release callback function


`key `The
key whose release triggers the callback


`x
`The x-coordinate of the mouse relative
to the window at the time the key is released


`y
`The y-coordinate of the mouse relative
to the window at the time the key is released


Has user-data callback function.


**Description**


The `glutSpecialUpFunc`function specifies the function that *freeglut* will call when the
user releases a special key from the keyboard.  The callback function
has one argument: the name of the function to be invoked ("called back")
at the time at which the special key is released.  The function returns
no value. *freeglut* sets the *current window* to the window
which is active when the callback is invoked.  "Special keys" are the
function keys, the arrow keys, the Page Up and Page Down keys, and the Insert
key.  The Delete key is considered to be a regular key.


Calling `glutSpecialUpFunc` with a NULL argument
disables the call to the window's special key release callback.


The `key` argument may take one of the following defined constant values:

- `GLUT_KEY_F1, GLUT_KEY_F2, ..., GLUT_KEY_F12`  - F1 through F12 keys
- `GLUT_KEY_PAGE_UP, GLUT_KEY_PAGE_DOWN`  - Page Up and Page Down keys
- `GLUT_KEY_HOME, GLUT_KEY_END`  - Home and End keys
- `GLUT_KEY_LEFT, GLUT_KEY_RIGHT, GLUT_KEY_UP, GLUT_KEY_DOWN` - arrow keys
- `GLUT_KEY_INSERT` - Insert key


**Changes From GLUT**


This function is not implemented in GLUT
versions before Version 4.  It has been designed to be as close to GLUT
as possible.  Users who find differences should contact the
*freeglut* Programming Consortium  to
have them fixed.

### 12.10 glutMotionFunc, glutPassiveMotionFunc


Both functions have user-data callback functions.

### 12.11 glutMouseFunc


Has user-data callback function.

### 12.12 glutMouseWheelFunc


The `glutMouseWheelFunc` function sets the window's mouse wheel
callback. *freeglut* calls the mouse wheel callback when the user
spins the mouse wheel.


**Usage**


`void glutMouseWheelFunc ( void( *callback )( int wheel, int
direction, int x, int y ));`


Has user-data callback function.


**Description**


If the mouse wheel is spun over your (sub)window, *freeglut*
will report this via the MouseWheel callback. `wheel` is the wheel
number, `direction` is +/- 1, and `x` and `y` are
the mouse coordinates.


If you do not register a wheel callback, wheel events will be reported
as mouse buttons.


**Changes From GLUT**


This function is not implemented in GLUT.

### 12.13 glutEntryFunc


Has user-data callback function.

### 12.14 glutJoystickFunc


Has user-data callback function.

### 12.15 glutSpaceballMotionFunc


The `glutSpaceballMotionFunc` function is implemented in
*freeglut* on X11 and Windows only.  On other platforms, function stubs are
provided so that GLUT-based programs can compile and link against
*freeglut* without modification.


The `glutSpaceballMotionFunc` function sets the window's Spaceball motion callback. *freeglut* invokes this callback when the user push/pull Spaceball cap in *x*, *y*, and *z* directions.


**Usage**


`void glutSpaceballMotionFunc ( void (* callback)( int x, int y, int z ) );`


Has user-data callback function.


**Description**


The *x*, *y*, and *z* arguments indicate the amount of translation in integer along x, y, and z axis respectively.


The x, y, and z axes form a common OpenGL right-handed coordinate system. A positive value of *x*, *y*, or *z* indicates movement along the positive direction of the respective axis, while the negative one denotes movement along negative direction.

### 12.16 glutSpaceballRotateFunc


The `glutSpaceballRotateFunc` function is implemented in
*freeglut* on X11 and Windows only. On other platforms, function stubs are
provided so that GLUT-based programs can compile and link against
*freeglut* without modification.


The `glutSpaceballRotateFunc` function sets the window's Spaceball rotation callback. *freeglut* invokes this callback when the user rotates/twists Spaceball cap.


**Usage**


`void glutSpaceballRotateFunc ( void (* callback)( int rx, int ry, int rz ) );`


Has user-data callback function.


**Description**


The *rx*, *ry*, and *rz* arguments indicate the amount of rotation in integer with respect to x, y, and z axis respectively.


The x, y, and z axes form a common OpenGL right-handed coordinate system. Positive value of *rx*, *ry*, or *rz* indicates counter-clock wise rotation along the respective axis, while negative one denotes clock wise rotation.

### 12.17 glutSpaceballButtonFunc


The `glutSpaceballButtonFunc` function is implemented in
*freeglut* on X11 and Windows only.  On other platforms, function stubs are
provided so that GLUT-based programs can compile and link against
*freeglut* without modification.


The `glutSpaceballButtonFunc` function sets the window's Spaceball button callback. *freeglut* invokes this callback when the user presses/releases one of the Spaceball buttons.


**Usage**


`void glutSpaceballButtonFunc ( void (* callback)( int button, int updown ) );`


Has user-data callback function.


**Description**


The *button* argument may take one of the following defined
constant values:

- `GLUT_SPACEBALL_BUTTON_A` (0x00000001)
- `GLUT_SPACEBALL_BUTTON_B` (0x00000002)
- `GLUT_SPACEBALL_BUTTON_C` (0x00000004)
- `GLUT_SPACEBALL_BUTTON_D` (0x00000008)
- `GLUT_SPACEBALL_BUTTON_E` (0x00000010)


The *updown* argument may take one of the two defined constant
values:

- GLUT_DOWN, GLUT_UP indicating if button is pressed or released.


**Changes From GLUT**


The `GLUT_SPACEBALL_BUTTON_` defines for the button argument of
the callback are not provided by GLUT, but the numerical values returned
are the same.

### 12.18 glutButtonBoxFunc


The `glutDialsFunc` function sets the global dials&buttons box callback. freeglut calls the callback when there is input from the box buttons.


**Usage**


`void glutButtonBoxFunc ( void (* callback)( int button, int updown ) );`


Has user-data callback function.


**Description**


The Dials&Buttons box is an ancient device presenting several pushable
or rotatable buttons, sending the events to the computer via serial
I/O.


See [http://www.reputable.com/sgipix/sgi-dialnbutton1.jpg](http://www.reputable.com/sgipix/sgi-dialnbutton1.jpg)


for instance.

### 12.19 glutDialsFunc


The `glutDialsFunc` function sets the global dials&buttons box callback. freeglut calls the callback when there is input from the box dials.


**Usage**


`void glutDialsFunc ( void (* callback)( int dial, int value ) );`


Has user-data callback function.


**Description**


The Dials&Buttons box is an ancient device presenting several pushable
or rotatable buttons, sending the events to the computer via serial
I/O.


See [http://www.reputable.com/sgipix/sgi-dialnbutton1.jpg](http://www.reputable.com/sgipix/sgi-dialnbutton1.jpg)


for instance.

### 12.20 glutTabletMotionFunc


The `glutTabletMotionFunc` function is not implemented in *
freeglut*, although the library does "answer the mail" to the extent
that a call to the function will not produce an error..


**Usage**


`void glutTabletMotionFunc ( void (* callback)( int x, int y ) );`


Has user-data callback function.


**Description**


The `glutTabletMotionFunc` function
is not implemented in *freeglut*.


**Changes From GLUT**


GLUT implements this function.

### 12.21 glutTabletButtonFunc


The `glutTabletButtonFunc` function is not implemented in *
freeglut*, although the library does "answer the mail" to the extent
that a call to the function will not produce an error..


**Usage**


`void glutTabletButtonFunc ( void (* callback)( int button, int updown, int x, int y ) );`


Has user-data callback function.


**Description**


The `glutTabletButtonFunc` function
is not implemented in *freeglut*.


**Changes From GLUT**


GLUT implements this function.

### 12.22 glutVisibilityFunc, glutWindowStatusFunc


The `glutVisibilityFunc` and the `glutWindowStatusFunc`
functions set the window's visibility and windowStatus callbacks for the
current window. Setting one overwrites the other. *freeglut* calls
these callbacks when the visibility status of a window changes.


**Usage**


`void glutVisibilityFunc ( void( *callback )( int state ));`


`void glutWindowStatusFunc ( void( *callback )( int state ));`


Both functions have user-data callback functions.


**Description**


`glutVisibilityFunc` is deprecated and superseded by the more
informative `glutWindowStatusFunc`.


For `glutWindowStatusFunc`, the state callback parameter is one
of GLUT_HIDDEN, GLUT_FULLY_RETAINED, GLUT_PARTIALLY_RETAINED, or
GLUT_FULLY_COVERED depending on the current window status of the window.
GLUT_HIDDEN means that the window is not shown (often meaning that the
window is iconified). GLUT_FULLY_RETAINED means that the window is fully
retained (no pixels belonging to the window are covered by other
windows). GLUT_PARTIALLY_RETAINED means that the window is partially
retained (some but not all pixels belonging to the window are covered by
other windows). GLUT_FULLY_COVERED means the window is shown but no part
of the window is visible, i.e., until the window's status changes, all
further rendering to the window is discarded.


GLUT considers a window visible if any pixel of the window is visible or
any pixel of any descendant window is visible on the screen.


GLUT applications are encouraged to disable rendering and/or animation
when windows have a status of either GLUT_HIDDEN or
GLUT_FULLY_COVERED.


If the window status callback for a window is disabled and later
re-enabled, the window status of the window is undefined; any change in
window window status will be reported, that is if you disable a window
status callback and re-enable the callback, you are guaranteed the next
window status change will be reported.


Setting the window status callback for a window disables the visibility
callback set for the window (and vice versa). The visibility callback is
set with `glutVisibilityFunc`, and its
state callback parameter is either GLUT_NOT_VISIBLE or GLUT_VISIBLE
depending on the current visibility of the window. GLUT_VISIBLE does not
distinguish a window being totally versus partially visible.
GLUT_NOT_VISIBLE means no part of the window is visible, i.e., until the
window's visibility changes, all further rendering to the window is
discarded.


Not all window managers support such finegrained callback messages or
can even ensure basic correctness. On Windows, there are no
notifications if the visibility status of a window changes and
*freeglut* might be in visible state even if the window is fully
obscured by other windows.


**Changes From GLUT**


None.

## 13. State Setting and Retrieval Functions

### 13.1 glutSetOption


Allows you to set some general state/option variables.


**Usage**


`void glutSetOption ( GLenum eWhat, int value );`


**Description**


Stores the `value` into a state variable named by
`eWhat`.


The following state variables can be set:

- GLUT_ACTION_ON_WINDOW_CLOSE - Controls what happens when a window is
closed by the user or system. GLUT_ACTION_EXIT will immediately exit the
application (default, GLUT's behavior). GLUT_ACTION_GLUTMAINLOOP_RETURNS
will immediately return from the main loop.
GLUT_ACTION_CONTINUE_EXECUTION will contine execution of remaining
windows.
- GLUT_INIT_DISPLAY_MODE - Set the display mode for a new window, see
`glutInitDisplayMode`
- GLUT_INIT_WINDOW_X - Set the initial horizontal position of new
windows.
- GLUT_INIT_WINDOW_Y - Set the initial vertical position of new
windows.
- GLUT_INIT_WINDOW_WIDTH - Set the width of new windows.
- GLUT_INIT_WINDOW_HEIGHT - Set the height of new windows.
- GLUT_RENDERING_CONTEXT - Set to either GLUT_CREATE_NEW_CONTEXT or
GLUT_USE_CURRENT_CONTEXT to indicate whether to share the current OpenGL
rendering context with new windows.
- GLUT_WINDOW_CURSOR - Set the current window's cursor as if by
glutSetCursor().
- GLUT_AUX - Set the number of auxiliary buffers requested for new
windows if GLUT_AUX was set in the displayMode.
- GLUT_MULTISAMPLE - Set the number of samples to request for new
windows if GLUT_MULTISAMPLE was set in the displayMode.
- GLUT_GEOMETRY_VISUALIZE_NORMALS - Set whether [*freeglut*'s geometric object rendering
functions](#GeometricObject) also visualize the object's normals or not.
- GLUT_STROKE_FONT_DRAW_JOIN_DOTS - Set whether join dots are drawn
between line segments when drawing letters of stroke fonts or not.
- GLUT_ALLOW_NEGATIVE_WINDOW_POSITION - Set if negative positions can be
used for window coordinates.


**Changes From GLUT**


This function is not implemented in GLUT.

### 13.2 glutGet


The following state variables may be queried with `glutGet`.
The returned value is an integer.


example:


`int windowLeft = glutGet(GLUT_WINDOW_X);`


These queries are with respect to the current window:

- GLUT_WINDOW_X - X position of the window's client (drawable) area, relative to screen origin. Note that this returns the client area position, not the window frame position used by `glutPositionWindow`. See [freeglut's conventions](#32-conventions)
- GLUT_WINDOW_Y - Y position of the window's client (drawable) area, relative to screen origin. Note that this returns the client area position, not the window frame position used by `glutPositionWindow`. See [freeglut's conventions](#32-conventions)
- GLUT_WINDOW_WIDTH - width of the window's client (drawable) area. See [freeglut's conventions](#32-conventions)
- GLUT_WINDOW_HEIGHT - height of the window's client (drawable) area. See [freeglut's conventions](#32-conventions)
- GLUT_WINDOW_BORDER_WIDTH - window border width
- GLUT_WINDOW_BORDER_HEIGHT - height of non-client area above window,
including both border and caption (if any)
- GLUT_WINDOW_BUFFER_SIZE - number of color (including alpha) or color index bits per pixel
- GLUT_WINDOW_STENCIL_SIZE - number of bits per stencil value
- GLUT_WINDOW_DEPTH_SIZE - number of bits per depth value
- GLUT_WINDOW_RED_SIZE - number of bits per red value
- GLUT_WINDOW_GREEN_SIZE - number of bits per green value
- GLUT_WINDOW_BLUE_SIZE - number of bits per blue value
- GLUT_WINDOW_ALPHA_SIZE - number of bits per alpha value
- GLUT_WINDOW_ACCUM_RED_SIZE - number of red bits in the accumulation buffer
- GLUT_WINDOW_ACCUM_GREEN_SIZE - number of green bits in the accumulation buffer
- GLUT_WINDOW_ACCUM_BLUE_SIZE - number of blue bits in the accumulation buffer
- GLUT_WINDOW_ACCUM_ALPHA_SIZE - number of alpha bits in the accumulation buffer
- GLUT_WINDOW_DOUBLEBUFFER - 1 if the color buffer is double buffered, 0 otherwise
- GLUT_WINDOW_RGBA - 1 if the color buffers are RGB, 0 for color index
- GLUT_WINDOW_PARENT - parent window ID
- GLUT_WINDOW_NUM_CHILDREN - number of child windows
- GLUT_WINDOW_COLORMAP_SIZE - number of entries in the window's colormap
- GLUT_WINDOW_NUM_SAMPLES - number of samples per pixel if using multisampling
- GLUT_WINDOW_STEREO - 1 if the window supports stereo, 0 otherwise
- GLUT_WINDOW_CURSOR - current cursor
- GLUT_WINDOW_FORMAT_ID - on Windows, return the pixel format number of the current window
- GLUT_FULL_SCREEN - 1 if window is currently in fullscreen mode


These queries do not depend on the current window.

- GLUT_SCREEN_WIDTH - width of the screen in pixels
- GLUT_SCREEN_HEIGHT - height of the screen in pixels
- GLUT_SCREEN_WIDTH_MM - width of the screen in millimeters
- GLUT_SCREEN_HEIGHT_MM - height of the screen in millimeters
- GLUT_MENU_NUM_ITEMS - number of items in the current menu
- GLUT_DISPLAY_MODE_POSSIBLE - return 1 if the current display mode is supported, 0 otherwise
- GLUT_INIT_WINDOW_X - X position last set by glutInitWindowPosition
- GLUT_INIT_WINDOW_Y - Y position last set by glutInitWindowPosition
- GLUT_INIT_WINDOW_WIDTH - width last set by glutInitWindowSize
- GLUT_INIT_WINDOW_HEIGHT - height last set by glutInitWindowSize
- GLUT_INIT_DISPLAY_MODE - display mode last set by
glutInitDisplayMode or glutSetOption(GLUT_INIT_DISPLAY_MODE, value)
- GLUT_ELAPSED_TIME - time (in milliseconds) elapsed since glutInit or glutGet(GLUT_ELAPSED_TIME) was first called
- GLUT_INIT_STATE - 1 if *freeglut* has been initialized through
a call to `glutInit`
- GLUT_VERSION - Return value will be X*10000+Y*100+Z where X is the
major version, Y is the minor version and Z is the patch level.
This query is only supported in *freeglut* (version 2.0.0 or later).
- GLUT_ALLOW_NEGATIVE_WINDOW_POSITION - 1 if negative window positions are enabled, 0 otherwise

### 13.3 glutDeviceGet

### 13.4 glutGetModifiers

### 13.5 glutLayerGet

### 13.6 glutExtensionSupported

### 13.7 glutGetProcAddress


`glutGetProcAddress` returns
a pointer to a named GL or *freeglut* function.


**Usage**


`void *glutGetProcAddress ( const char *procName );`


`procName` Name of an OpenGL or GLUT function.


**Description**


`glutGetProcAddress` is useful
for dealing with OpenGL extensions. If an application calls OpenGL extension
functions directly, that application will only link/run with an OpenGL library
that supports the extension. By using a function pointer returned from glutGetProcAddress(),
the application will avoid this hard dependency and be more portable and interoperate
better with various implementations of OpenGL.


Both OpenGL functions and *freeglut*
functions can be queried with this function.


**Changes From GLUT**


GLUT does not include this function.

### 13.8 glutGetModeValues


`glutGetModeValues` returns a dynamically allocated array of integers describing
which values are supported for a given display-mode attribute.


**Usage**

```c
int *glutGetModeValues( GLenum mode, int *size );
```


**Description**


The `mode` parameter selects which capability to query.


The pointer `size` must be non-NULL and will be filled with the number of returned elements.


The caller is responsible for freeing the returned array with `free()`.


Currently supported mode queries:

- `GLUT_AUX` - returns the supported counts of auxiliary buffers.
- `GLUT_MULTISAMPLE` - returns the supported multisample sample counts.


If the queried mode is not supported on the current platform, size is set to
zero and `NULL` is returned.


**Changes From GLUT**


GLUT does not include this function.

## 14. Font Rendering Functions


*freeglut* supports two types of font rendering:  bitmap fonts,
which are rendered using the `glBitmap` function call, and stroke
fonts, which are rendered as sequences of OpenGL line segments. Because
they are rendered as bitmaps, the bitmap fonts tend to render more quickly
than stroke fonts, but they are less flexible in terms of scaling and rendering.
Bitmap font characters are positioned with calls to the `glRasterPos*`
functions while stroke font characters use the OpenGL transformations
to position characters.


It should be noted that *freeglut* fonts are similar but not identical to GLUT fonts.
At the moment, *freeglut* fonts do not support the "`" (backquote) and
"|" (vertical line) characters; in their place it renders asterisks.`


*freeglut* supports the following bitmap fonts:

- `GLUT_BITMAP_8_BY_13` - A variable-width font with every character fitting in a rectangle of 13 pixels high by at most 8 pixels wide.
- `GLUT_BITMAP_9_BY_15` - A variable-width font with every character fitting in a rectangle of 15 pixels high by at most 9 pixels wide.
- `GLUT_BITMAP_TIMES_ROMAN_10` - A 10-point variable-width Times Roman font.
- `GLUT_BITMAP_TIMES_ROMAN_24` - A 24-point variable-width Times Roman font.
- `GLUT_BITMAP_HELVETICA_10` - A 10-point variable-width Helvetica font.
- `GLUT_BITMAP_HELVETICA_12` - A 12-point variable-width Helvetica font.
- `GLUT_BITMAP_HELVETICA_18` - A 18-point variable-width Helvetica font.


*freeglut* calls `glRasterPos4v` to advance the cursor by
the width of a character and to render carriage returns when appropriate.
It does not use any display lists in it rendering in bitmap fonts.


*freeglut* supports
the following stroke fonts:

- `GLUT_STROKE_ROMAN` - A proportionally-spaced Roman Simplex font
- `GLUT_STROKE_MONO_ROMAN` - A fixed-width Roman Simplex font


*freeglut* does not use any display lists in its rendering of stroke
fonts.  It calls `glTranslatef` to advance the cursor by the
width of a character and to render carriage returns when appropriate.

### 14.1 glutBitmapCharacter


The `glutBitmapCharacter` function renders a single bitmapped
character in the *current window* using the specified font.


**Usage**


`void glutBitmapCharacter ( void *font,
int character );`


`font
`The bitmapped font to use in rendering
the character


`character `The ASCII
code of the character to be rendered


**Description**


The `glutBitmapCharacter` function renders the given character in the specified bitmap font.
*freeglut* automatically sets the necessary
pixel unpack storage modes and restores the existing modes when it has finished.
Before the first call to `glutBitMapCharacter` the application
program should call `glRasterPos*` to set the  position of the character
in the window. The `glutBitmapCharacter`  function advances
the cursor position as part of its call to `glBitmap`  and so the
application does not need to call `glRasterPos*` again  for successive
characters on the same line.


**Changes From GLUT**


Nonexistent characters are rendered as
asterisks. The rendering position in *freeglut* is apparently off
from GLUT's position by a few pixels vertically and one or two pixels horizontally.

### 14.2 glutBitmapString


The `glutBitmapString` function renders a string of bitmapped
characters in the *current window* using the specified font.


**Usage**


`void glutBitmapString ( void *font,
char *string );`


`font
`The bitmapped font to use in rendering
the character string


`string `String
of characters to be rendered


**Description**


The `glutBitmapString` function renders the given character
string in the specified bitmap font.
*freeglut* automatically sets the necessary
pixel unpack storage modes and restores the existing modes when it has finished.
Before calling `glutBitMapString` the application program should
call `glRasterPos*` to set the position of the string in the window.
The `glutBitmapString` function handles carriage returns.
Nonexistent characters are rendered as asterisks.


**Changes From GLUT**


GLUT does not include this function.

### 14.3 glutBitmapWidth


The `glutBitmapWidth` function returns the width in pixels of
a single bitmapped character in the specified font.


**Usage**


`int glutBitmapWidth ( void *font,
int character );`


`font
`The bitmapped font to use in calculating
the character width


`character `The ASCII
code of the character


**Description**


The `glutBitmapWidth`
function returns the width of the given character in the specified bitmap
font. Because the font is bitmapped, the width is an exact integer.


**Changes From GLUT**


Nonexistent characters return the width
of an asterisk.

### 14.4 glutBitmapLength


The `glutBitmapLength` function returns the width in pixels of
a string of bitmapped characters in the specified font.


**Usage**


`int glutBitmapLength ( void *font,
char *string );`


`font `The bitmapped
font to use in calculating the character width


`string `String of characters
whose width is to be calculated


**Description**


The `glutBitmapLength` function returns the width in pixels of the given character string in
the specified bitmap font. Because the font is bitmapped, the width
is an exact integer: the return value is identical to the sum of the
character widths returned by a series of calls to `glutBitmapWidth`.
The width of nonexistent characters is counted to be the width of
an asterisk.


If the string contains
one or more carriage returns, *freeglut* calculates the widths in pixels
of the lines separately and returns the largest width.


**Changes From GLUT**


GLUT does not include this function.

### 14.5 glutBitmapHeight


The `glutBitmapHeight` function returns the height in pixels of
the specified font.


**Usage**


`int glutBitmapHeight ( void *font
);`


`font
`The bitmapped font to use in calculating
the character height


**Description**


The `glutBitmapHeight` function returns the height of a character in the specified bitmap font.
Because the font is bitmapped, the height is an exact integer. The fonts
are designed such that all characters have (nominally) the same height.


**Changes From GLUT**


GLUT does not include this function.

### 14.6 glutStrokeCharacter


The `glutStrokeCharacter` function renders a single stroke character
in the *current window* using the specified font.


**Usage**


`void glutStrokeCharacter ( void *font, int character );`


`font`The stroke font to use in rendering the character


`character`The ASCII code of the character to be rendered


**Description**


The `glutStrokeCharacter` function renders the given character in the specified stroke font.
Before the first call to `glutStrokeCharacter` the application program
should call the OpenGL transformation (positioning and scaling) functions
to set the position of the character in the window. The `glutStrokeCharacter`
function advances the cursor position by a call to `glTranslatef`
and so the application does not need to call the OpenGL positioning functions
again for successive characters on the same line.


**Changes From GLUT**


Nonexistent characters are rendered as
asterisks.

### 14.7 glutStrokeString


The `glutStrokeString` function renders a string of characters
in the *current window* using the specified stroke font.


**Usage**


`void glutStrokeString ( void *font,
char *string );`


`font
`The stroke font to use in rendering
the character string


`string `String
of characters to be rendered


**Description**


The `glutStrokeString` function renders the given character string in the specified stroke font.
Before calling `glutStrokeString` the application program should
call the OpenGL transformation (positioning and scaling) functions to set
the position of the string in the window. The `glutStrokeString`
function handles carriage returns. Nonexistent characters are rendered
as asterisks.


**Changes From GLUT**


GLUT does not include this function.

### 14.8 glutStrokeWidth


The `glutStrokeWidth` function returns the width in model units of
a single character in the specified stroke font, rounded to an int.


**Usage**


`int glutStrokeWidth ( void *font,
int character );`


`font
`The stroke font to use in calculating
the character width


`character `The ASCII
code of the character


**Description**


The `glutStrokeWidth`
function returns the width of the given character in the specified stroke
font. Because the font is a stroke font, the width is actually a floating-point
number; the function rounds it to the nearest integer for the return value.


**Changes From GLUT**


Nonexistent characters return the width
of an asterisk.

### 14.9 glutStrokeWidthf


The `glutStrokeWidthf` function returns the width in model units of
a single character in the specified stroke font.


**Usage**


`GLfloat glutStrokeWidthf ( void *font,
int character );`


`font
`The stroke font to use in calculating
the character width


`character `The ASCII
code of the character


**Description**


The `glutStrokeWidthf`
function returns the width of the given character in the specified stroke
font. Function was included in an unreleased GLUT 3.8.


**Changes From GLUT**


Nonexistent characters return the width
of an asterisk.

### 14.10 glutStrokeLength


The `glutStrokeLength` function returns the width in model units of
a string of characters in the specified stroke font, rounded to an int.


**Usage**


`int glutStrokeLength ( void *font,
char *string );`


`font `The stroke
font to use in calculating the character width


`string `String of characters
whose width is to be calculated


**Description**


The `glutStrokeLength` function returns the width in model units of the given character string in
the specified stroke font. Because the font is a stroke font, the width
of an individual character is a floating-point number. *freeglut*
adds the floating-point widths and rounds the final result to return the
integer value. Thus the return value may differ from the sum of the
character widths returned by a series of calls to `glutStrokeWidth`.
The width of nonexistent characters is counted to be the width
of an asterisk.


If the string contains
one or more carriage returns, *freeglut* calculates the widths in pixels
of the lines separately and returns the largest width.

### 14.11 glutStrokeLengthf


The `glutStrokeLengthf` function returns the width in model units of
a string of characters in the specified stroke font.


**Usage**


`GLfloat glutStrokeLengthf ( void *font,
char *string );`


`font `The stroke
font to use in calculating the character width


`string `String of characters
whose width is to be calculated


**Description**


The `glutStrokeLengthf` function returns the width in model units of the given character string in
the specified stroke font. The return value is equal to the sum of the
character widths returned by a series of calls to
`glutStrokeWidthf`. Function was included in an unreleased GLUT 3.8.
The width of nonexistent characters is counted to be the width
of an asterisk.


If the string contains
one or more carriage returns, *freeglut* calculates the widths in pixels
of the lines separately and returns the largest width.

### 14.12 glutStrokeHeight


The `glutStrokeHeight` function returns the height in pixels of
the specified font.


**Usage**


`GLfloat glutStrokeHeight ( void *font
);`


`font
`The stroke font to use in calculating
the character height


**Description**


The `glutStrokeHeight` function returns the height of a character in the specified stroke font.
The application programmer should note that, unlike the other *freeglut*
font functions, this one returns a floating-point number.  The fonts
are designed such that all characters have (nominally) the same height.


**Changes From GLUT**


GLUT does not include this function.

## 15. Geometric Object Rendering Functions


*freeglut* includes twenty two routines for generating
easily-recognizable 3-d geometric objects.  These routines are
effectively the same ones that are included in the GLUT library, and
reflect the functionality available in the *aux* toolkit described
in the *OpenGL Programmer's Guide*. They are included to allow
programmers to create with a single line of code a three-dimensional
object which can be used to test a variety of OpenGL functionality.
None of the routines generates a display list for the object which it
draws.  The functions generate normals appropriate for lighting but,
except for the teapot functions, do not generate texture coordinates. Do
note that depth testing (GL_LESS) should be enabled for the correct
drawing of the nonconvex objects, i.e., the glutTorus,
glutSierpinskiSponge, glutTeapot, glutTeacup and glutTeaspoon.


Also see the `GLUT_GEOMETRY_VISUALIZE_NORMALS` option that can be
set with `glutSetOption`. Lastly, see
`glutSetVertexAttribCoord3`, `glutSetVertexAttribNormal`,
and `glutSetVertexAttribTexCoord2` if you wish to use these
objects with shaders.

### 15.1  glutWireSphere, glutSolidSphere


The `glutWireSphere` and `glutSolidSphere` functions
draw a wireframe and solid sphere respectively.


**Definition**

```c
void glutWireSphere (double dRadius, GLint slices, GLint stacks);
void glutSolidSphere(double dRadius, GLint slices, GLint stacks);
```


**Arguments**


`dRadius`  The desired radius of the sphere


`slices`   The desired number of slices (divisions
in the longitudinal direction) in the sphere


`stacks`   The desired number of stacks (divisions
in the latitudinal direction) in the sphere.  The number of points in
this direction, including the north and south poles, is `stacks+1`


**Description**


The `glutWireSphere` and `
glutSolidSphere` functions render a sphere centered at the origin
of the modeling coordinate system. The north and south poles of the
sphere are on the positive and negative Z-axes respectively and the prime
meridian crosses the positive X-axis.


**Changes From GLUT**


None that we know of.

### 15.2 glutWireTorus, glutSolidTorus


The `glutWireTorus` and `glutSolidTorus` functions draw
a wireframe and solid torus (donut shape) respectively.


**Definition**

```c
void glutWireTorus (double dInnerRadius, double dOuterRadius, GLint nSides, GLint nRings);
void glutSolidTorus(double dInnerRadius, double dOuterRadius, GLint nSides, GLint nRings);
```


**Arguments**


`dInnerRadius`  The radius of the tube.


`dOuterRadius`  The distance from the center of the
Torus to the center of the tube.


`nSides`        The desired number of segments in a
single outer circle of the torus


`nRings`        The desired number of outer circles
around the origin of the torus


**Description**


The `glutWireTorus` and `
glutSolidTorus` functions render a torus centered at the origin of
the modeling coordinate system. The torus is circularly symmetric about
the Z-axis and starts at the positive X-axis.


**Changes From GLUT**


None that we know of.

### 15.3 glutWireCylinder, glutSolidCylinder


The `glutWireCylinder` and `glutSolidCylinder` functions draw
a wireframe and solid cone respectively.


**Definition**

```c
void glutWireCylinder (double base, double height, GLint slices, GLint stacks);
void glutSolidCylinder(double base, double height, GLint slices, GLint stacks);
```


**Arguments**


`radius`  The desired radius of the cylinder


`height`  The desired height of the cylinder


`slices`  The desired number of slices around
the cylinder


`stacks`  The desired number of segments between
the base and the top of the cylinder (the number of points, including
the tip, is `stacks + 1`)

### 15.4 glutWireCone, glutSolidCone


The `glutWireCone` and `glutSolidCone` functions draw
a wireframe and solid cone respectively.


**Definition**

```c
void glutWireCone (double base, double height, GLint slices, GLint stacks);
void glutSolidCone(double base, double height, GLint slices, GLint stacks);
```


**Arguments**


`base`    The desired radius of the base of the cone


`height`  The desired height of the cone


`slices`  The desired number of slices around
the base of the cone


`stacks`  The desired number of segments between
the base and the tip of the cone (the number of points, including the tip,
is `stacks + 1`)


**Description**


The `glutWireCone` and `
glutSolidCone` functions render a right circular cone with a base
centered at the origin and in the X-Y plane and its tip on the positive Z-axis.
The wire cone is rendered with triangular elements.


**Changes From GLUT**


None that we know of.

### 15.5 glutWireCube, glutSolidCube


The `glutWireCube` and `glutSolidCube` functions draw a
wireframe and solid cube respectively.


**Definition**

```c
void glutWireCube (double dSize);
void glutSolidCube(double dSize);
```


**Arguments**


`dSize`  The desired length of an edge of the cube


**Description**


The `glutWireCube` and `
glutSolidCube` functions render a cube of the desired size, centered
at the origin. Its faces are normal to the coordinate directions.


**Changes From GLUT**


None that we know of.

### 15.6 glutWireTetrahedron, glutSolidTetrahedron


The `glutWireTetrahedron` and `glutSolidTetrahedron`
functions draw a wireframe and solid tetrahedron (four-sided Platonic solid)
respectively.


**Definition**

```c
void glutWireTetrahedron (void);
void glutSolidTetrahedron(void);
```


**Description**


The `glutWireTetrahedron` and
`glutSolidTetrahedron` functions render a tetrahedron whose corners
are each a distance of one from the origin.  The length of each side
is 2/3 sqrt(6).  One corner is on the positive X-axis and another is
in the X-Y plane with a positive Y-coordinate.


**Changes From GLUT**


None that we know of.

### 15.7 glutWireOctahedron, glutSolidOctahedron


The `glutWireOctahedron` and `glutSolidOctahedron` functions
draw a wireframe and solid octahedron (eight-sided Platonic solid) respectively.


**Definition**

```c
void glutWireOctahedron (void);
void glutSolidOctahedron(void);
```


**Description**


The `glutWireOctahedron` and
`glutSolidOctahedron` functions render an octahedron whose corners
are each a distance of one from the origin.  The length of each side
is sqrt(2).  The corners are on the positive and negative coordinate
axes.


**Changes From GLUT**


None that we know of.

### 15.8 glutWireDodecahedron, glutSolidDodecahedron


The `glutWireDodecahedron` and `glutSolidDodecahedron`
functions draw a wireframe and solid dodecahedron (twelve-sided Platonic
solid) respectively.


**Definition**

```c
void glutWireDodecahedron (void);
void glutSolidDodecahedron(void);
```


**Description**


The `glutWireDodecahedron` and
`glutSolidDodecahedron` functions render a dodecahedron whose corners
are each a distance of sqrt(3) from the origin.  The length of each
side is sqrt(5)-1.  There are twenty corners; interestingly enough,
eight of them coincide with the corners of a cube with sizes of length 2.


**Changes From GLUT**


None that we know of.

### 15.9  glutWireIcosahedron, glutSolidIcosahedron


The `glutWireIcosahedron` and `glutSolidIcosahedron`
functions draw a wireframe and solid icosahedron (twenty-sided Platonic solid)
respectively.


**Definition**

```c
void glutWireIcosahedron (void);
void glutSolidIcosahedron(void);
```


**Description**


The `glutWireIcosahedron` and
`glutSolidIcosahedron` functions render an icosahedron whose corners
are each a unit distance from the origin. The length of each side is
slightly greater than one.  Two of the corners lie on the positive and
negative X-axes.


**Changes From GLUT**


None that we know of.

### 15.10 glutWireRhombicDodecahedron, glutSolidRhombicDodecahedron


The `glutWireRhombicDodecahedron` and `glutSolidRhombicDodecahedron`
functions draw a wireframe and solid rhombic dodecahedron (twelve-sided
semi-regular solid) respectively.


**Definition**

```c
void glutWireRhombicDodecahedron (void);
void glutSolidRhombicDodecahedron(void);
```


**Description**


The `glutWireRhombicDodecahedron` and `glutSolidRhombicDodecahedron`
functions render a rhombic
dodecahedron whose corners are at most a distance of one from the origin.
The rhombic dodecahedron has faces which are identical rhombuses (rhombi?)
but which have some vertices at which three faces meet and some vertices at
which four faces meet.  The length of each side is sqrt(3)/2.  Vertices
at which four faces meet are found at (0, 0, +/- 1) and (+/- sqrt(2)/2,
+/- sqrt(2)/2, 0).


**Changes From GLUT**


GLUT does not include these functions.

### 15.11 glutWireTeapot, glutSolidTeapot, glutWireTeacup,


glutSolidTeacup, glutWireTeaspoon, glutSolidTeaspoon


The `glutWireTeapot` and `glutSolidTeapot` functions draw
a wireframe and solid teapot respectively, the `glutWireTeacup`
and `glutSolidTeacup` functions a wireframe and solid teacup, and
the `glutWireTeaspoon` and `glutSolidTeaspoon` functions a
wireframe and solid teaspoon.


**Definition**

```c
void glutWireTeapot   (double dSize);
void glutSolidTeapot  (double dSize);
void glutWireTeacup   (double dSize);
void glutSolidTeacup  (double dSize);
void glutWireTeaspoon (double dSize);
void glutSolidTeaspoon(double dSize);
```


**Arguments**


`dSize`  The desired size of the teapot, teacup and
teaspoon - relative to a "standard" size


**Description**


The `glutWireTeapot` and ` glutSolidTeapot` functions
render a teapot of the desired size, centered at the origin. This is the
famous teapot created by Martin Newell. The other functions render the
teacup and teaspoon he used in the table scene figure in his PhD thesis.
Vertex data retrieved from: [ftp://ftp.funet.fi/pub/sci/graphics/packages/objects/teasetorig.gz](ftp://ftp.funet.fi/pub/sci/graphics/packages/objects/teasetorig.gz).


**Bugs**


OpenGL's default `glFrontFace` state assumes that front facing
polygons (for the purpose of face culling) have vertices that wind
counter clockwise when projected into window space. This teapot, teacup
and teaspoon are rendered with their front facing polygon vertices
winding clockwise. For OpenGL's default back face culling to work, you
should use:

```c
  glFrontFace(GL_CW);
  glutSolidTeapot(size);
  glFrontFace(GL_CCW);
```


This bug reflect issues in the original teaset's vertex data
(and is thus present in GLUT too).


**Changes From GLUT**


GLUT only has the teapot and misses the rest of the teaset.

### 15.12 glutSetVertexAttribCoord3, glutSetVertexAttribNormal, glutSetVertexAttribTexCoord2


To draw shapes with shaders (OpenGL 2 and later), one need to upload
vertices and associated normal vectors and texture coordinates to
vertex attributes of your shaders. Use these functions to set the
indices (addresses) of the vertex attributes in your currently active
shaders before calling the above geometry functions, and *freeglut*
will upload the object geometry there. Texture coordinates are only
generated for the teapot, teacup and teaspoon.


**Definition**

```c
void glutSetVertexAttribCoord3   (GLint attrib);
void glutSetVertexAttribNormal   (GLint attrib);
void glutSetVertexAttribTexCoord2(GLint attrib);
```


**Arguments**


`attrib`  The index (address) of the vertex
attribute


**Changes From GLUT**


GLUT does not include these functions.

## 16. Game Mode Functions

### 16.1 glutGameModeString


Specify the display mode that should be entered when GameMode is
entered. Default is the current display mode of the monitor on which the
GameMode screen will be opened.


**Usage**


A string is passed to this function that specifies a combination of
resolution, pixel depth (ignored on Linux) and refresh rate. Valid
formats are:

- WxH:D@R
- WxH:D
- WxH@R
- WxH
- :D@R
- :D
- @R


Where W, H, D and R are placeholders for horizontal resolution, vertical
resolution, pixel depth and refresh rate respectively, as integers. Note
the "x", ":" and "@" characters.

### 16.2 glutEnterGameMode, glutLeaveGameMode


Attempt to change to the requested display mode and open the GameMode
window, or close the GameMode window and return to the original display
mode.
For multi-monitor display setups, *freeglut* can be told on which
monitor the gamemode window should be opened by providing the
`-display` command line option to `glutInit`.

### 16.3 glutGameModeGet


The following state variables may be queried with `glutGet`.
The returned value is an integer.


example:


`int windowLeft = glutGet(GLUT_WINDOW_X);`

- GLUT_GAME_MODE_ACTIVE - 1 if currently in GameMode
- GLUT_GAME_MODE_DISPLAY_CHANGED - 1 if currently in GameMode
- GLUT_GAME_MODE_POSSIBLE - 1 if display mode requested with
`glutGameModeString` is possible


These queries return information about the current display mode if in
GameMode, or about the requested display mode __before__ entering
GameMode:

- GLUT_GAME_MODE_WIDTH - (requested) width of GameMode window
- GLUT_GAME_MODE_HEIGHT - (requested) height of GameMode window
- GLUT_GAME_MODE_PIXEL_DEPTH - (requested) pixel depth of GameMode window
- GLUT_GAME_MODE_REFRESH_RATE - (requested) refresh rate of GameMode window

## 17. Video Resize Functions


These functions are not implemented in *freeglut*.

### 17.1 glutVideoResizeGet

### 17.2 glutSetupVideoResizing,


glutStopVideoResizing

### 17.3 glutVideoResize

### 17.4 glutVideoPan

## 18. Color Map Functions

### 18.1 glutSetColor, glutGetColor

### 18.2 glutCopyColormap

## 19. MultiTouch Functions


MultiTouch callbacks are used to handle environments with multiple
inputs, such as a multi-touch screen, multi-touch touchpad, or
multiple mouses.

### 19.1 glutMultiEntryFunc &larr; id, GLUT_ENTERED|GLUT_LEFT

### 19.2 glutMultiButtonFunc &larr; id, x, y, button, GLUT_DOWN|GLUT_UP

### 19.3 glutMultiMotionFunc &larr; id, x, y

### 19.4 glutMultiPassiveFunc &larr; id, x, y


These functions work like their non-multi variants, with an additional
'deviceid' parameter describing the current input device (mouse or
finger).


Exception: in MultiButtonFunc, the order of callback parameters is
different (x,y,button,state instead of button,state,x,y).


Currently, under X11, the non-multi callback variants are also called
on X11 for each event.


Currently, under windows, the first (oldest) touch point also controls
the mouse cursor, which triggers the non-multi callbacks as
usual.


All these functions have user-data callback functions.


Limitation: currently on the cursor id is provided.  It may be
desirable to get the device id as well in the following situations:

- Support a 2-fingers gesture such as pinch-to-zoom on the
touchscreen, while the mouse pointer or touchpad is performing an
unrelated action, not part of the touchscreen gesture (may not be
possible on some platforms).
- Avoid touch id conflicts between touchscreen and
touchpad/gamepad on Android; a possible work-around is to shift the
device id in the touch id.


Since this extra support comes at the cost of extra complexity, we're
[considering](http://sourceforge.net/mailarchive/forum.php?thread_name=20120518071314.GA28061%40perso.beuc.net&forum_name=freeglut-developer)
whether/how to implement it.

## 20. Mobile Functions


These new callbacks were added:

- `glutInitContextFunc ← void` : called when the context
is initialized or re-initialized (e.g. after a pause). Has user-data callback
function.
- `glutAppStatusFunc ← event` : called when the
application's status changes, with event identifying the state entered. Has
user-data callback function.
Possible states:
  - application goes on a pause (or a stop) → GLUT_APPSTATUS_PAUSE
  - application comes back from a pause → GLUT_APPSTATUS_RESUME. Is
called after the `glutInitContextFunc` callback.


Supported mobile platforms

- Android support is further described at
the [Android page](android.php).
- BlackBerry 10/BlackBerry PlayBook

## 21. Miscellaneous Functions

### 21.1 glutSetKeyRepeat, glutIgnoreKeyRepeat


The `glutSetKeyRepeat` and `glutIgnoreKeyRepeat` functions
set whether repeated key presses (generated by keeping a key depressed)
are passed on to the keyboard callbacks. `glutSetKeyRepeat`
allows to globally switch off key repeat, while
`glutIgnoreKeyRepeat` offers control over this behavior on a
per-window basis.


**Definition**

```c
void glutSetKeyRepeat   (int repeatMode);
void glutIgnoreKeyRepeat(int ignore);
```


**Arguments**


`glutSetKeyRepeat's repeatMode`
GLUT_KEY_REPEAT_OFF to globally switch key repeat off, or
GLUT_KEY_REPEAT_ON and GLUT_KEY_REPEAT_DEFAULT to globally switch key
repeat on.


`glutIgnoreKeyRepeat's ignore`    if non-zero, key
repeat is switched off for the current window.


**Notes**


If key repeat is globally switched off through
`glutSetKeyRepeat`, it cannot be reenabled on a per-window basis
with `glutIgnoreKeyRepeat`. If you want per-window control of key
repeat, set `glutSetKeyRepeat` to `GLUT_KEY_REPEAT_ON` and
use `glutIgnoreKeyRepeat(GL_TRUE)` to switch off key repeat for
the windows for which you don't want it.


**Changes From GLUT**


Nate Robbins' port of GLUT to win32 did not implement
`glutSetKeyRepeat`, but *freeglut*'s behavior should conform on all
platforms to GLUT's behavior on X11.

### 21.2 glutForceJoystickFunc

### 21.3 glutReportErrors

## 22. Usage Notes


The following environment variables
are recognized by *freeglut*:

- DISPLAY - specifies a display name.
- GLUT_FPS - specifies a time interval
(in milliseconds) for reporting framerate messages to stderr.  For example,
if FREEGLUT_FPS is set to 5000, every 5 seconds a message will be printed
to stderr showing the current frame rate.  The frame rate is measured by counting
the number of times glutSwapBuffers() is called over the time interval.


Furthermore, on windows, there is a resource file identifier GLUT_ICON
that you can specify for your executable file. It specifies the icon
that goes in the upper left-hand corner of the *freeglut* windows.
Your application's resource file should contain the line:


`GLUT_ICON   ICON    DISCARDABLE     "icon.ico"`
where
icon.ico is the filename of your icon. The One demo includes such an
icon as an example.

### 21.1 Implementation Notes

### 21.2 GLUT State

### 21.3 "freeglut.h" Header File


Application programmers who are porting their GLUT programs to *freeglut* may continue
to include `<GL/glut.h>` in their programs.
Programs which use the *freeglut*-specific extensions to GLUT should include
`<GL/freeglut.h>`.  One possible arrangement is as follows:

```c
#ifdef FREEGLUT #include <GL/freeglut.h> #else #include <GL/glut.h> #endif 
```


It was initially planned to
define `FREEGLUT_VERSION_2_0`, `FREEGLUT_VERSION_2_1`, `FREEGLUT_VERSION_2_2`,
etc., but this was only done for `FREEGLUT_VERSION_2_0`.
This constant still exist in current freeglut releases but is
deprecated.


The *freeglut* version can be queried at runtime by calling
glutGet(GLUT_VERSION).
The result will be X*10000+Y*100+Z where X is the major version, Y is the
minor version and Z is the patch level.


This may be used as follows:

```c
if (glutGet(GLUT_VERSION) < 20001) {     printf("Sorry, you need freeglut version 2.0.1 or later to run this program.\n");
    exit(1);
} 
```

### 21.4 References

### 21.5 Index

