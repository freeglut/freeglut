/*
 * windows.c - multi-window demo for freeglut
 * Written by Andrew Woods <drew.woods at gmail.com>
 *
 * Demonstrates creating multiple freeglut windows and querying/manipulating their positions and sizes.
 *
 * This highlights some gotchas in window geometry handling
 *
 * See docs/api.md#conventions
 */

#include <assert.h>
#include <math.h>
#include <stdio.h>

#ifndef M_PI
#define M_PI 3.14159265358979323846
#endif

/* disable precision conversion warnings */
#ifdef _MSC_VER
#pragma warning( disable : 4305 4244 )
#endif

/* backward compatibility with GLUT for testing */
#if 1 && !defined( USE_GLUT )
#include <GL/freeglut.h>
#else
#include <GLUT/glut.h>
#endif

#define MIN( a, b )    ( ( a ) < ( b ) ? ( a ) : ( b ) )
#define MAX( a, b )    ( ( a ) > ( b ) ? ( a ) : ( b ) )
#define MID( a, b, c ) MIN( MAX( a, b ), c )

#define ARRAY_SIZE( a ) ( sizeof( a ) / sizeof( a[0] ) )
#define NUM_WINDOWS     ARRAY_SIZE( winGeom )
#define WIN_SZ          200

const int CALIBRATION_DELAY  = 500;
const int VELOCITY           = 3;
const int ICONIFY_TRIGGER_MS = 3 * 1000;

/* clang-format off */
int win1, win2, win3, win4, win5;
struct geom {
    int x, y;
    int w, h;
} winGeom[] = {
        { 0, 0, 0, 0 }, /* unused - GLUT window IDs start at 1 */

        /* X                Y               W            H */
        { 0,                100,            WIN_SZ,     WIN_SZ      },  /* Window 1 */
        { WIN_SZ + 50,      WIN_SZ + 100,   WIN_SZ,     WIN_SZ      },  /* Window 2 */
        { WIN_SZ + 100,     100,            WIN_SZ,     WIN_SZ / 4  },  /* Window 3 */
        { 4 * WIN_SZ,       WIN_SZ + 50,    WIN_SZ,     WIN_SZ      },  /* Window 4 */
        { 3 * WIN_SZ,       3 * WIN_SZ,     WIN_SZ/2,   WIN_SZ/2    },  /* Window 5 */
    };
/* clang-format on */

enum overlap { NO_OVERLAP, X_OVERLAP, Y_OVERLAP };

/* Returns the axis with the smaller penetration depth. */
enum overlap overlaps_axis( struct geom *a, struct geom *b )
{
    int          x_overlap, y_overlap;
    struct geom *max_x, *min_x, *max_y, *min_y;

    if ( a->x >= b->x + b->w || b->x >= a->x + a->w || a->y >= b->y + b->h || b->y >= a->y + a->h )
        return NO_OVERLAP;

    max_x = a->x > b->x ? a : b;
    min_x = a->x > b->x ? b : a;
    max_y = a->y > b->y ? a : b;
    min_y = a->y > b->y ? b : a;

    x_overlap = min_x->x + min_x->w - max_x->x;
    y_overlap = min_y->y + min_y->h - max_y->y;

    /*
     * Pick the axis with the smaller penetration depth.
     * Rationale:
     * - Resolving the smallest overlap is the minimal correction needed.
     * - In some cases only that axis represents a valid (non-spurious) overlap.
     */
    if ( x_overlap < y_overlap )
        return X_OVERLAP;

    return Y_OVERLAP;
}

void update_window_geometry( void )
{
    static int calibrated;
    static int offset_x, offset_y;

    int win = glutGetWindow( );

    assert( win < NUM_WINDOWS );

    /*
     * GLUT_WINDOW_X/Y may return the position of the window frame or the
     * content area depending on the implementation. Calibrate once at the start
     * to determine the offset
     */
    if ( !calibrated ) {
        /* Calibrate offset between actual and requested position */
        offset_x   = glutGet( GLUT_WINDOW_X ) - winGeom[win].x;
        offset_y   = glutGet( GLUT_WINDOW_Y ) - winGeom[win].y;
        calibrated = 1;
        printf( "\nCalibration: using win %d offset = (%d, %d)\n-----------\n", win, offset_x, offset_y );

        return;
    }

    winGeom[win].x = glutGet( GLUT_WINDOW_X );
    winGeom[win].y = glutGet( GLUT_WINDOW_Y );
    winGeom[win].w = glutGet( GLUT_WINDOW_WIDTH );
    winGeom[win].h = glutGet( GLUT_WINDOW_HEIGHT );
    winGeom[win].x -= offset_x;
    winGeom[win].y -= offset_y;

#ifdef FREEGLUT
    /*
     * GLUT_WINDOW_WIDTH/HEIGHT in doesn't include window frame decorations.
     * freeglut exposes this, so we adjust for that.
     */
    winGeom[win].w += 2 * glutGet( GLUT_WINDOW_BORDER_WIDTH );
    winGeom[win].h += glutGet( GLUT_WINDOW_BORDER_HEIGHT );
#endif
}

