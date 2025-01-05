
<!--
TODO:
Alphabetic sort for each function?
-->

# Table of Contents

1. [Introduction]()

2. [Background]()

	1. [Design Philosophy]()
	2. [Conventions]()
	3. [Terminology]()
	4. [Differences from GLUT 3.7]()

3. [Initialization Functions]()

	1. [glutInit]()
	2. [glutInitWindowPosition]()
	3. [glutInitWindowSize]()
	4. [glutInitDisplayMode]()
	5. [glutInitDisplayString]()
	6. [glutInitErrorFunc [fg]]()
	7. [glutInitWarningFunc [fg]]()

4. [Event Processing Functions]()

	1. [glutMainLoop]()
	2. [glutMainLoopEvent [fg]]()
	3. [glutLeaveMainLoop [fg]]()

5. [Window Functions]()

	1. [glutCreateWindow]()
	2. [glutCreateSubwindow]()
	3. [glutDestroyWindow]()
	4. [glutSetWindow]()
	5. [glutGetWindow]()
	6. [glutSetWindowTitle]()
	7. [glutSetIconTitle]()
	8. [glutReshapeWindow]()
	9. [glutPositionWindow]()
	10. [glutShowWindow]()
	11. [glutHideWindow]()
	12. [glutIconifyWindow]()
	13. [glutPushWindow]()
	14. [glutPopWindow]()
	15. [glutFullScreen]()
	16. [glutLeaveFullScreen [fg]]()
	17. [glutFullScreenToggle [fg]]()

6. [Display Functions]()

	1. [glutPostRedisplay]()
	2. [glutPostWindowRedisplay]()
	3. [glutSwapBuffers]()

7. [Mouse Cursor Functions]()

	1. [glutSetCursor]()
	2. [glutWarpPointer]()

8. [Overlay Functions]()

	1. [glutEstablishOverlay]()
	2. [glutRemoveOverlay]()
	3. [glutUseLayer]()
	4. [glutPostOverlayRedisplay]()
	5. [glutPostWindowOverlayRedisplay]()
	6. [glutShowOverlay]()
	7. [glutHideOverlay]()

9. [Menu Functions]()

	1. [glutCreateMenu]()
	2. [glutDestroyMenu]()
	3. [glutGetMenu]()
	4. [glutSetMenu]()
	5. [glutAddMenuEntry]()
	6. [glutAddSubMenu]()
	7. [glutChangeToMenuEntry]()
	8. [glutChangeToSubMenu]()
	9. [glutSetMenuFont [fg]]()
	10. [glutRemoveMenuItem]()
	11. [glutAttachMenu]()
	12. [glutDetachMenu]()
	13. [glutMenuDestroyFunc [fg]]()

10. [Global Callback Registration Functions]()

	1. [glutTimerFunc]()
	2. [glutIdleFunc]()
	3. [glutMenuStatusFunc]()
	4. [glutMenuStateFunc]()

11. [Window-Specific Callback Registration Functions]()

	1. [glutDisplayFunc]()
	2. [glutOverlayDisplayFunc]()
	3. [glutReshapeFunc]()
	4. [glutPositionFunc [fg]]()
	5. [glutCloseFunc [fg]]()
	6. [glutKeyboardFunc]()
	7. [glutSpecialFunc]()
	8. [glutKeyboardUpFunc]()
	9. [glutSpecialUpFunc]()
	10. [glutMotionFunc]()
	11. [glutPassiveMotionFunc]()
	12. [glutMouseFunc]()
	13. [glutMouseWheelFunc [fg]]()
	14. [glutEntryFunc]()
	15. [glutJoystickFunc]()
	16. [glutSpaceballMotionFunc]()
	17. [glutSpaceballRotateFunc]()
	18. [glutSpaceballButtonFunc]()
	19. [glutButtonBoxFunc]()
	20. [glutDialsFunc]()
	21. [glutTabletMotionFunc]()
	22. [glutTabletButtonFunc]()
	23. [glutVisibilityFunc]()
	24. [glutWindowStatusFunc]()

12. [State Setting and Retrieval Functions]()

	1. [glutSetOption [fg]]()
	2. [glutGet]()
	3. [glutDeviceGet]()
	4. [glutGetModifiers]()
	5. [glutLayerGet]()
	6. [glutExtensionSupported]()
	7. [glutGetProcAddress [fg]]()

13. [Font Rendering Functions]()

	1. [glutBitmapCharacter]()
	2. [glutBitmapString [fg]]()
	3. [glutBitmapWidth]()
	4. [glutBitmapLength]()
	5. [glutBitmapHeight [fg]]()
	6. [glutStrokeCharacter]()
	7. [glutStrokeString [fg]]()
	8. [glutStrokeWidth]()
	9. [glutStrokeWidthf]()
	10. [glutStrokeLength]()
	11. [glutStrokeLengthf]()
	12. [glutStrokeHeight [fg]]()

14. [Geometric Object Rendering Functions]()

	1. [glutWireSphere]()
	2. [glutSolidSphere]()
	3. [glutWireTorus]()
	4. [glutSolidTorus]()
	5. [glutWireCone]()
	6. [glutSolidCone]()
	7. [glutWireCube]()
	8. [glutSolidCube]()
	9. [glutWireTetrahedron]()
	10. [glutSolidTetrahedron]()
	11. [glutWireOctahedron]()
	12. [glutSolidOctahedron]()
	13. [glutWireDodecahedron]()
	14. [glutSolidDodecahedron]()
	15. [glutWireIcosahedron]()
	16. [glutSolidIcosahedron]()
	17. [glutWireRhombicDodecahedron [fg]]()
	18. [glutSolidRhombicDodecahedron [fg]]()
	19. [glutWireTeapot]()
	20. [glutSolidTeapot]()
	21. [glutWireTeacup [fg]]()
	22. [glutSolidTeacup [fg]]()
	23. [glutWireTeaspoon [fg]]()
	24. [glutSolidTeaspoon [fg]]()
	25. [glutSetVertexAttribCoord3 [fg]]()
	26. [glutSetVertexAttribNormal [fg]]()
	27. [glutSetVertexAttribTexCoord2 [fg]]()

15. [Game Mode Functions]()

	1. [glutGameModeString]()
	2. [glutEnterGameMode]()
	3. [glutLeaveGameMode]()
	4. [glutGameModeGet]()

16. [Video Resize Functions]()

	1. [glutVideoResizeGet]()
	2. [glutSetupVideoResizing]()
	3. [glutStopVideoResizing]()
	4. [glutVideoResize]()
	5. [glutVideoPan]()

17. [Color Map Functions]()

	1. [glutSetColor]()
	2. [glutGetColor]()
	3. [glutCopyColormap]()

18. [MultiTouch Functions]()

	1. [glutMultiEntryFunc [fg]]()
	2. [glutMultiButtonFunc [fg]]()
	3. [glutMultiMotionFunc [fg]]()
	4. [glutMultiPassiveFunc [fg]]()

19. [Mobile functions]()

	1. [glutInitContextFunc [fg]]()
	2. [glutAppStatusFunc [fg]]()

20. [Miscellaneous Functions]()

	1. [glutSetKeyRepeat]()
	2. [glutIgnoreKeyRepeat]()
	3. [glutForceJoystickFunc]()
	4. [glutReportErrors]()

21. [Usage Notes]()

	1. [Implementation Notes]()
	2. [GLUT State]()
	3. ["freeglut.h" Header File]()
	4. [References]()
	5. [Index]()

22. [Unsorted Functions]()
