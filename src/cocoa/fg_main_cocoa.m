/*
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

fg_time_t fgPlatformSystemTime( void )
{
    TODO_IMPL;
    return 0;
}

/*
 * Does the magic required to relinquish the CPU until something interesting
 * happens.
 */

void fgPlatformSleepForEvents( fg_time_t msec )
{
    TODO_IMPL;
}

/*
 * Returns GLUT modifier mask for the state field of an X11 event.
 */
int fgPlatformGetModifiers( int state )
{
    TODO_IMPL;
    return 0;
}

void fgPlatformProcessSingleEvent( void )
{
    TODO_IMPL;
}

void fgPlatformMainLoopPreliminaryWork( void )
{
    TODO_IMPL;
}

/* deal with work list items */
void fgPlatformInitWork( SFG_Window *window )
{
    TODO_IMPL;
}

void fgPlatformPosResZordWork( SFG_Window *window, unsigned int workMask )
{
    TODO_IMPL;
}

void fgPlatformVisibilityWork( SFG_Window *window )
{
    TODO_IMPL;
}
