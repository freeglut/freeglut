
<!--
TODO:
Alphabetic sort for each function?
-->

# Table of Contents

1. [Introduction]()

2. [Background]()

	- [Design Philosophy]()
	- [Conventions]()
	- [Terminology]()
	- [Differences from GLUT 3.7]()

3. [Initialization Functions]()

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