/* Move the window, bouncing off screen edges and other windows */
void move_window( int value )
{
    static int x_dir[NUM_WINDOWS];
    static int y_dir[NUM_WINDOWS];

    int win           = glutGetWindow( );
    struct geom *this = &winGeom[win];
    struct geom *other;

    int min_x = 0, min_y = 0;
    int max_x  = glutGet( GLUT_SCREEN_WIDTH ) - winGeom[win].w;
    int max_y  = glutGet( GLUT_SCREEN_HEIGHT ) - winGeom[win].h;
    int next_x = winGeom[win].x;
    int next_y = winGeom[win].y;

    /* Initialize movement directions */
    if ( !x_dir[win] || !y_dir[win] ) {
        x_dir[win] = 1;
        y_dir[win] = 1;
    }

    /* Collision detection between moving windows */
    for ( other = winGeom; other < &winGeom[NUM_WINDOWS]; other++ ) {
        if ( other == this )
            continue;
        switch ( overlaps_axis( this, other ) ) {
        case NO_OVERLAP:
            break;
        case X_OVERLAP:
            x_dir[win] = -x_dir[win];
            while ( overlaps_axis( this, other ) == X_OVERLAP )
                this->x += x_dir[win], next_x += x_dir[win];
            goto done; /* Resolve one collision per frame to prevent jitter */
        case Y_OVERLAP:
            y_dir[win] = -y_dir[win];
            while ( overlaps_axis( this, other ) == Y_OVERLAP )
                this->y += y_dir[win], next_y += y_dir[win];
            goto done; /* Resolve one collision per frame to prevent jitter */
        }
    }

    /* TODO The minimum X/Y might be larger than if the WM is using menu bars */
done:
    if ( next_x <= min_x )
        x_dir[win] = 1;
    if ( next_x >= max_x )
        x_dir[win] = -1;
    if ( next_y <= min_y )
        y_dir[win] = 1;
    if ( next_y >= max_y )
        y_dir[win] = -1;

    next_x = MID( next_x + x_dir[win] * VELOCITY, min_x, max_x );
    next_y = MID( next_y + y_dir[win] * VELOCITY, min_y, max_y );

    glutPositionWindow( next_x, next_y );
}

void display( void )
{
    static int frames[NUM_WINDOWS];
    static int starttime[NUM_WINDOWS];

    int i;
    int win = glutGetWindow( );

    char *s;
    char  title[256];

    float base_freq = 0.01f;
    float phase     = frames[win] * base_freq;
    float fps, elapsed;
    float a, b, c; /* temp variables */

    if ( !starttime[win] )
        starttime[win] = glutGet( GLUT_ELAPSED_TIME );

    elapsed = ( glutGet( GLUT_ELAPSED_TIME ) - starttime[win] ) / 1000.0f;
    fps     = frames[win] / elapsed;
    sprintf( title, "Window ID: %d, FPS %.1f", glutGetWindow( ), fps );

    glClear( GL_COLOR_BUFFER_BIT );

    /* Do something interesting on each window */
    switch ( win ) {
    case 1: /* Lissajous curve */
        glBegin( GL_LINE_STRIP );
        for ( i = 0; i <= 200; i++ ) {
            a = i * 2.0f * M_PI / 200.0f;
            b = 0.8f * sin( 7.0f * a + phase * 0.5f );
            c = 0.8f * sin( 5.0f * a );
            glColor3f( c + b + 0.5f, c + 0.5f, b + 0.5f );
            glVertex2f( b, c );
        }
        glEnd( );
        break;

    case 2: /* Spinning square */
        glRotatef( sin( phase ) * 5, 0.0f, 0.0f, 1.0f );
        glRectf( -0.5f, -0.5f, 0.5f, 0.5f );
        break;

    case 3: /* Color cycle and text */
        a = sin( phase ) * 0.5f + 0.5f;
        b = sin( phase / 2 ) * 0.5f + 0.5f;
        c = sin( phase / 3 ) * 0.5f + 0.5f;
        glColor4f( a, b, c, 1.0f );

        sprintf( title, "%.3fs: Window %d fps %.1f", elapsed, glutGetWindow( ), fps );
        glRasterPos2f( -0.95, 0 );
        for ( s = title; *s; s++ )
            glutBitmapCharacter( GLUT_BITMAP_HELVETICA_10, *s );
        break;

    case 4: /* 3D teapot flyby camera */
        glLoadIdentity( );
        a = cos( phase ) * 2.0f;
        b = cos( phase / 10 ) * 4.0f - cos( phase / 7 ) * 2.0f;
        c = sin( phase ) * 2.0f;

        gluLookAt( a, b, c, 0.0, 0.0, 0.0, 0.0, 1.0, 0.0 );
        glutWireTeapot( 1 );
        break;

    case 5: /* Color cycle background */
        a = sin( phase * 2 ) * 0.5f + 0.5f;
        b = sin( phase / 2 ) * 0.5f + 0.5f;
        c = sin( phase / 3 ) * 0.5f + 0.5f;
        glClearColor( a, b, c, 1.0f );
        break;

    default:
        assert( !"Unknown window" );
    }

    glutSetWindowTitle( title );
    frames[win]++;
    glFlush( );
}

