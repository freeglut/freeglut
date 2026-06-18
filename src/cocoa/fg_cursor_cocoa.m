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

/*
 * Cursor handling works through AppKit cursor rects: fgPlatformSetCursor
 * stores the NSCursor for the window in pContext.Cursor, and the window's
 * fgOpenGLView applies it from -resetCursorRects whenever the pointer is
 * inside the view. A nil cursor means GLUT_CURSOR_INHERIT.
 */

/* A fully transparent cursor, for GLUT_CURSOR_NONE */
static NSCursor *fghEmptyCursor( void )
{
    static NSCursor *empty = nil;

    if ( !empty ) {
        NSImage *blank = [[NSImage alloc] initWithSize:NSMakeSize( 16, 16 )];
        empty          = [[NSCursor alloc] initWithImage:blank hotSpot:NSZeroPoint];
        [blank release];
    }
    return empty;
}

/* A pair of rotating arrows for GLUT_CURSOR_CYCLE. macOS has no system
 * equivalent, so we draw one in code (cf. fghEmptyCursor). */
static NSCursor *fghCycleCursor( void )
{
    static NSCursor *cycle = nil;

    if ( !cycle ) {
        const CGFloat size   = 24.0;
        const NSPoint center = NSMakePoint( size / 2, size / 2 );
        const CGFloat radius = 7.0;

        NSImage *image = [NSImage imageWithSize:NSMakeSize( size, size )
                                        flipped:NO
                                 drawingHandler:^BOOL( NSRect dstRect ) {
            (void)dstRect;

            /* Two opposing arcs, each ending in an arrowhead, so the cursor
             * reads as a continuous rotation. */
            const CGFloat arcs[2][2] = { { 30.0, 150.0 }, { 210.0, 330.0 } };

            NSMutableArray<NSBezierPath *> *arcPaths  = [NSMutableArray array];
            NSMutableArray<NSBezierPath *> *headPaths = [NSMutableArray array];

            for ( int i = 0; i < 2; ++i ) {
                CGFloat start = arcs[i][0];
                CGFloat end   = arcs[i][1];

                NSBezierPath *arc = [NSBezierPath bezierPath];
                [arc setLineCapStyle:NSLineCapStyleRound];
                [arc appendBezierPathWithArcWithCenter:center
                                                radius:radius
                                            startAngle:start
                                              endAngle:end]; /* counter-clockwise */
                [arcPaths addObject:arc];

                /* Arrowhead at the arc's end, pointing along the
                 * counter-clockwise tangent (end + 90 degrees). */
                CGFloat endRad = end * M_PI / 180.0;
                NSPoint tip    = NSMakePoint( center.x + radius * cos( endRad ),
                                              center.y + radius * sin( endRad ) );
                CGFloat tanRad = endRad + M_PI_2;
                NSPoint dir    = NSMakePoint( cos( tanRad ), sin( tanRad ) );
                NSPoint nrm    = NSMakePoint( -dir.y, dir.x );

                NSBezierPath *head = [NSBezierPath bezierPath];
                [head moveToPoint:NSMakePoint( tip.x + dir.x * 5.0, tip.y + dir.y * 5.0 )];
                [head lineToPoint:NSMakePoint( tip.x + nrm.x * 4.0, tip.y + nrm.y * 4.0 )];
                [head lineToPoint:NSMakePoint( tip.x - nrm.x * 4.0, tip.y - nrm.y * 4.0 )];
                [head closePath];
                [headPaths addObject:head];
            }

            /* White halo first, then black on top, so the cursor stays
             * visible over both light and dark content. */
            [[NSColor whiteColor] set];
            for ( NSBezierPath *arc in arcPaths ) {
                [arc setLineWidth:4.0];
                [arc stroke];
            }
            for ( NSBezierPath *head in headPaths ) {
                [head setLineWidth:2.0];
                [head stroke];
                [head fill];
            }

            [[NSColor blackColor] set];
            for ( NSBezierPath *arc in arcPaths ) {
                [arc setLineWidth:2.0];
                [arc stroke];
            }
            for ( NSBezierPath *head in headPaths ) {
                [head fill];
            }

            return YES;
        }];

        cycle = [[NSCursor alloc] initWithImage:image hotSpot:center];
    }
    return cycle;
}

