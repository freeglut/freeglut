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

static int *appendIfUnique( int *array, int *arr_size, int value )
{
    for ( int i = 0; i < *arr_size; i++ ) {
        if ( array[i] == value )
            return array; /* already present */
    }

    int *new_array = realloc( array, sizeof( int ) * ( *arr_size + 1 ) );
    if ( !new_array ) {
        fgWarning( "%s(): memory allocation failure", __FUNCTION__ );
        return array;
    }

    new_array[( *arr_size )++] = value;
    return new_array;
}

int fgPlatformGlutDeviceGet( GLenum eWhat )
{
    AUTORELEASE_POOL;

    switch ( eWhat ) {
    case GLUT_HAS_KEYBOARD:
        return 1;

    case GLUT_HAS_MOUSE:
        return 1;

    case GLUT_NUM_MOUSE_BUTTONS:
        return 3; // left (click), middle (click+options), right (click+ctrl)

    default:
        fgWarning( "glutDeviceGet(): unhandled enum %d", eWhat );
        break;
    }
    return 0;
}

int fgPlatformGlutGet( GLenum eWhat )
{
    AUTORELEASE_POOL;

    if ( !fgStructure.CurrentWindow )
        return 0;

    NSWindow *win = (NSWindow *)fgStructure.CurrentWindow->Window.Handle;

    switch ( eWhat ) {

    /*
     * Note: Returns the content area position, not the window frame position.
     * This differs from the original GLUT behavior. See docs/api.md#conventions
     */
    case GLUT_WINDOW_X:
    case GLUT_WINDOW_Y: {
        NSRect frame   = [win frame];
        NSRect content = [win contentRectForFrameRect:frame];
        if ( eWhat == GLUT_WINDOW_X )
            return content.origin.x;
        else
            return fgDisplay.ScreenHeight - content.origin.y - content.size.height;
    }

    case GLUT_WINDOW_BORDER_WIDTH:
        /* Returns the width of the left or right border */
        {
            NSRect frame   = [win frame];
            NSRect content = [win contentRectForFrameRect:frame];
            /* Assume the left and right borders are the same size */
            return ( frame.size.width - content.size.width ) / 2;
        }

    case GLUT_WINDOW_HEADER_HEIGHT:
        /* Returns the height of the title bar */
        {
            NSRect frame   = [win frame];
            NSRect content = [win contentRectForFrameRect:frame];
            return frame.size.height - content.size.height;
        }

    case GLUT_WINDOW_WIDTH:
    case GLUT_WINDOW_HEIGHT: {
        NSRect frame = [win contentRectForFrameRect:[win frame]];
        if ( eWhat == GLUT_WINDOW_WIDTH )
            return frame.size.width;
        else
            return frame.size.height;
    }

    case GLUT_WINDOW_COLORMAP_SIZE:
        /* macOS typically uses 32-bit RGBA color, so no color map */
        return 0;

    case GLUT_WINDOW_NUM_SAMPLES: {
        NSOpenGLPixelFormat *pixelFormat = fgStructure.CurrentWindow->Window.pContext.PixelFormat;
        GLint                samples     = 0;
        [pixelFormat getValues:&samples forAttribute:NSOpenGLPFASamples forVirtualScreen:0];
        return samples;
    }

    case GLUT_WINDOW_RGBA: {
        /* All macOS contexts are RGBA */
        return 1;
    }

    case GLUT_WINDOW_DOUBLEBUFFER: {
        NSOpenGLPixelFormat *pixelFormat = fgStructure.CurrentWindow->Window.pContext.PixelFormat;
        GLint                value       = 0;
        [pixelFormat getValues:&value forAttribute:NSOpenGLPFADoubleBuffer forVirtualScreen:0];
        return value;
    }

    case GLUT_WINDOW_BUFFER_SIZE: {
        NSOpenGLPixelFormat *pixelFormat = fgStructure.CurrentWindow->Window.pContext.PixelFormat;
        GLint                colorSize   = 0;
        [pixelFormat getValues:&colorSize forAttribute:NSOpenGLPFAColorSize forVirtualScreen:0];
        return colorSize;
    }

    case GLUT_WINDOW_STENCIL_SIZE: {
        NSOpenGLPixelFormat *pixelFormat = fgStructure.CurrentWindow->Window.pContext.PixelFormat;
        GLint                value       = 0;
        [pixelFormat getValues:&value forAttribute:NSOpenGLPFAStencilSize forVirtualScreen:0];
        return value;
    }

    case GLUT_WINDOW_DEPTH_SIZE: {
        NSOpenGLPixelFormat *pixelFormat = fgStructure.CurrentWindow->Window.pContext.PixelFormat;
        GLint                value       = 0;
        [pixelFormat getValues:&value forAttribute:NSOpenGLPFADepthSize forVirtualScreen:0];
        return value;
    }

    case GLUT_WINDOW_RED_SIZE:
    case GLUT_WINDOW_GREEN_SIZE:
    case GLUT_WINDOW_BLUE_SIZE: {
        NSOpenGLPixelFormat *pixelFormat = fgStructure.CurrentWindow->Window.pContext.PixelFormat;
        GLint                colorSize   = 0;
        [pixelFormat getValues:&colorSize forAttribute:NSOpenGLPFAColorSize forVirtualScreen:0];
        /* Assuming equal distribution for RGBA components */
        return colorSize / 4;
    }

    case GLUT_WINDOW_ALPHA_SIZE: {
        NSOpenGLPixelFormat *pixelFormat = fgStructure.CurrentWindow->Window.pContext.PixelFormat;
        GLint                value       = 0;
        [pixelFormat getValues:&value forAttribute:NSOpenGLPFAAlphaSize forVirtualScreen:0];
        return value;
    }

    case GLUT_WINDOW_ACCUM_RED_SIZE:
    case GLUT_WINDOW_ACCUM_GREEN_SIZE:
    case GLUT_WINDOW_ACCUM_BLUE_SIZE:
    case GLUT_WINDOW_ACCUM_ALPHA_SIZE: {
        NSOpenGLPixelFormat *pixelFormat = fgStructure.CurrentWindow->Window.pContext.PixelFormat;
        GLint                accumSize   = 0;
        [pixelFormat getValues:&accumSize forAttribute:NSOpenGLPFAAccumSize forVirtualScreen:0];
        /* Assuming equal distribution for RGBA components in accumulation buffer */
        return accumSize / 4;
    }

    case GLUT_WINDOW_STEREO: {
        NSOpenGLPixelFormat *pixelFormat = fgStructure.CurrentWindow->Window.pContext.PixelFormat;
        GLint                value       = 0;
        [pixelFormat getValues:&value forAttribute:NSOpenGLPFAStereo forVirtualScreen:0];
        return value;
    }

    case GLUT_WINDOW_CURSOR: {
        return fgStructure.CurrentWindow->State.Cursor;
    }

    case GLUT_WINDOW_SRGB: {
        /* macOS does not have a specific sRGB flag */
        return 0;
    }
    case GLUT_DISPLAY_MODE_POSSIBLE:
        /* TODO: Query fgState.ContextFlags to determine if display mode config is possible for now assume it is */
        return 1;

    case GLUT_WINDOW_FORMAT_ID:
        /* macOS does not have a specific format ID */
        return 0;

    default:
        fgWarning( "glutGet(): missing enum handle %d", eWhat );
        break;
    }

    return -1;
}

