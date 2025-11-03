/*
 * windows.c - multi-window demo for freeglut
 * Written by Andrew Woods <drew.woods at gmail.com>
 *
 * Demonstrates creating multiple freeglut windows and querying/manipulating their positions and sizes.
 *
 * This highlights some gotchas in window geometry handling, especially in freeglut vs original GLUT.
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

#define MAX_WINDOWS 6

#define MIN( a, b )    ( ( a ) < ( b ) ? ( a ) : ( b ) )
#define MAX( a, b )    ( ( a ) > ( b ) ? ( a ) : ( b ) )
#define MID( a, b, c ) MIN( MAX( a, b ), c )

int win1, win2, win3, win4, win5;
struct geom {
    int x, y;
    int w, h;
} winGeom[MAX_WINDOWS];

enum overlap { NO_OVERLAP, X_OVERLAP, Y_OVERLAP };

enum overlap overlaps( struct geom *a, struct geom *b )
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

void get_geom( void )
{
    int win = glutGetWindow( );
    assert( win < MAX_WINDOWS );
    winGeom[win].x = glutGet( GLUT_WINDOW_X );
    winGeom[win].y = glutGet( GLUT_WINDOW_Y );
    winGeom[win].w = glutGet( GLUT_WINDOW_WIDTH );
    winGeom[win].h = glutGet( GLUT_WINDOW_HEIGHT );
#ifdef FREEGLUT
    /*
     * In freeglut, glutGet( GLUT_WINDOW_Y ) returns the content offset from
     * the top of the screen, not the window frame offset. Adjust for that.
     *
     * This differs from the original GLUT behavior. See docs/api.md#conventions
     */
    winGeom[win].y -= glutGet( GLUT_WINDOW_BORDER_HEIGHT );

    /*
     * In freeglut and the original GLUT, glutGet( GLUT_WINDOW_WIDTH/HEIGHT )
     * returns the content size, not the window frame size. Adjust for that.
     *
     * Unlike GLUT, freeglut provides GLUT_WINDOW_BORDER_WIDTH/HEIGHT
     */
    winGeom[win].w += 2 * glutGet( GLUT_WINDOW_BORDER_WIDTH );
    winGeom[win].h += glutGet( GLUT_WINDOW_BORDER_HEIGHT );
#endif
}

/* Move the window, bouncing off screen edges and other windows */
void move_window( int value )
{
    static int XDir[MAX_WINDOWS];
    static int YDir[MAX_WINDOWS];
    static int spd = 3;

    int win           = glutGetWindow( );
    struct geom *this = &winGeom[win];
    struct geom *other;

    int maxX = glutGet( GLUT_SCREEN_WIDTH ) - glutGet( GLUT_WINDOW_WIDTH );
    int maxY = glutGet( GLUT_SCREEN_HEIGHT ) - glutGet( GLUT_WINDOW_HEIGHT );
    int X    = winGeom[win].x;
    int Y    = winGeom[win].y;
#ifdef FREEGLUT
    maxX -= glutGet( GLUT_WINDOW_BORDER_WIDTH ) * 2;
    maxY -= glutGet( GLUT_WINDOW_BORDER_HEIGHT );
#endif

    /* Initialize movement directions */
    if ( !XDir[win] || !YDir[win] ) {
        XDir[win] = 1;
        YDir[win] = 1;
    }

    /* Collision detection between moving windows */
    for ( other = winGeom; other < &winGeom[MAX_WINDOWS]; other++ ) {
        if ( other == this )
            continue;
        switch ( overlaps( this, other ) ) {
        case NO_OVERLAP:
            break;
        case X_OVERLAP:
            XDir[win] = -XDir[win];
            while ( overlaps( this, other ) == X_OVERLAP )
                this->x += XDir[win], X += XDir[win];
            goto done; /* Only handle one window overlap per move */
        case Y_OVERLAP:
            YDir[win] = -YDir[win];
            while ( overlaps( this, other ) == Y_OVERLAP )
                this->y += YDir[win], Y += YDir[win];
            goto done; /* Only handle one window overlap per move */
        }
    }

done:
    if ( X <= 0 )
        XDir[win] = 1;
    if ( X >= maxX )
        XDir[win] = -1;
    if ( Y <= 0 )
        YDir[win] = 1;
    if ( Y >= maxY )
        YDir[win] = -1;

    Y = MID( Y + YDir[win] * spd, 0, maxY );
    X = MID( X + XDir[win] * spd, 0, maxX );

    glutPositionWindow( X, Y );
}

