PROJECT(freeglut)
CMAKE_MINIMUM_REQUIRED(VERSION 2.6)

SET(FREEGLUT_HEADERS
	include/GL/freeglut.h
	include/GL/freeglut_ext.h
	include/GL/freeglut_std.h
	include/GL/glut.h
)
SET(FREEGLUT_SRCS
	${FREEGLUT_HEADERS}
	src/Common/freeglut_callbacks.c
	src/Common/freeglut_cursor.c
	src/Common/freeglut_display.c
	src/Common/freeglut_ext.c
	src/Common/freeglut_font.c
	src/Common/freeglut_font_data.c
	src/Common/freeglut_gamemode.c
	src/Common/freeglut_geometry.c
	src/Common/freeglut_glutfont_definitions.c
	src/Common/freeglut_init.c
	src/Common/freeglut_internal.h
	src/Common/freeglut_input_devices.c
	src/Common/freeglut_joystick.c
	src/Common/freeglut_main.c
	src/Common/freeglut_menu.c
	src/Common/freeglut_misc.c
	src/Common/freeglut_overlay.c
	src/Common/freeglut_spaceball.c
	src/Common/freeglut_state.c
	src/Common/freeglut_stroke_mono_roman.c
	src/Common/freeglut_stroke_roman.c
	src/Common/freeglut_structure.c
	src/Common/freeglut_teapot.c
	src/Common/freeglut_videoresize.c
	src/Common/freeglut_window.c
	src/Common/freeglut_xinput.c
)

IF(WIN32)
	LIST(APPEND FREEGLUT_SRCS
		src/mswin/freeglut_cursor_mswin.c
		src/mswin/freeglut_display_mswin.c
		src/mswin/freeglut_ext_mswin.c
		src/mswin/freeglut_gamemode_mswin.c
		src/mswin/freeglut_init_mswin.c
		src/mswin/freeglut_internal_mswin.h
		src/mswin/freeglut_input_devices_mswin.c
		src/mswin/freeglut_joystick_mswin.c
		src/mswin/freeglut_main_mswin.c
		src/mswin/freeglut_menu_mswin.c
		src/mswin/freeglut_spaceball_mswin.c
		src/mswin/freeglut_state_mswin.c
		src/mswin/freeglut_structure_mswin.c
		src/mswin/freeglut_window_mswin.c
)
ELSE()
	LIST(APPEND FREEGLUT_SRCS
		src/x11/freeglut_cursor_x11.c
		src/x11/freeglut_display_x11.c
		src/x11/freeglut_ext_x11.c
		src/x11/freeglut_gamemode_x11.c
		src/x11/freeglut_glutfont_definitions_x11.c
		src/x11/freeglut_init_x11.c
		src/x11/freeglut_internal_x11.h
		src/x11/freeglut_input_devices_x11.c
		src/x11/freeglut_joystick_x11.c
		src/x11/freeglut_main_x11.c
		src/x11/freeglut_menu_x11.c
		src/x11/freeglut_spaceball_x11.c
		src/x11/freeglut_state_x11.c
		src/x11/freeglut_structure_x11.c
		src/x11/freeglut_window_x11.c
)


ENDIF()

# Neatly organize all of the output files in the build directory
SET(EXECUTABLE_OUTPUT_PATH ${PROJECT_BINARY_DIR}/bin)
SET(LIBRARY_OUTPUT_PATH ${PROJECT_BINARY_DIR}/lib)

# BUILD_SHARED_LIBS is already a standard CMake variable, but we need to
# re-declare it here so it will show up in the GUI.
OPTION(BUILD_SHARED_LIBS "Build FreeGLUT as a shared library." ON)

FIND_PACKAGE(OpenGL REQUIRED)

INCLUDE_DIRECTORIES(${CMAKE_SOURCE_DIR}/include ${OPENGL_INCLUDE_DIR})

IF(WIN32)
	MESSAGE(WARNING "Insecure CRT warnings hidden (might want to fix these)")
	ADD_DEFINITIONS(-D_CRT_SECURE_NO_WARNINGS)
