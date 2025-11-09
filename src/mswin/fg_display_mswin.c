/*
 * Copyright (c) 2012 Stephen J. Baker. All Rights Reserved.
 * Written by John F. Fay, <fayjf@sourceforge.net>
 * Creation date: Sat Jan 28, 2012
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




void fgPlatformGlutSwapBuffers( SFG_PlatformDisplay *pDisplayPtr, SFG_Window* CurrentWindow )
{
    SwapBuffers( CurrentWindow->Window.pContext.Device );
}

void fgPlatformInitSwapCtl(void)
{
	SFG_PlatformContext *fgctx = &fgStructure.CurrentWindow->Window.pContext;
	if(fgctx->has_swap_ctl_tear >= 0) {
		return;		/* swap control was already initialized for this context */
	}

	fgctx->wgl_get_extensions_string = (wgl_getextstr_func)glutGetProcAddress("wglGetExtensionsStringEXT");

	if((fgctx->wgl_swap_interval = (wgl_swapint_func)glutGetProcAddress("wglSwapIntervalEXT"))) {
		fgctx->has_swap_ctl_tear = glutExtensionSupported("WGL_EXT_swap_control_tear");
	} else {
		fgctx->has_swap_ctl_tear = 0;
	}
}

void fgPlatformSwapInterval(int n)
{
	SFG_PlatformContext *fgctx = &fgStructure.CurrentWindow->Window.pContext;

	if(n < 0 && !fgctx->has_swap_ctl_tear) {
		/* if a negative swap interval was requested and adaptive vsync is not
		 * available (WGL_EXT_swap_control_tear), fallback to regular vsync
		 * with the equivalent positive interval.
		 */
		n = -n;
	}
	if(fgctx->wgl_swap_interval) {
		fgctx->wgl_swap_interval(n);
	}
}

int fgPlatformExtSupported(const char *ext)
{
	const char *str;
	SFG_PlatformContext *fgctx = &fgStructure.CurrentWindow->Window.pContext;

	if(!fgctx->wgl_get_extensions_string) {
		return 0;
	}
	if((str = fgctx->wgl_get_extensions_string()) && fgMatchExt(str, ext)) {
		return 1;
	}
	return 0;
}
