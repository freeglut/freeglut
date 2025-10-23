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
#include <stdlib.h>
#include <string.h>

/* backward compatibility with GLUT for testing */
#if 1 && !defined( USE_GLUT )
#include <GL/freeglut.h>
#else
#include <GLUT/glut.h>
#endif

#define UNSTABLE_MSG "Unstable window position... :("
#define STABLE_MSG   "Stable window position!"

/* Move window slowly for unstable */
const int UNSTABLE_TIMER_INTERVAL = 300;
const int STABLE_TIMER_INTERVAL   = 30;

static int   EXPECTED_X, EXPECTED_Y;
static char  STATUS_MSG[256] = UNSTABLE_MSG;
static float MSG_COLOR[3]    = { 1.0f, 0.0f, 0.0f };

void print_position_info( const char *msg )
{
    printf( "\n==== %s ====\n", msg );
    printf( "GLUT_INIT_DISPLAY_MODE:        %d\n", glutGet( GLUT_INIT_DISPLAY_MODE ) );
    printf( "GLUT_INIT_WINDOW_X:            %d\n", glutGet( GLUT_INIT_WINDOW_X ) );
    printf( "GLUT_INIT_WINDOW_Y:            %d\n", glutGet( GLUT_INIT_WINDOW_Y ) );
    printf( "GLUT_WINDOW_X:                 %d\n", glutGet( GLUT_WINDOW_X ) );
    printf( "GLUT_WINDOW_Y:                 %d\n", glutGet( GLUT_WINDOW_Y ) );
    printf( "GLUT_INIT_WINDOW_WIDTH:        %d\n", glutGet( GLUT_INIT_WINDOW_WIDTH ) );
    printf( "GLUT_INIT_WINDOW_HEIGHT:       %d\n", glutGet( GLUT_INIT_WINDOW_HEIGHT ) );
    printf( "GLUT_ELAPSED_TIME:             %d\n", glutGet( GLUT_ELAPSED_TIME ) );
#ifdef FREEGLUT
    printf( "GLUT_WINDOW_BORDER_WIDTH:      %d\n", glutGet( GLUT_WINDOW_BORDER_WIDTH ) );
    printf( "GLUT_WINDOW_BORDER_HEIGHT:     %d\n", glutGet( GLUT_WINDOW_BORDER_HEIGHT ) );
    printf( "GLUT_WINDOW_HEADER_HEIGHT:     %d\n", glutGet( GLUT_WINDOW_HEADER_HEIGHT ) );
    assert( GLUT_WINDOW_BORDER_HEIGHT == GLUT_WINDOW_HEADER_HEIGHT ); /* freeglut synonym */
#endif
}

void timer( int value )
{
    static int i;
    int        x, y;
    int        next_timer;
    float      luma = fabs( sin( glutGet( GLUT_ELAPSED_TIME ) / 500.0f ) ) * 0.5f + 0.5f;

    x = glutGet( GLUT_WINDOW_X );
    y = glutGet( GLUT_WINDOW_Y );

    /*
     * FreeGLUT returns the content offset, not the frame offset as used in
     * original GLUT and in PositionWindow.
     *
     * See docs/api.md#conventions for details details
     */
#if defined( FREEGLUT )
    x -= 2 * glutGet( GLUT_WINDOW_BORDER_WIDTH );
    y -= glutGet( GLUT_WINDOW_BORDER_HEIGHT );
#endif

    if ( EXPECTED_X == x && EXPECTED_Y == y ) {
        strncpy( STATUS_MSG, STABLE_MSG, sizeof( STATUS_MSG ) );
        next_timer   = STABLE_TIMER_INTERVAL;
        MSG_COLOR[0] = luma, MSG_COLOR[1] = luma, MSG_COLOR[2] = luma;
    }
    else {
        strncpy( STATUS_MSG, UNSTABLE_MSG, sizeof( STATUS_MSG ) );
        printf( "----- Iteration %d -----\n", i++ );
        printf( " --> glutGet(GLUT_WINDOW_X)    %3d (EXPECTED: %d, delta:%d)\n", x, EXPECTED_X, x - EXPECTED_X );
        printf( " --> glutGet(GLUT_WINDOW_Y)    %3d (EXPECTED: %d, delta:%d)\n", y, EXPECTED_Y, y - EXPECTED_Y );
        printf( " --> glutPositionWindow( %d, %d )\n", x, y );

        MSG_COLOR[0] = luma, MSG_COLOR[1] = 0.3f * luma, MSG_COLOR[2] = 0.0f;
        next_timer = UNSTABLE_TIMER_INTERVAL;
    }

    glutPostRedisplay( );
    glutTimerFunc( next_timer, timer, 0 );

    glutPositionWindow( x, y );
    EXPECTED_X = x, EXPECTED_Y = y;
}

void display( void )
{
    char *p;

    glClear( GL_COLOR_BUFFER_BIT );
    glRasterPos2f( -0.90, 0 );

    glColor3fv( MSG_COLOR );
    for ( p = STATUS_MSG; *p; p++ )
        glutBitmapCharacter( GLUT_BITMAP_HELVETICA_18, *p );
    glFlush( );
}

int main( int argc, char **argv )
{
    glutInit( &argc, argv );
    glutInitWindowPosition( 0, 0 );

    glutCreateWindow( "Window A" );
    print_position_info( "Initial window info" );

    glutTimerFunc( 0, timer, 0 );
    glutDisplayFunc( display );
    glutMainLoop( );

    return 0;
}