/* Diagonal window-resize cursors only gained public API in macOS 15 */
static NSCursor *fghCornerCursor( int cursorID )
{
#if defined( MAC_OS_VERSION_15_0 ) && MAC_OS_X_VERSION_MAX_ALLOWED >= MAC_OS_VERSION_15_0
    if ( @available( macOS 15.0, * ) ) {
        NSCursorFrameResizePosition position;
        switch ( cursorID ) {
        case GLUT_CURSOR_TOP_LEFT_CORNER:
            position = NSCursorFrameResizePositionTopLeft;
            break;
        case GLUT_CURSOR_TOP_RIGHT_CORNER:
            position = NSCursorFrameResizePositionTopRight;
            break;
        case GLUT_CURSOR_BOTTOM_RIGHT_CORNER:
            position = NSCursorFrameResizePositionBottomRight;
            break;
        default:
            position = NSCursorFrameResizePositionBottomLeft;
            break;
        }
        return [NSCursor frameResizeCursorFromPosition:position inDirections:NSCursorFrameResizeDirectionsAll];
    }
#endif
    return [NSCursor crosshairCursor];
}

static NSCursor *fghCursorForID( int cursorID )
{
    switch ( cursorID ) {
    case GLUT_CURSOR_RIGHT_ARROW: /* TODO: macOS has no mirrored arrow */
    case GLUT_CURSOR_LEFT_ARROW:
        return [NSCursor arrowCursor];
    case GLUT_CURSOR_INFO:
        return [NSCursor pointingHandCursor];
    case GLUT_CURSOR_DESTROY:
        return [NSCursor operationNotAllowedCursor];
    case GLUT_CURSOR_HELP:  /* TODO: no public help/wait cursors; the system */
    case GLUT_CURSOR_WAIT:  /* shows its own busy indicator when appropriate */
        return [NSCursor arrowCursor];
    case GLUT_CURSOR_CYCLE:
        return fghCycleCursor( );
    case GLUT_CURSOR_SPRAY: /* TODO */
    case GLUT_CURSOR_CROSSHAIR:
    case GLUT_CURSOR_FULL_CROSSHAIR: /* FULL_CROSSHAIR demotes to CROSSHAIR, as on X11 */
        return [NSCursor crosshairCursor];
    case GLUT_CURSOR_TEXT:
        return [NSCursor IBeamCursor];
    case GLUT_CURSOR_UP_DOWN:
        return [NSCursor resizeUpDownCursor];
    case GLUT_CURSOR_LEFT_RIGHT:
        return [NSCursor resizeLeftRightCursor];
    case GLUT_CURSOR_TOP_SIDE:
        return [NSCursor resizeUpCursor];
    case GLUT_CURSOR_BOTTOM_SIDE:
        return [NSCursor resizeDownCursor];
    case GLUT_CURSOR_LEFT_SIDE:
        return [NSCursor resizeLeftCursor];
    case GLUT_CURSOR_RIGHT_SIDE:
        return [NSCursor resizeRightCursor];
    case GLUT_CURSOR_TOP_LEFT_CORNER:
    case GLUT_CURSOR_TOP_RIGHT_CORNER:
    case GLUT_CURSOR_BOTTOM_RIGHT_CORNER:
    case GLUT_CURSOR_BOTTOM_LEFT_CORNER:
        return fghCornerCursor( cursorID );
    case GLUT_CURSOR_NONE:
        return fghEmptyCursor( );
    case GLUT_CURSOR_INHERIT:
        return nil;
    default:
        fgError( "Unknown cursor type: %d", cursorID );
        return nil; /* not reached */
    }
}

/* Walk up the window hierarchy to resolve GLUT_CURSOR_INHERIT (nil).
 * Returns nil if no ancestor set a cursor, i.e. use the system default. */
NSCursor *fghCocoaEffectiveCursor( const SFG_Window *window )
{
    for ( ; window; window = window->Parent ) {
        NSCursor *cursor = (NSCursor *)window->Window.pContext.Cursor;
        if ( cursor ) {
            return cursor;
        }
    }
    return nil;
}

/* Subwindows inheriting their cursor must re-resolve it too */
static void fghInvalidateCursorRectsRecursive( SFG_Window *window )
{
    NSView *view = (NSView *)window->Window.pContext.View;
    if ( view ) {
        [[view window] invalidateCursorRectsForView:view];
    }

    SFG_Window *child;
    for ( child = (SFG_Window *)window->Children.First; child; child = (SFG_Window *)child->Node.Next ) {
        fghInvalidateCursorRectsRecursive( child );
    }
}

void fgPlatformSetCursor( SFG_Window *window, int cursorID )
{
    AUTORELEASE_POOL;

    NSCursor *cursor = fghCursorForID( cursorID );

    [cursor retain];
    [(NSCursor *)window->Window.pContext.Cursor release];
    window->Window.pContext.Cursor = cursor;

    fghInvalidateCursorRectsRecursive( window );
}

void fgPlatformWarpPointer( int x, int y )
{
    TODO_IMPL;
}
