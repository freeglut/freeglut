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

#define CALIBRATING_MSG "Calibrating window position..."
#define UNSTABLE_MSG    "Unstable window position... :("
#define STABLE_MSG      "Stable window position!"
#define HELP_MSG        "Press arrow keys to adjust target position."

/* Move window slowly for unstable */
const int UNSTABLE_TIMER_INTERVAL = 300;
const int STABLE_TIMER_INTERVAL   = 30;

/* Only run calibration after window is positioned */
const int CALIBRATION_DELAY = 1000;

static int TARGET_X, TARGET_Y;
static int OFFSET_X, OFFSET_Y;

static char STATUS_STR[256] = CALIBRATING_MSG;
static char TARGET_STR[256];
static char HELP_STR[256];

static float STATUS_MSG_COLOR[3] = { 0.8f, 0.4f, 0.1f };
static float TARGET_MSG_COLOR[3] = { 0.7, 0.7f, 0.7f };

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

void adjust_target_position( int key, int x, int y )
{
    switch ( key ) {
    case GLUT_KEY_UP:
        TARGET_Y -= 1;
        break;
    case GLUT_KEY_DOWN:
        TARGET_Y += 1;
        break;
    case GLUT_KEY_LEFT:
        TARGET_X -= 1;
        break;
    case GLUT_KEY_RIGHT:
        TARGET_X += 1;
        break;
    default:
        break;
    }
}

void validate_position( int value )
{
    static int i;
    int        x, y, adjusted_x, adjusted_y;
    int        next_timer;
    float      luma = fabs( sin( glutGet( GLUT_ELAPSED_TIME ) / 500.0f ) ) * 0.5f + 0.5f;

    x = glutGet( GLUT_WINDOW_X );
    y = glutGet( GLUT_WINDOW_Y );

    /*
     * See docs/api.md#conventions for details details
     */
    adjusted_x = x - OFFSET_X;
    adjusted_y = y - OFFSET_Y;

    sprintf( TARGET_STR,
        "Pos:%d,%d | Adj:%d,%d | Tgt:%d,%d | Off:%d,%d | Err:%d,%d",
        x,
        y,
        adjusted_x,
        adjusted_y,
        TARGET_X,
        TARGET_Y,
        OFFSET_X,
        OFFSET_Y,
        adjusted_x - TARGET_X,
        adjusted_y - TARGET_Y );

    if ( TARGET_X == adjusted_x && TARGET_Y == adjusted_y ) {
        strncpy( STATUS_STR, STABLE_MSG, sizeof( STATUS_STR ) );
        next_timer          = STABLE_TIMER_INTERVAL;
        STATUS_MSG_COLOR[0] = luma, STATUS_MSG_COLOR[1] = luma, STATUS_MSG_COLOR[2] = luma;
    }
    else {
        strncpy( STATUS_STR, UNSTABLE_MSG, sizeof( STATUS_STR ) );
        STATUS_MSG_COLOR[0] = luma, STATUS_MSG_COLOR[1] = 0.3f * luma, STATUS_MSG_COLOR[2] = 0.0f;
        next_timer = UNSTABLE_TIMER_INTERVAL;
        printf( "%2d: %s\n", i++, TARGET_STR );
    }

    glutPostRedisplay( );
    glutTimerFunc( next_timer, validate_position, 0 );

    glutPositionWindow( TARGET_X, TARGET_Y );
}

/*
 * FreeGLUT may return the content offset or the frame offset. So in order to
 * position a window at an exact screen position, we need to calibrate the
 * offset between the requested position and the actual position.
 *
 * See docs/api.md#conventions for details details
 */
void calibrate( int value )
{
    int x = glutGet( GLUT_WINDOW_X );
    int y = glutGet( GLUT_WINDOW_Y );

    OFFSET_X = x - TARGET_X;
    OFFSET_Y = y - TARGET_Y;

    printf( "\n==== Calibration complete ====\n" );
    printf( " --> glutGet(GLUT_WINDOW_X)    %3d (EXPECTED: %d, OFFSET:%d)\n", x, TARGET_X, OFFSET_X );
    printf( " --> glutGet(GLUT_WINDOW_Y)    %3d (EXPECTED: %d, OFFSET:%d)\n", y, TARGET_Y, OFFSET_Y );

    /* only enable arrow key adjustments after calibration */
    glutSpecialFunc( adjust_target_position );

    /* update help message and fire off timer */
    strncpy( HELP_STR, HELP_MSG, sizeof( HELP_STR ) );
    glutTimerFunc( 0, validate_position, 0 );
}

void display( void )
{
    char *p;

    glClear( GL_COLOR_BUFFER_BIT );

    glColor3fv( STATUS_MSG_COLOR );
    glRasterPos2f( -0.95, 0 );
    for ( p = STATUS_STR; *p; p++ )
        glutBitmapCharacter( GLUT_BITMAP_HELVETICA_18, *p );

    glColor3fv( TARGET_MSG_COLOR );
    glRasterPos2f( -0.95, -0.2 );
    for ( p = TARGET_STR; *p; p++ )
        glutBitmapCharacter( GLUT_BITMAP_HELVETICA_10, *p );

    glRasterPos2f( -0.95, -0.4 );
    for ( p = HELP_STR; *p; p++ )
        glutBitmapCharacter( GLUT_BITMAP_HELVETICA_10, *p );

    glFlush( );
}

int main( int argc, char **argv )
{
    glutInit( &argc, argv );
    TARGET_X = 100, TARGET_Y = 100;
    glutInitWindowPosition( TARGET_X, TARGET_Y );

    glutCreateWindow( "Window A" );
    glutPositionWindow( TARGET_X, TARGET_Y );

    print_position_info( "Initial window info" );

    glutTimerFunc( CALIBRATION_DELAY, calibrate, 0 );
    glutDisplayFunc( display );
    glutMainLoop( );

    return 0;
}
