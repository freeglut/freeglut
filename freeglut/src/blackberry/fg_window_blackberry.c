/*
 * fg_window_blackberry.c
 *
 * Window management methods for BlackBerry
 *
 * Copyright (c) 1999-2000 Pawel W. Olszta. All Rights Reserved.
 * Written by Pawel W. Olszta, <olszta@sourceforge.net>
 * Copied for Platform code by Evan Felix <karcaw at gmail.com>
 * Copyright (C) 2012  Sylvain Beucler
 * Copyright (C) 2013  Vincent Simonetti
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL
 * PAWEL W. OLSZTA BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

#define FREEGLUT_BUILDING_LIB
#include <GL/freeglut.h>
#include "fg_internal.h"
#include "egl/fg_window_egl.h"
#include <screen/screen.h>
#include <bps/screen.h>

/*
 * Opens a window. Requires a SFG_Window object created and attached
 * to the freeglut structure. OpenGL context is created here.
 */
void fgPlatformOpenWindow( SFG_Window* window, const char* title,
                           GLboolean positionUse, int x, int y,
                           GLboolean sizeUse, int w, int h,
                           GLboolean gameMode, GLboolean isSubWindow )
{
  /* TODO: only one full-screen window possible? */
  if (fgDisplay.pDisplay.single_native_window != NULL) {
    fgWarning("You can't have more than one window on BlackBerry");
    return;
  }
  window->Window.pContext.event = NULL; //XXX Should probably be done elsewhere. Done here so there is no event at the moment

  /* Create window */
  if (screen_create_context(&window->Window.pContext.screenContext, 0)) {
    fgError("Could not create screen context");
	return;
  }
  screen_window_t sWindow;
  if (screen_create_window(&sWindow, window->Window.pContext.screenContext)) {
	screen_destroy_context(window->Window.pContext.screenContext);
  	fgError("Could not create window");
  	return;
  }
  fgDisplay.pDisplay.single_native_window = sWindow;

  /* Set window properties */
  int screenFormat = SCREEN_FORMAT_RGBA8888;
#ifdef __X86__
  int screenUsage = SCREEN_USAGE_OPENGL_ES2;
#else
  int screenUsage = SCREEN_USAGE_DISPLAY | SCREEN_USAGE_OPENGL_ES2; // Physical device copy directly into physical display
#endif
  if (screen_set_window_property_iv(sWindow, SCREEN_PROPERTY_FORMAT, &screenFormat)) {
	screen_destroy_window(sWindow);
	screen_destroy_context(window->Window.pContext.screenContext);
	fgError("Could not set window format");
	return;
  }
  if (screen_set_window_property_iv(sWindow, SCREEN_PROPERTY_USAGE, &screenUsage)) {
	screen_destroy_window(sWindow);
	screen_destroy_context(window->Window.pContext.screenContext);
	fgError("Could not set window usage");
	return;
  }

  /* Could set size based on what is specified for window. Work on another time
  int size[2];
  size[0] = w;
  size[1] = h;
  if (screen_set_window_property_iv(sWindow, SCREEN_PROPERTY_BUFFER_SIZE, size)) {
  	screen_destroy_window(sWindow);
  	screen_destroy_context(window->Window.pContext.screenContext);
  	fgError("Could not set window buffer size");
  	return;
  }*/

  /* Create window buffers */
  if (screen_create_window_buffers(sWindow, 2)) {
	screen_destroy_window(sWindow);
	screen_destroy_context(window->Window.pContext.screenContext);
	fgError("Could not create window buffers");
	return;
  }

  /* Request window events */
  screen_request_events(window->Window.pContext.screenContext);

  /* Save window */
  window->Window.Handle = fgDisplay.pDisplay.single_native_window;
  window->State.WorkMask |= GLUT_INIT_WORK;

  /* Create context */
  fghChooseConfig(&window->Window.pContext.egl.Config);
  window->Window.Context = fghCreateNewContextEGL(window);

  /* Create EGL window */
  fghPlatformOpenWindowEGL(window);

  window->State.Visible = GL_TRUE;
}

/*
 * Request a window resize
 */
void fgPlatformReshapeWindow ( SFG_Window *window, int width, int height )
{
  fprintf(stderr, "fgPlatformReshapeWindow: STUB\n");
}

/*
 * Closes a window, destroying the frame and OpenGL context
 */
void fgPlatformCloseWindow( SFG_Window* window )
{
  fghPlatformCloseWindowEGL(window);

  screen_stop_events(window->Window.pContext.screenContext);

  screen_destroy_window((screen_window_t)window->Window.Handle);

  screen_destroy_context(window->Window.pContext.screenContext);
}

/*
 * This function makes the specified window visible
 */
void fgPlatformShowWindow( void )
{
  fprintf(stderr, "fgPlatformShowWindow: STUB\n");
}

/*
 * This function hides the specified window
 */
void fgPlatformHideWindow( SFG_Window *window )
{
  fprintf(stderr, "fgPlatformHideWindow: STUB\n");
}

/*
 * Iconify the specified window (top-level windows only)
 */
void fgPlatformIconifyWindow( SFG_Window *window )
{
  fprintf(stderr, "fgPlatformGlutIconifyWindow: STUB\n");
}

/*
 * Set the current window's title
 */
void fgPlatformGlutSetWindowTitle( const char* title )
{
  fprintf(stderr, "fgPlatformGlutSetWindowTitle: STUB\n");
}

/*
 * Set the current window's iconified title
 */
void fgPlatformGlutSetIconTitle( const char* title )
{
  fprintf(stderr, "fgPlatformGlutSetIconTitle: STUB\n");}

/*
 * Change the specified window's position
 */
void fgPlatformPositionWindow( SFG_Window *window, int x, int y )
{
  fprintf(stderr, "fgPlatformPositionWindow: STUB\n");
}

/*
 * Lowers the specified window (by Z order change)
 */
void fgPlatformPushWindow( SFG_Window *window )
{
  fprintf(stderr, "fgPlatformPushWindow: STUB\n");
}

/*
 * Raises the specified window (by Z order change)
 */
void fgPlatformPopWindow( SFG_Window *window )
{
  fprintf(stderr, "fgPlatformPopWindow: STUB\n");
}

/*
 * Toggle the window's full screen state.
 */
void fgPlatformFullScreenToggle( SFG_Window *win )
{
  fprintf(stderr, "fgPlatformFullScreenToggle: STUB\n");
}
