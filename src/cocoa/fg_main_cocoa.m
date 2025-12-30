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

#include <Cocoa/Cocoa.h>

void fgPlatformSetWindow( SFG_Window *window );

extern void fghOnReshapeNotify( SFG_Window *window, int width, int height, GLboolean forceNotify );
extern void fghOnPositionNotify( SFG_Window *window, int x, int y, GLboolean forceNotify );
extern void fgPlatformFullScreenToggle( SFG_Window *win );
extern void fgPlatformPositionWindow( SFG_Window *window, int x, int y );
extern void fgPlatformReshapeWindow( SFG_Window *window, int width, int height );
extern void fgPlatformPushWindow( SFG_Window *window );
extern void fgPlatformPopWindow( SFG_Window *window );

extern void fgPlatformHideWindow( SFG_Window *window );
extern void fgPlatformIconifyWindow( SFG_Window *window );
extern void fgPlatformShowWindow( SFG_Window *window );

fg_time_t fgPlatformSystemTime( void )
{
    uint64_t now_ns = clock_gettime_nsec_np( CLOCK_REALTIME );
    return (fg_time_t)( now_ns / 1000000LL ); // Return time in milliseconds
}

/*
 * Does the magic required to relinquish the CPU until something interesting
 * happens.
 */
void fgPlatformSleepForEvents( fg_time_t msec )
{
    // Implement sleep functionality according to msec
    @autoreleasepool {
        NSTimeInterval timeout_sec = ( msec == INT_MAX ) ? 1.0 : ( msec / 1000.0 );
        NSEvent       *event       = [NSApp nextEventMatchingMask:NSEventMaskAny
                                            untilDate:[NSDate dateWithTimeIntervalSinceNow:timeout_sec]
                                               inMode:NSDefaultRunLoopMode
                                              dequeue:YES];
        if ( event ) {
            [NSApp sendEvent:event];
        }
    }
}

void fgPlatformProcessSingleEvent( void )
{
    @autoreleasepool {
        // Process all pending Cocoa events
        while ( true ) {
            NSEvent *event = [NSApp nextEventMatchingMask:NSEventMaskAny
                                                untilDate:[NSDate distantPast] // Return immediately
                                                   inMode:NSDefaultRunLoopMode
                                                  dequeue:YES];
            if ( !event )
                break; // Exit when no more events are pending

            [NSApp sendEvent:event];
        }

        // Set the current window’s OpenGL context after event processing
        if ( fgStructure.CurrentWindow ) {
            fgPlatformSetWindow( fgStructure.CurrentWindow );
        }
    }
}

void fgPlatformMainLoopPreliminaryWork( void )
{
    AUTORELEASE_POOL;

    static BOOL application_initialized;

    // Initialize the Cocoa application if not already done
    if ( !application_initialized ) {
        [NSApp finishLaunching]; // Completes the app launch process
        application_initialized = YES;
    }

    // Bring app to the front
    // Use the modern API which is more reliable than activateIgnoringOtherApps:
    if ( @available( macOS 10.9, * ) ) {
        [[NSRunningApplication currentApplication]
            activateWithOptions:( NSApplicationActivateAllWindows | NSApplicationActivateIgnoringOtherApps )];
    }
    else {
        [NSApp activateIgnoringOtherApps:YES];
    }
}

/* Upon initial window creation, do any platform-specific work required for the window */
void fgPlatformInitWork( SFG_Window *window )
{
    /* Not required at present */
}

void fgPlatformPosResZordWork( SFG_Window *window, unsigned int workMask )
{
    AUTORELEASE_POOL;

    if ( workMask & GLUT_FULL_SCREEN_WORK )
        fgPlatformFullScreenToggle( window );
    if ( workMask & GLUT_POSITION_WORK )
        fgPlatformPositionWindow( window, window->State.DesiredXpos, window->State.DesiredYpos );
    if ( workMask & GLUT_SIZE_WORK )
        fgPlatformReshapeWindow( window, window->State.DesiredWidth, window->State.DesiredHeight );
    if ( workMask & GLUT_ZORDER_WORK ) {
        if ( window->State.DesiredZOrder < 0 )
            fgPlatformPushWindow( window );
        else
            fgPlatformPopWindow( window );
    }
}

void fgPlatformVisibilityWork( SFG_Window *window )
{
    AUTORELEASE_POOL;

    SFG_Window *win = window;
    switch ( window->State.DesiredVisibility ) {
    case DesireHiddenState:
        fgPlatformHideWindow( window );
        break;
    case DesireIconicState:
        /* Call on top-level window */
        while ( win->Parent )
            win = win->Parent;
        fgPlatformIconifyWindow( win );
        break;
    case DesireNormalState:
        fgPlatformShowWindow( window );
        break;
    }
}