void display( void )
{
    static int frames[MAX_WINDOWS];
    static int starttime[MAX_WINDOWS];

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

    case 4: /* 3D teapot flyby camera and window resizing */
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
    static int minimized           = 0;
    const int  minimized_threshold = 3 * 1000;
    int        runtime             = glutGet( GLUT_ELAPSED_TIME ) % ( 3 * minimized_threshold );
    float      scale               = glutGet( GLUT_INIT_WINDOW_WIDTH ) / 2.0f;
    float      phase               = glutGet( GLUT_ELAPSED_TIME ) / 1000.0f;

    if ( win1 ) {
        /* Window 1 moves around */
        glutSetWindow( win1 );
        glutPostRedisplay( );
        get_geom( );
        move_window( 0 );
    }

    if ( win2 ) {
        /* Hide/Show window 2 on an interval */
        if ( MID( runtime, minimized_threshold, 2 * minimized_threshold ) == runtime && !minimized ) {
            glutSetWindow( win2 );
            glutIconifyWindow( );
            minimized       = 1;
            winGeom[win2].x = -1000;
        }
        else if ( runtime > 2 * minimized_threshold && minimized ) {
            glutSetWindow( win2 );
            glutShowWindow( );
            minimized = 0;
        }
        if ( !minimized ) {
            glutSetWindow( win2 );
            glutPostRedisplay( );
            get_geom( );
        }
    }

    if ( win3 ) {
        glutSetWindow( win3 );
        glutPostRedisplay( );
        get_geom( );
    }

    if ( win4 ) {
        /* Window 4 is resized sinusoidally */
        glutSetWindow( win4 );
        /* glutPostRedisplay( ); */ /* not needed, reshape triggers redisplay */
        get_geom( );
        glutReshapeWindow( glutGet( GLUT_INIT_WINDOW_WIDTH ) + cos( phase ) * scale,
            glutGet( GLUT_INIT_WINDOW_HEIGHT ) + sin( phase / 3 ) * scale );
    }

    if ( win5 ) {
        /* Window 5 moves around */
        glutSetWindow( win5 );
        glutPostRedisplay( );
        get_geom( );
        move_window( 0 );
    }

    glutTimerFunc( 16, timer, 0 ); /* approx 60 fps */
}

void global_info( void )
{
    printf( "------------------- Global GLUT Info ------------------\n" );
    printf( "GLUT_SCREEN_WIDTH: %d\n", glutGet( GLUT_SCREEN_WIDTH ) );
    printf( "GLUT_SCREEN_HEIGHT: %d\n", glutGet( GLUT_SCREEN_HEIGHT ) );
    printf( "GLUT_SCREEN_WIDTH_MM: %d\n", glutGet( GLUT_SCREEN_WIDTH_MM ) );
    printf( "GLUT_SCREEN_HEIGHT_MM: %d\n", glutGet( GLUT_SCREEN_HEIGHT_MM ) );
    printf( "GLUT_DISPLAY_MODE_POSSIBLE: %d\n", glutGet( GLUT_DISPLAY_MODE_POSSIBLE ) );
    printf( "GLUT_INIT_DISPLAY_MODE: %d\n", glutGet( GLUT_INIT_DISPLAY_MODE ) );
}