ENDIF()

INCLUDE(CheckIncludeFiles)

CHECK_INCLUDE_FILES(X11/extensions/xf86vmode.h 	HAVE_X11_EXTENSIONS_XF86VMODE_H)
CHECK_INCLUDE_FILES(X11/extensions/Xrandr.h 	HAVE_X11_EXTENSIONS_XRANDR_H)
CHECK_INCLUDE_FILES(sys/types.h HAVE_SYS_TYPES_H)
CHECK_INCLUDE_FILES(unistd.h 	HAVE_UNISTD_H)
CHECK_INCLUDE_FILES(sys/time.h 	HAVE_SYS_TIME_H)
CHECK_INCLUDE_FILES(stdbool.h 	HAVE_STDBOOL_H)
CHECK_INCLUDE_FILES(sys/param.h HAVE_SYS_PARAM_H)
CHECK_INCLUDE_FILES(sys/ioctl.h HAVE_SYS_IOCTL_H)
CHECK_INCLUDE_FILES(fcntl.h 	HAVE_FCNTL_H)
CHECK_INCLUDE_FILES(errno.h 	HAVE_ERRNO_H)
CHECK_INCLUDE_FILES(usbhid.h 	HAVE_USBHID_H)

# The generated config.h is placed in the project's build directory, just to
# ensure that all CMake-generated files are kept away from the main source tree.
# As a result, the build directory must to be added to the include path list.
CONFIGURE_FILE(${CMAKE_SOURCE_DIR}/config.h.in ${CMAKE_BINARY_DIR}/config.h)
INCLUDE_DIRECTORIES(${CMAKE_BINARY_DIR})
ADD_DEFINITIONS(-DHAVE_CONFIG_H)

IF(BUILD_SHARED_LIBS)
	IF(WIN32)
		LIST(APPEND FREEGLUT_SRCS src/Common/freeglutdll.def freeglut.rc)
	ENDIF()
	ADD_DEFINITIONS(-DFREEGLUT_EXPORTS)
ELSE()
	ADD_DEFINITIONS(-DFREEGLUT_STATIC)
ENDIF()

# The default library type is determined by BUILD_SHARED_LIBS
ADD_LIBRARY(freeglut ${FREEGLUT_SRCS})

IF(NOT BUILD_SHARED_LIBS)
	SET_TARGET_PROPERTIES(freeglut PROPERTIES OUTPUT_NAME freeglut_static)
ENDIF()

INSTALL(TARGETS freeglut DESTINATION lib)
INSTALL(FILES ${FREEGLUT_HEADERS} DESTINATION include/GL)

# Optionally build demos, on by default.
option( FREEGLUT_BUILD_DEMOS "Build FreeGLUT demos." ON )

MACRO(ADD_DEMO name)
	if( FREEGLUT_BUILD_DEMOS )
		ADD_EXECUTABLE(${name} ${ARGN})
		TARGET_LINK_LIBRARIES(${name} freeglut)
		INSTALL(TARGETS ${name} DESTINATION bin)
	endif()
ENDMACRO()

ADD_DEMO(CallbackMaker   progs/demos/CallbackMaker/CallbackMaker.c)
ADD_DEMO(Fractals        progs/demos/Fractals/fractals.c)
ADD_DEMO(Fractals_random progs/demos/Fractals_random/fractals_random.c)
ADD_DEMO(Lorenz          progs/demos/Lorenz/lorenz.c)
ADD_DEMO(One             progs/demos/One/one.c)
ADD_DEMO(Resizer         progs/demos/Resizer/Resizer.cpp)
ADD_DEMO(shapes          progs/demos/shapes/shapes.c)
ADD_DEMO(smooth_opengl3  progs/demos/smooth_opengl3/smooth_opengl3.c)
ADD_DEMO(spaceball       progs/demos/spaceball/spaceball.c
                         progs/demos/spaceball/vmath.c
                         progs/demos/spaceball/vmath.h)
ADD_DEMO(subwin          progs/demos/subwin/subwin.c)

