# Table of Contents

1. [Introduction](#introduction)

2. [Background](#background)

	- [Design Philosophy](#design-philosophy)
	- [Conventions](#conventions)
		- [Window placement and size](#window-placement-and-size)
		- [User-data callbacks](#user-data-callbacks)
	- [Terminology](#terminology)
	- [Differences from GLUT 3.7](#differences-from-glut-37)
		- [glutMainLoop Behaviour](#glutmainloop-behaviour)
		- [Action on Window Closure](#action-on-window-closure)
		- [Fullscreen windows](#fullscreen-windows)
		- [Changes to Callbacks](#changes-to-callbacks)
		- [String Rendering](#string-rendering)
		- [Geometry Rendering](#geometry-rendering)
		- [Extension Function Queries](#extension-function-queries)

3. [Initialization Functions](#initialization-functions)

	- [glutInit]()
	- [glutInitWindowPosition]()
	- [glutInitWindowSize]()
	- [glutInitDisplayMode]()
	- [glutInitDisplayString]()
	- [glutInitErrorFunc [fg]]()
	- [glutInitWarningFunc [fg]]()

4. [Event Processing Functions]()

	- [glutMainLoop]()
	- [glutMainLoopEvent [fg]]()
	- [glutLeaveMainLoop [fg]]()

5. [Window Functions]()

	- [glutCreateWindow]()
	- [glutCreateSubwindow]()
	- [glutDestroyWindow]()
	- [glutSetWindow]()
	- [glutGetWindow]()
	- [glutSetWindowTitle]()
	- [glutSetIconTitle]()
	- [glutReshapeWindow]()
	- [glutPositionWindow]()
	- [glutShowWindow]()
	- [glutHideWindow]()
	- [glutIconifyWindow]()
	- [glutPushWindow]()
	- [glutPopWindow]()
	- [glutFullScreen]()
	- [glutLeaveFullScreen [fg]]()
	- [glutFullScreenToggle [fg]]()

6. [Display Functions]()

	- [glutPostRedisplay]()
	- [glutPostWindowRedisplay]()
	- [glutSwapBuffers]()

7. [Mouse Cursor Functions]()

	- [glutSetCursor]()
	- [glutWarpPointer]()

8. [Overlay Functions]()

	- [glutEstablishOverlay]()
	- [glutRemoveOverlay]()
	- [glutUseLayer]()
	- [glutPostOverlayRedisplay]()
	- [glutPostWindowOverlayRedisplay]()
	- [glutShowOverlay]()
	- [glutHideOverlay]()

9. [Menu Functions]()

	- [glutCreateMenu]()
	- [glutDestroyMenu]()
	- [glutGetMenu]()
	- [glutSetMenu]()
	- [glutAddMenuEntry]()
	- [glutAddSubMenu]()
	- [glutChangeToMenuEntry]()
	- [glutChangeToSubMenu]()
	- [glutSetMenuFont [fg]]()
	- [glutRemoveMenuItem]()
	- [glutAttachMenu]()
	- [glutDetachMenu]()
	- [glutMenuDestroyFunc [fg]]()

10. [Global Callback Registration Functions]()

	- [glutTimerFunc]()
	- [glutIdleFunc]()
	- [glutMenuStatusFunc]()
	- [glutMenuStateFunc]()

11. [Window-Specific Callback Registration Functions]()

	- [glutDisplayFunc]()
	- [glutOverlayDisplayFunc]()
	- [glutReshapeFunc]()
	- [glutPositionFunc [fg]]()
	- [glutCloseFunc [fg]]()
	- [glutKeyboardFunc]()
	- [glutSpecialFunc]()
	- [glutKeyboardUpFunc]()
	- [glutSpecialUpFunc]()
	- [glutMotionFunc]()
	- [glutPassiveMotionFunc]()
	- [glutMouseFunc]()
	- [glutMouseWheelFunc [fg]]()
	- [glutEntryFunc]()
	- [glutJoystickFunc]()
	- [glutSpaceballMotionFunc]()
	- [glutSpaceballRotateFunc]()
	- [glutSpaceballButtonFunc]()
	- [glutButtonBoxFunc]()
	- [glutDialsFunc]()
	- [glutTabletMotionFunc]()
	- [glutTabletButtonFunc]()
	- [glutVisibilityFunc]()
	- [glutWindowStatusFunc]()

12. [State Setting and Retrieval Functions]()

	- [glutSetOption [fg]]()
	- [glutGet]()
	- [glutDeviceGet]()
	- [glutGetModifiers]()
	- [glutLayerGet]()
	- [glutExtensionSupported]()
	- [glutGetProcAddress [fg]]()

13. [Font Rendering Functions]()

	- [glutBitmapCharacter]()
	- [glutBitmapString [fg]]()
	- [glutBitmapWidth]()
	- [glutBitmapLength]()
	- [glutBitmapHeight [fg]]()
	- [glutStrokeCharacter]()
	- [glutStrokeString [fg]]()
	- [glutStrokeWidth]()
	- [glutStrokeWidthf]()
	- [glutStrokeLength]()
	- [glutStrokeLengthf]()
	- [glutStrokeHeight [fg]]()

14. [Geometric Object Rendering Functions]()

	- [glutWireSphere]()
	- [glutSolidSphere]()
	- [glutWireTorus]()
	- [glutSolidTorus]()
	- [glutWireCone]()
	- [glutSolidCone]()
	- [glutWireCube]()
	- [glutSolidCube]()
	- [glutWireTetrahedron]()
	- [glutSolidTetrahedron]()
	- [glutWireOctahedron]()
	- [glutSolidOctahedron]()
	- [glutWireDodecahedron]()
	- [glutSolidDodecahedron]()
	- [glutWireIcosahedron]()
	- [glutSolidIcosahedron]()
	- [glutWireRhombicDodecahedron [fg]]()
	- [glutSolidRhombicDodecahedron [fg]]()
	- [glutWireTeapot]()
	- [glutSolidTeapot]()
	- [glutWireTeacup [fg]]()
	- [glutSolidTeacup [fg]]()
	- [glutWireTeaspoon [fg]]()
	- [glutSolidTeaspoon [fg]]()
	- [glutSetVertexAttribCoord3 [fg]]()
	- [glutSetVertexAttribNormal [fg]]()
	- [glutSetVertexAttribTexCoord2 [fg]]()

15. [Game Mode Functions]()

	- [glutGameModeString]()
	- [glutEnterGameMode]()
	- [glutLeaveGameMode]()
	- [glutGameModeGet]()

16. [Video Resize Functions]()

	- [glutVideoResizeGet]()
	- [glutSetupVideoResizing]()
	- [glutStopVideoResizing]()
	- [glutVideoResize]()
	- [glutVideoPan]()

17. [Color Map Functions]()

	- [glutSetColor]()
	- [glutGetColor]()
	- [glutCopyColormap]()

18. [MultiTouch Functions]()

	- [glutMultiEntryFunc [fg]]()
	- [glutMultiButtonFunc [fg]]()
	- [glutMultiMotionFunc [fg]]()
	- [glutMultiPassiveFunc [fg]]()

19. [Mobile functions]()

	- [glutInitContextFunc [fg]]()
	- [glutAppStatusFunc [fg]]()

20. [Miscellaneous Functions]()

	- [glutSetKeyRepeat]()
	- [glutIgnoreKeyRepeat]()
	- [glutForceJoystickFunc]()
	- [glutReportErrors]()

21. [Usage Notes]()

	- [Implementation Notes]()
	- [GLUT State]()
	- ["freeglut.h" Header File]()
	- [References]()
	- [Index]()

22. [Unsorted Functions]()

---

# Introduction

The Markdown document is meant to serve as a FreeGLUT programming reference guide.
This document focuses on the following goals:

- Act as a complete API function reference guide
- Instruct the user how the API is meant to be used
- Provide additional information and trivia

Keep in mind that any and all functions in the [Table of Contents](#table-of-contents)
that contain "[fg]" are extended functionality that is not part of GLUT.
This will, of course, be mentioned in the API reference for each function,
because who reads introductions!

# Background

The OpenGL programming world owes a tremendous debt to Mr. Mark J. Kilgard
for writing the OpenGL Utility Toolkit, or GLUT. The GLUT library of functions
allows an application programmer to create, control, and manipulate windows
independent of what operating system the program is running on. By hiding the
dependency on the operating system from the application programmer, he allowed
people to write truly portable OpenGL applications.

Mr. Kilgard copyrighted his library and gave it a rather unusual license.
Under his license, people are allowed freely to copy and distribute
the libraries and the source code, but they are not allowed to modify it.
For a long time this did not matter because the GLUT library worked so well
and because Mr. Kilgard was releasing updates on a regular basis. But with
the passage of time, people started wanting some slightly different behaviours
in their windowing system. When Mr. Kilgard stopped supporting the GLUT library
in 1999, having moved on to bigger and better things, this started to become a problem.

In December 1999, Mr. Pawel Olzsta started work on an open-source clone of the GLUT library.
This open-source clone, which does not use any of the GLUT source code, has evolved into
the present FreeGLUT library. This documentation specifies the application program interface
to the FreeGLUT library.

## Design Philosophy

TODO

## Conventions

In developing the FreeGLUT library, we have taken careful steps to ensure consistency
in function operation across the board, in such a manner as to maintain compatibility
with GLUT's behavior whenever possible. In this section some of the important conventions of
FreeGLUT, and their compatibility with GLUT, are made explicit.

### Window placement and size

There is considerable confusion about the "right thing to do" concerning window size and position.
GLUT itself is not consistent between Windows and UNIX/X11; since platform independence
is a virtue for FreeGLUT, we decided to break with GLUT's behaviour. Under UNIX/X11,
it is apparently not possible to get the window border sizes in order to subtract them off
the window's initial position until some time after the window has been created.
Therefore we decided on the following behavior, both under Windows and under UNIX/X11:

- When you create a window with position (x,y) and size (w,h), the upper left hand corner of the
  outside of the window (the non-client area) is at (x,y) and the size of the drawable (client)
  area is (w,h). The coordinates taken by `glutInitPosition` and `glutPositionWindow`, as well as the
  coordinates provided by FreeGLUT when it calls the `glutPositionFunc` callback, specify the
  top-left of the non-client area of the window. By default only positive-signed coordinates are
  supported. If `GLUT_ALLOW_NEGATIVE_WINDOW_POSITION` is enabled, then negative coordinates are
  supported. An exception for `glutPositionWindow` exists as it's always supported negative window coordinates.

- When you query the size and position of the window using glutGet, FreeGLUT will return
  the size of the drawable area, the (w,h), that you specified when you created the window
  and the coordinates of the upper left hand corner of the drawable (client) area which is
  NOT the (x,y) position of the window you specified when you created it.

### User-data callbacks

GLUT was created as a tool to help teach OpenGL programming. To simplify development,
callbacks were used for handling display, input, and other events. But at the time it was developed,
the purpose, or for some other unknown reason, the callbacks lacked any user-provided data argument.
This has caused considerable difficulties for any significantly advanced usage of GLUT, and now FreeGLUT.
This has prevented any attempt to wrap FreeGLUT in a C++ wrapper, make per-window,
per-callback data structure, and potentially made it undesirable to modern C developers
who tend to be well versed in "don't use globals". To combat these complaints and issues,
many callbacks (with some deprecated callbacks excluded) support user-data callbacks
provided through additional functions provided in FreeGLUT.
All callbacks that support user-data callbacks are marked as such.

The general rule to follow is to take the FreeGLUT callback function and append "Ucall"
to the end of the function, add an additional `void*` argument to the end of the argument list
of both the FreeGLUT function and the callback function.
This will pass the user-data to the callback when it's invoked.

Examples include:

```c
void glutPositionFunc ( void (* func)( int x, int y ) );
void glutPositionFuncUcall ( void (* func)( int x, int y, void* user_data ), void* user_data );

void glutKeyboardUpFunc ( void (* func)( unsigned char key, int x, int y ) );
void glutKeyboardUpFuncUcall ( void (* func)( unsigned char key, int x, int y, void* user_data ), void* user_data );
```

## Terminology

TODO

## Differences from GLUT 3.7

Since the FreeGLUT library was developed in order to update GLUT, it is natural
that there will be some differences between the two.
Each function in the API notes any differences between the GLUT and the FreeGLUT function behaviours.
The important ones are summarized here.

### glutMainLoop Behaviour

One of the most common complaints about the GLUT library was that once an application
called `glutMainLoop`, it never got control back. There was no way for an application
to loop in GLUT for a while, possibly as a subloop while a specific window was open,
and then return to the calling function. A new function, `glutMainLoopEvent`, has been
added to allow this functionality. Another function, `glutLeaveMainLoop`, has also been
added to allow the application to tell FreeGLUT to clean up and close down.

### Action on Window Closure

Another difficulty with GLUT, especially with multiple-window programs, is that
if the user clicks on the "X" in the window header the application exits immediately.
The application programmer can now set an option, `GLUT_ACTION_ON_WINDOW_CLOSE`,
to specify whether `glutMainLoop` should continue execution, whether FreeGLUT should
return control to the main program, or whether FreeGLUT should simply exit (the default).

### Fullscreen windows

Functions to leave fullscreen window mode (`glutLeaveFullScreen`)
or to toggle between fullscreen and normal window mode (`glutFullScreenToggle`)
have been added.

### Changes to Callbacks

Several new callbacks have been added and several callbacks which were specific to Silicon Graphics
hardware have not been implemented. Most or all of the new callbacks are listed in the GLUT Version 4
"glut.h" header file but did not make it into the documentation. The new callbacks consist of
regular and special key release callbacks, a joystick callback, a menu state callback
(with one argument, distinct from the menu status callback which has three arguments),
a window status callback (also with one argument), and a window position callback.
Unsupported callbacks are the two Tablet callbacks. If the user has a need for an unsupported callback
he should contact the FreeGLUT development team.

### String Rendering

New functions have been added to render full character strings (including carriage returns),
rather than rendering one character at a time. More functions return the widths of character strings
and the font heights, in pixels for bitmapped fonts and in OpenGL units for the stroke fonts.

### Geometry Rendering

Two functions have been added to render a wireframe and a solid rhombic dodecahedron.

Furthermore, solid and wireframe versions of the original teacup and teaspoon that accompanied the famous Newell teapot.

As these geometry functions are often used for teaching purposes,
`glutSetOption(GLUT_GEOMETRY_VISUALIZE_NORMALS, true/false)` can now be used to
visualize the normal vectors for each vertex.

Lastly, to support drawing these objects with shaders,
three functions have been added with which users can
provide the locations of the vertex position, normal, and texture coordinates attributes:
`glutSetVertexAttribCoord3`, `glutSetVertexAttribNormal`, and `glutSetVertexAttribTexCoord2`.
Texture coordinates are only generated for the teaset.

### Extension Function Queries

`glutGetProcAddress` is a wrapper for the `glXGetProcAddressARB` and `wglGetProcAddress` functions.

# Initialization Functions

TODO