/* Timer function to update all windows and move them around the screen */
void timer( int a )
{
    static int minimized = 0;
    int        runtime   = glutGet( GLUT_ELAPSED_TIME ) % ( 3 * ICONIFY_TRIGGER_MS );
    float      scale     = glutGet( GLUT_INIT_WINDOW_WIDTH ) / 2.0f;
    float      phase     = glutGet( GLUT_ELAPSED_TIME ) / 1000.0f;

    /* Window 1 moves around */
    if ( win1 ) {
        glutSetWindow( win1 );
        glutPostRedisplay( );
        update_window_geometry( );
        move_window( 0 );
    }

    /* Hide/Show window 2 on an trigger interval */
    if ( win2 ) {
        /*
         * t: 0            trigger          2*trigger         3*trigger
         *    |----------------|----------------|----------------|
         *         visible           hidden          visible
         */
        if ( MID( runtime, ICONIFY_TRIGGER_MS, 2 * ICONIFY_TRIGGER_MS ) == runtime && !minimized ) {
            glutSetWindow( win2 );
            glutIconifyWindow( );
            minimized = 1;
            /* Move cached geometry off-screen to prevent collisions with moving windows */
            winGeom[win2].x = -1000;
        } else if ( MID( runtime, ICONIFY_TRIGGER_MS, 2 * ICONIFY_TRIGGER_MS ) != runtime && minimized ) {
            glutSetWindow( win2 );
            glutShowWindow( );
            minimized = 0;
        }
        if ( !minimized ) {
            glutSetWindow( win2 );
            glutPostRedisplay( );
            update_window_geometry( );
        }
    }

    /* Window 3 is stationary, but you can drag it around to obstruct the moving windows */
    if ( win3 ) {
        glutSetWindow( win3 );
        glutPostRedisplay( );
        update_window_geometry( );
    }

    /* Window 4 is resized sinusoidally */
    if ( win4 ) {
        glutSetWindow( win4 );
        /* glutPostRedisplay( ); */ /* not needed, reshape triggers redisplay */
        update_window_geometry( );
        glutReshapeWindow( glutGet( GLUT_INIT_WINDOW_WIDTH ) + cos( phase ) * scale,
            glutGet( GLUT_INIT_WINDOW_HEIGHT ) + sin( phase / 3 ) * scale );
    }

    /* Window 5 moves around */
    if ( win5 ) {
        glutSetWindow( win5 );
        glutPostRedisplay( );
        update_window_geometry( );
        move_window( 0 );
    }

    glutTimerFunc( 16, timer, 0 ); /* approx 60 fps */
}

