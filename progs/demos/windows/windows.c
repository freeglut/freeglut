/*
 * window_position.c - demonstrates FreeGLUT window positioning calibration
 * Shows how to handle offset between requested and actual window position
 */

#include <assert.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

/* backward compatibility with GLUT for testing */
#if 1 && !defined( USE_GLUT )
#include <GL/freeglut.h>
#else
#include <GLUT/glut.h>
#endif

static int target_x = 100, target_y = 100;
static int offset_x, offset_y;
static int calibrated;

const int CALIBRATION_DELAY = 500;
const int TIMER_INTERVAL    = 33;

void print_position_info( const char *title )
{
    printf( "\n--- %s --- \n", title );
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
    printf( "GLUT_WINDOW_BORDER_HEIGHT: %d\n", glutGet( GLUT_WINDOW_BORDER_HEIGHT ) );
    printf( "GLUT_WINDOW_HEADER_HEIGHT: %d\n", glutGet( GLUT_WINDOW_HEADER_HEIGHT ) );
    assert( GLUT_WINDOW_BORDER_HEIGHT == GLUT_WINDOW_HEADER_HEIGHT ); /* freeglut synonym */
#endif
}

void display( void )
{
    static int  stable_count = 0;
    static char prev_info[256];

    int x     = glutGet( GLUT_WINDOW_X );
    int y     = glutGet( GLUT_WINDOW_Y );
    int adj_x = x - offset_x;
    int adj_y = y - offset_y;

    char *p;
    char  info[256];

    if ( calibrated )
        sprintf(
            info, "glutGet(x,y): %d,%d | Adjusted: %d,%d | Target: %d,%d", x, y, adj_x, adj_y, target_x, target_y );
    else
        sprintf( info, "Calibrating..." );

    /* Log position errors only after they've been stable for a few frames. GLUT has no
     * explicit ordering between the window positioning and the display callback,
     * so we may get transient incorrect readings during movement. */
    if ( strcmp( prev_info, info ) == 0 ) {
        stable_count++;
    } else {
        stable_count = 0;
        strncpy( prev_info, info, sizeof( prev_info ) );
    }

    /* Color feedback: red if off-target, green if correct */
    if ( ( x != target_x || y != target_y ) && stable_count >= 2 )
        glColor3f( 1.0f, 0.0f, 0.0f );
    else
        glColor3f( 0.8f, 1.0f, 0.8f );

    if ( calibrated && ( x != target_x || y != target_y ) && stable_count == 2 ) {
        printf( "%.3f: Position mismatch - %s\n", glutGet( GLUT_ELAPSED_TIME ) / 1000.0f, info );
    }

    /* Render */
    glClear( GL_COLOR_BUFFER_BIT );
    glRasterPos2f( -0.95f, 0.0f );
    for ( p = info; *p; p++ )
        glutBitmapCharacter( GLUT_BITMAP_HELVETICA_10, *p );
    glFlush( );
}

void timer( int value )
{
    if ( !calibrated ) {
        /* Calibrate offset between requested and actual position */
        offset_x   = glutGet( GLUT_WINDOW_X ) - target_x;
        offset_y   = glutGet( GLUT_WINDOW_Y ) - target_y;
        calibrated = 1;
        printf( "\nCalibration: offset = (%d, %d)\n-----------\n", offset_x, offset_y );
    }

    glutPositionWindow( target_x - offset_x, target_y - offset_y );
    glutPostRedisplay( );
    glutTimerFunc( TIMER_INTERVAL, timer, 0 );
}

void keyboard( unsigned char key, int x, int y )
{
    int delta = 10;

    if ( glutGetModifiers( ) & GLUT_ACTIVE_ALT ) {
        delta = 1;
    }

    /* Don't allow position changes until after calibration */
    if ( !calibrated )
        return;

    switch ( key ) {
    case 'w':
        target_y -= delta;
        break;
    case 's':
        target_y += delta;
        break;
    case 'a':
        target_x -= delta;
        break;
    case 'd':
        target_x += delta;
        break;
    case 27:
        exit( 0 );
        break;
    }
}

int main( int argc, char **argv )
{
    glutInit( &argc, argv );
    glutInitWindowPosition( target_x, target_y );
    glutCreateWindow( "Position Calibration Demo" );
    glutPositionWindow( target_x, target_y );

    glutDisplayFunc( display );
    glutKeyboardFunc( keyboard );
    glutTimerFunc( CALIBRATION_DELAY, timer, 0 ); /* Delay for initial positioning */

    print_position_info( "Initial Window Info" );
    printf( "\nUsage:\n-----\nUse W/A/S/D to adjust target position, ESC to quit, ALT for fine adjustments\n" );
    glutMainLoop( );
    return 0;
}