void window_info( void )
{
    printf( "------------------- Window %d Info ------------------\n", glutGetWindow( ) );
    printf( "GLUT_INIT_DISPLAY_MODE: %d\n", glutGet( GLUT_INIT_DISPLAY_MODE ) );
    printf( "GLUT_INIT_WINDOW_X: %d\n", glutGet( GLUT_INIT_WINDOW_X ) );
    printf( "GLUT_INIT_WINDOW_Y: %d\n", glutGet( GLUT_INIT_WINDOW_Y ) );
    printf( "GLUT_WINDOW_X: %d\n", glutGet( GLUT_WINDOW_X ) );
    printf( "GLUT_WINDOW_Y: %d\n", glutGet( GLUT_WINDOW_Y ) );
    printf( "GLUT_INIT_WINDOW_WIDTH: %d\n", glutGet( GLUT_INIT_WINDOW_WIDTH ) );
    printf( "GLUT_INIT_WINDOW_HEIGHT: %d\n", glutGet( GLUT_INIT_WINDOW_HEIGHT ) );
    printf( "GLUT_ELAPSED_TIME: %d\n", glutGet( GLUT_ELAPSED_TIME ) );
#ifdef FREEGLUT
    printf( "GLUT_WINDOW_BORDER_WIDTH: %d\n", glutGet( GLUT_WINDOW_BORDER_WIDTH ) );
    assert( GLUT_WINDOW_BORDER_HEIGHT == GLUT_WINDOW_HEADER_HEIGHT ); /* both defined the same */
    printf( "GLUT_WINDOW_BORDER_HEIGHT: %d\n", glutGet( GLUT_WINDOW_BORDER_HEIGHT ) );
#endif

    glEnable( GL_BLEND );
    glBlendFunc( GL_SRC_ALPHA, GL_ONE_MINUS_SRC_ALPHA );
    glEnable( GL_LINE_SMOOTH );
}

int main( int argc, char **argv )
{
    static int dim = 200;
    glutInit( &argc, argv );
    glutInitDisplayMode( GLUT_SINGLE | GLUT_RGBA );
    glutInitWindowSize( dim, dim );

    global_info( );

    /* Window 1 */
    glutInitWindowPosition( 0, 10 );
    win1 = glutCreateWindow( "Window 1" );
    glutDisplayFunc( display ); /* display function is per window*/
    window_info( );
    glLineWidth( 3.0f ); /* only applies to window 1 (each window has its own GL context) */

    /* Window 2 */
    glutInitWindowPosition( dim + 50, dim + 100 );
    win2 = glutCreateWindow( "Window 2" );
    glutDisplayFunc( display );
    window_info( );
    glClearColor( 0.1, 0.3, 0.3, 1.0f ); /* set window 2 clear color to something other than black */

    /* Window 3 */
    glutInitWindowPosition( dim + 100, 100 );
    win3 = glutCreateWindow( "Window 3" );
    glutReshapeWindow( dim, dim / 4 );
    glutDisplayFunc( display );
    window_info( );

    /* Window 4 */
    glutInitWindowPosition( 4 * dim, dim + 50 );
    win4 = glutCreateWindow( "Window 4" );
    glutDisplayFunc( display );
    window_info( );
    /* Set the projection matrix for window 4 */
    glMatrixMode( GL_PROJECTION );
    glLoadIdentity( );
    gluPerspective( 60.0, 1.0, 1.0, 100.0 );
    glMatrixMode( GL_MODELVIEW );

    /* Window 5 */
    glutInitWindowPosition( 2 * dim + 100, 3 * dim + 100 );
    win5 = glutCreateWindow( "Window 5" );
    glutReshapeWindow( dim / 2, dim / 2 );
    glutDisplayFunc( display );
    window_info( );

    glutSetIconTitle( "Icon Title" );

    /* Timer function is global, not per window */
    glutTimerFunc( 0, timer, 0 );
    glutMainLoop( );

    return 0;
}