int *fgPlatformGlutGetModeValues( GLenum eWhat, int *size )
{
    AUTORELEASE_POOL;

    int *supportedModes = NULL;

    if ( !size ) {
        fgError( "fgPlatformGlutGetModeValues: size pointer is NULL" );
        return NULL;
    }

    switch ( eWhat ) {
    case GLUT_AUX: {
        /*
         * Query available auxiliary buffer counts.
         *
         * Cocoa typically supports up to 2 aux buffers, but we will check up to
         * MAX_AUX_BUFFERS
         */

        const int MAX_AUX_BUFFERS = 4;

        /* The "query" is really enumerating pixel formats and record the ones that work */
        for ( int auxCount = 0; auxCount <= MAX_AUX_BUFFERS; auxCount++ ) {
            NSOpenGLPixelFormatAttribute attrs[] = { NSOpenGLPFAAccelerated, NSOpenGLPFAAuxBuffers, auxCount, 0 };

            NSOpenGLPixelFormat *pixelFormat = [[NSOpenGLPixelFormat alloc] initWithAttributes:attrs];
            if ( pixelFormat != nil ) {
                GLint actualAuxBuffers = 0;
                [pixelFormat getValues:&actualAuxBuffers forAttribute:NSOpenGLPFAAuxBuffers forVirtualScreen:0];
                supportedModes = appendIfUnique( supportedModes, size, actualAuxBuffers );
                [pixelFormat release];
            }
        }

        break;
    }

    case GLUT_MULTISAMPLE: {
        /*
         * Query available MSAA sample counts
         *
         * Unfortunately, Modern macOS GPUs only support up to 4 sample counts.
         * But will check common counts for older hardware and hopeful future
         * support
         */
        int queryModes[] = { 0, 1, 2, 4, 8, 16, 32, 64 };
        int queryCnt     = sizeof( queryModes ) / sizeof( queryModes[0] );

        /* The "query" is really enumerating pixel formats and record the ones that work */
        for ( int i = 0; i < queryCnt; i++ ) {
            NSOpenGLPixelFormatAttribute attrs[] = { NSOpenGLPFAAccelerated,
                NSOpenGLPFAMultisample,
                NSOpenGLPFASampleBuffers,
                1,
                NSOpenGLPFASamples,
                queryModes[i],
                0 };

            NSOpenGLPixelFormat *pixelFormat = [[NSOpenGLPixelFormat alloc] initWithAttributes:attrs];
            if ( pixelFormat != nil ) {
                GLint actualSamples = 0;
                [pixelFormat getValues:&actualSamples forAttribute:NSOpenGLPFASamples forVirtualScreen:0];
                supportedModes = appendIfUnique( supportedModes, size, actualSamples );
                [pixelFormat release];
            }
        }

        break;
    }

    default:
        fgWarning( "glutGetModeValues: not implemented for %d", eWhat );
        break;
    }

    return supportedModes;
}
