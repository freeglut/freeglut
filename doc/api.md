
<!--
TODO:
Alphabetic sort for each function?
-->

# Table of Contents

1. [Introduction](#introduction)

2. [Background](#background)

	- [Design Philosophy](#design-philosophy)
	- [Conventions](#conventions)
	- [Terminology](#terminology)
	- [Differences from GLUT 3.7](#differences-from-glut-3-7)

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
that contain "[fg]" are extended functionality that is not part of FreeGLUT.
This will, of course, be mentioned in the API reference for each function,
because who reads introductions! :D

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

### User-data callbacks

## Terminology

## Differences from GLUT 3.7

### glutMainLoop Behaviour

### Action on Window Closure

### Fullscreen windows

### Changes to Callbacks

### String Rendering

### Geometry Rendering

### Extension Function Queries

# Initialization Functions