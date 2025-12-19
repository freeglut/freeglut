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

#import <Cocoa/Cocoa.h>

static NSScreen *fghScreenContainingPoint( NSPoint screenPoint )
{
    AUTORELEASE_POOL;

    for ( NSScreen *screen in [NSScreen screens] ) {
        if ( NSPointInRect( screenPoint, [screen frame] ) ) {
            return screen;
        }
    }

    return [NSScreen mainScreen];
}

void fghPlatformGetCursorPos( const SFG_Window *window, GLboolean client, SFG_XYUse *mouse_pos )
{
    AUTORELEASE_POOL;

    NSPoint   screenLoc   = [NSEvent mouseLocation]; // Bottom-left origin
    NSScreen *screen      = fghScreenContainingPoint( screenLoc );
    NSRect    screenFrame = screen ? [screen visibleFrame] : NSZeroRect;

    int x = (int)( screenLoc.x - screenFrame.origin.x );
    int y = (int)( NSMaxY( screenFrame ) - screenLoc.y ); // Top-left origin within the usable frame

    if ( client && window && window->Window.Handle ) {

        NSWindow *nsWindow  = window->Window.Handle;
        NSPoint   windowLoc = [nsWindow convertPointFromScreen:screenLoc];
        NSView   *view      = [nsWindow contentView];
        NSPoint   viewLoc   = [view convertPoint:windowLoc fromView:nil];

        x = (int)viewLoc.x;
        y = (int)( view.bounds.size.height - viewLoc.y );
    }

    mouse_pos->X   = x;
    mouse_pos->Y   = y;
    mouse_pos->Use = GL_TRUE;
}

void fgPlatformSetCursor( SFG_Window *window, int cursorID )
{
    TODO_IMPL;
}

void fgPlatformWarpPointer( int x, int y )
{
    TODO_IMPL;
}
