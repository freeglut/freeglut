/*
* freeglut_types.h
*
* The freeglut library global typedefs.
* 
* Ideally *every* type that is visible via freeglut_std.h
* should be moved here so if a developer changes the underlying type
* client code can also take advantage of using the new type w/o
* changing its code. For now this covers callback types only.
*
* Copyright (c) 1999-2000 Pawel W. Olszta. All Rights Reserved.
* Written by Pawel W. Olszta, <olszta@sourceforge.net>
* Creation date: Thu Dec 2 1999
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

#ifndef  FREEGLUT_TYPES_H
#define  FREEGLUT_TYPES_H

#include <string.h>

/* -- GLOBAL TYPE DEFINITIONS ---------------------------------------------- */

/* Freeglut callbacks type definitions */
typedef void(*FGCBDisplay)(void);
typedef void(*FGCBReshape)(int, int);
typedef void(*FGCBPosition)(int, int);
typedef void(*FGCBVisibility)(int);
typedef void(*FGCBKeyboard)(unsigned char, int, int);
typedef void(*FGCBKeyboardUp)(unsigned char, int, int);
typedef void(*FGCBSpecial)(int, int, int);
typedef void(*FGCBSpecialUp)(int, int, int);
typedef void(*FGCBMouse)(int, int, int, int);
typedef void(*FGCBMouseWheel)(int, int, int, int);
typedef void(*FGCBMotion)(int, int);
typedef void(*FGCBPassive)(int, int);
typedef void(*FGCBEntry)(int);
typedef void(*FGCBWindowStatus)(int);
typedef void(*FGCBJoystick)(unsigned int, int, int, int);
typedef void(*FGCBOverlayDisplay)(void);
typedef void(*FGCBSpaceMotion)(int, int, int);
typedef void(*FGCBSpaceRotation)(int, int, int);
typedef void(*FGCBSpaceButton)(int, int);
typedef void(*FGCBDials)(int, int);
typedef void(*FGCBButtonBox)(int, int);
typedef void(*FGCBTabletMotion)(int, int);
typedef void(*FGCBTabletButton)(int, int, int, int);
typedef void(*FGCBDestroy)(void);    /* Used for both window and menu destroy callbacks */
typedef void(*FGCBClose)(void);
typedef void(*FGCBWMClose)(void);

typedef void(*FGCBMultiEntry)(int, int);
typedef void(*FGCBMultiButton)(int, int, int, int, int);
typedef void(*FGCBMultiMotion)(int, int, int);
typedef void(*FGCBMultiPassive)(int, int, int);

typedef void(*FGCBInitContext)();
typedef void(*FGCBAppStatus)(int);

/* The global callbacks type definitions */
typedef void(*FGCBIdle)(void);
typedef void(*FGCBTimer)(int);
typedef void(*FGCBMenuState)(int);
typedef void(*FGCBMenuStatus)(int, int, int);

/* The callback used when creating/using menus */
typedef void(*FGCBMenu)(int);

/* The FreeGLUT error/warning handler type definition */
typedef void(*FGError) (const char *fmt, va_list ap);
typedef void(*FGWarning) (const char *fmt, va_list ap);

#endif /* FREEGLUT_TYPES_H */

/*** END OF FILE ***/
