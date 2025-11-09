/*
 * Copyright (c) 1999-2000 Pawel W. Olszta. All Rights Reserved.
 * Written by Pawel W. Olszta, <olszta@sourceforge.net>
 * Copied for Platform code by Evan Felix <karcaw at gmail.com>
 * Creation date: Thur Feb 2 2012
 *
 * Copyright (c) 2025 John Tsiombikas <nuclear@mutantstargoat.com>
 * Implemented swap control and platform-specific extension query
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

#include <GL/freeglut.h>
#include "../fg_internal.h"


typedef void (*glx_swapint_ext_func)(Display*, GLXDrawable, int);
typedef int (*glx_swapint_mesa_func)(unsigned int);
typedef int (*glx_swapint_sgi_func)(int);

static glx_swapint_ext_func glx_swap_interval_ext;
static glx_swapint_mesa_func glx_swap_interval_mesa;
static glx_swapint_sgi_func glx_swap_interval_sgi;


void fgPlatformGlutSwapBuffers( SFG_PlatformDisplay *pDisplayPtr, SFG_Window* CurrentWindow )
{
    glXSwapBuffers( pDisplayPtr->Display, CurrentWindow->Window.Handle );
}

void fgPlatformInitSwapCtl(void)
{
    if(fgState.HasSwapCtlTear >= 0) {
		return;		/* swap control was already initialized */
    }

#ifdef GLX_VERSION_1_4
	glx_swap_interval_ext = (glx_swapint_ext_func)glutGetProcAddress("glXSwapIntervalEXT");
	glx_swap_interval_mesa = (glx_swapint_mesa_func)glutGetProcAddress("glXSwapIntervalMESA");
	glx_swap_interval_sgi = (glx_swapint_sgi_func)glutGetProcAddress("glXSwapIntervalSGI");
#elif defined(__sgi)
	glx_swap_interval_sgi = glXSwapIntervalSGI;
#endif

	fgState.HasSwapCtlTear = glutExtensionSupported("GLX_EXT_swap_control_tear");
}

void fgPlatformSwapInterval(int n)
{
	if(n < 0 && !fgState.HasSwapCtlTear) {
		/* if a negative swap interval was requested and adaptive vsync is not
		 * available (GLX_EXT_swap_control_tear), fallback to regular vsync
		 * with the equivalent positive interval.
		 */
		n = -n;
	}
	if(glx_swap_interval_ext) {
		Display *dpy = fgDisplay.pDisplay.Display;
		Window win = fgStructure.CurrentWindow->Window.Handle;
		glx_swap_interval_ext(dpy, win, n);

	} else if(glx_swap_interval_mesa) {
		glx_swap_interval_mesa(n);

	} else if(glx_swap_interval_sgi) {
		/* GLX_SGI_swap_control does not support disabling vsync, only swap
		 * intervals 1 or higher are accepted (so it's pretty useless)
		 */
		glx_swap_interval_sgi(n < 1 ? 1 : n);
	}
}

int fgPlatformExtSupported(const char *ext)
{
#ifdef GLX_VERSION_1_1
	Display *dpy = fgDisplay.pDisplay.Display;
	int scr = fgDisplay.pDisplay.Screen;
	const char *str = glXQueryExtensionsString(dpy, scr);
	if(str && fgMatchExt(str, ext)) {
		return 1;
	}
#endif
	return 0;
}