void global_info( void )
{
    int size = 0, i = 0;
    int *array = NULL;

#define PRINT_ARRAY( name, arr, sz )                        \
    printf( "  " name ":%s", sz ? "" : " Not Supported " ); \
    for ( i = 0; i < sz; i++ ) printf( " %d,", arr[i] );    \
    printf( "\b \n" );

    printf( "------------------- Global GLUT Info ------------------\n" );
    printf( "glutGet():\n");
    printf( "  GLUT_DISPLAY_MODE_POSSIBLE: %d\n", glutGet( GLUT_DISPLAY_MODE_POSSIBLE ) );
    printf( "  GLUT_INIT_DISPLAY_MODE: %d\n", glutGet( GLUT_INIT_DISPLAY_MODE ) );
    printf( "  GLUT_INIT_WINDOW_X: %d\n", glutGet( GLUT_INIT_WINDOW_X ) );
    printf( "  GLUT_INIT_WINDOW_Y: %d\n", glutGet( GLUT_INIT_WINDOW_Y ) );
    printf( "  GLUT_INIT_WINDOW_WIDTH: %d\n", glutGet( GLUT_INIT_WINDOW_WIDTH ) );
    printf( "  GLUT_INIT_WINDOW_HEIGHT: %d\n", glutGet( GLUT_INIT_WINDOW_HEIGHT ) );
    printf( "  GLUT_SCREEN_WIDTH: %d\n", glutGet( GLUT_SCREEN_WIDTH ) );
    printf( "  GLUT_SCREEN_HEIGHT: %d\n", glutGet( GLUT_SCREEN_HEIGHT ) );
    printf( "  GLUT_SCREEN_WIDTH_MM: %d\n", glutGet( GLUT_SCREEN_WIDTH_MM ) );
    printf( "  GLUT_SCREEN_HEIGHT_MM: %d\n", glutGet( GLUT_SCREEN_HEIGHT_MM ) );
#ifdef FREEGLUT
    printf( "glutGetModeValues():\n");

    array = glutGetModeValues(GLUT_AUX, &size);
    PRINT_ARRAY("GLUT_AUX", array, size);
    free(array);

    array = glutGetModeValues(GLUT_MULTISAMPLE, &size);
    PRINT_ARRAY("GLUT_MULTISAMPLE", array, size);
    free(array);
#endif
}

void window_info( void )
{
    printf( "------------------- Window %d Info ------------------\n", glutGetWindow( ) );
    printf( "  GLUT_ELAPSED_TIME: %d\n", glutGet( GLUT_ELAPSED_TIME ) );
    printf( "  GLUT_WINDOW_X: %d\n", glutGet( GLUT_WINDOW_X ) );
    printf( "  GLUT_WINDOW_Y: %d\n", glutGet( GLUT_WINDOW_Y ) );
#ifdef FREEGLUT
    printf( "  GLUT_WINDOW_BORDER_WIDTH: %d\n", glutGet( GLUT_WINDOW_BORDER_WIDTH ) );
    printf( "  GLUT_WINDOW_BORDER_HEIGHT: %d\n", glutGet( GLUT_WINDOW_BORDER_HEIGHT ) );
    assert( GLUT_WINDOW_BORDER_HEIGHT == GLUT_WINDOW_HEADER_HEIGHT ); /* both defined the same */
#endif
}

int create_window( int id )
{
    int  win;
    char title[32];

    sprintf( title, "Window %d", id );

    win = glutCreateWindow( title );
    glutPositionWindow( winGeom[win].x, winGeom[win].y );
    glutReshapeWindow( winGeom[win].w, winGeom[win].h );
    glutDisplayFunc( display ); /* display function is per window*/
    window_info( );

    /* Set up some default GL state */
    glEnable( GL_BLEND );
    glBlendFunc( GL_SRC_ALPHA, GL_ONE_MINUS_SRC_ALPHA );
    glEnable( GL_LINE_SMOOTH );

    return win;
}

int main( int argc, char **argv )
{
    glutInit( &argc, argv );
    glutInitDisplayMode( GLUT_SINGLE | GLUT_RGBA );
    glutInitWindowSize( WIN_SZ, WIN_SZ );

    global_info( );

    /*
     * --- Create multiple windows (each window has its own GL context) ---
     */

    win1 = create_window( 1 );
    glLineWidth( 3.0f ); /* only applies to window 1  */

    win2 = create_window( 2 );
    glClearColor( 0.1, 0.3, 0.3, 1.0f );

    win3 = create_window( 3 );

    win4 = create_window( 4 );
    glMatrixMode( GL_PROJECTION );
    glLoadIdentity( );
    gluPerspective( 60.0, 1.0, 1.0, 100.0 );
    glMatrixMode( GL_MODELVIEW );

    win5 = create_window( 5 );

    /*
     * --- Start event loop ---
     */

    /* Timer function is global, not per window */
    glutTimerFunc( CALIBRATION_DELAY, timer, 0 ); /* Delay for initial positioning */
    glutMainLoop( );

    return 0;
}
