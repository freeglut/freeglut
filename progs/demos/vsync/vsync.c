#include <stdio.h>
#include <string.h>
#include <time.h>

#include <GL/freeglut.h>

#define ARR_LEN( arr ) ( sizeof( arr ) / sizeof( arr[0] ) )

static unsigned long last_switch;
static unsigned      frames;
static char          buf[1024] = "VSync test:\n"
                                 "===========\n"
                                 "In a correctly synchronized double-buffered environment:\n"
                                 " - The screen should completely fill with blocks\n"
                                 " - The two buffers will each contain half the blocks (in a checkerboard pattern)\n"
                                 " - Colors will appear to flash rapidly as the buffers alternate\n"
                                 " - Frame rate should match the monitor's refresh rate\n"
                                 "\n"
                                 "In environments with improper vsync you'll observe:\n"
                                 " - Missing blocks\n"
                                 " - Only one of the two buffers may be visible\n"
                                 " - Inconsistent rendering across the screen\n "
                                 "\n";

static char *msg = NULL;

static int   idx          = 0;
static float colours[][3] = {
    { 0.6f, 0.0f, 0.0f }, /* Red */
    { 0.0f, 0.0f, 0.6f }, /* Blue */
    { 0.0f, 0.6f, 0.6f }, /* Cyan */
    { 0.6f, 0.6f, 0.0f }, /* Yellow */
};

enum { ROWS = 16, COLS = ROWS, BLOCKS_PER_SCREEN = ( ROWS * COLS ) };

static unsigned long get_time_ns( )
{
#ifdef CLOCK_MONOTONIC
    struct timespec now;
    clock_gettime( CLOCK_MONOTONIC, &now );
    return now.tv_nsec + now.tv_sec * 1e9;
#elif defined( HAVE_GETTIMEOFDAY )
    struct timeval now;
    gettimeofday( &now, NULL );
    return now.tv_usec * 1000 + now.tv_sec * 1e9;
#else
    return 0;
#endif
}

static void draw_block( void )
{
    GLint row = ( frames / COLS ) % ROWS;
    GLint col = frames % COLS;

    if ( ( col + row ) & 1 )
        glColor3fv( &colours[idx][0] );
    else
        glColor3fv( &colours[idx ^ 1][0] );

    glRecti( col, row, col + 1, row + 1 );
}

/* Update the block colors when we reach the end of a screen */
static void updateColours( void )
{
    unsigned long now;
    float         dur_ms, avg_ms;

    if ( frames % BLOCKS_PER_SCREEN == 0 ) {
        idx = ( idx + 2 ) % ARR_LEN( colours ); /* switch to next color pair */

        now    = get_time_ns( );
        dur_ms = ( now - last_switch ) / 1e6;
        avg_ms = dur_ms / BLOCKS_PER_SCREEN;
        sprintf( msg, "Switching colours -- avg frametime %.2fms (%.1fFPS)\n", avg_ms, 1e3 / avg_ms );
        printf( "%s", msg );
        glutSetWindowTitle( msg );
        last_switch = now;
    }
}

static void display( void )
{
    last_switch = last_switch ?: get_time_ns( );
    msg         = msg ?: buf + strlen( buf );

    glColor3f( 1.0f, 1.0f, 1.0f );
    glRasterPos2i( 0, ROWS - 1 );
    glutBitmapString( GLUT_BITMAP_8_BY_13, (unsigned char *)buf );

    draw_block( );

    frames++;

    updateColours( );

    glutSwapBuffers( );
}

static void reshape( int width, int height )
{
    glMatrixMode( GL_PROJECTION );
    glLoadIdentity( );
    glOrtho( 0, COLS, 0, ROWS, -1, 1 );

    glMatrixMode( GL_MODELVIEW );
    glLoadIdentity( );
}

static void keyboard( unsigned char key, int x, int y )
{
    switch ( key ) {
    case 27: /* ESC */
        exit( 0 );
        break;
    default:
        break;
    }
}

int main( int argc, char **argv )
{
    printf( "%s", buf );

    glutInit( &argc, argv );
    glutInitDisplayMode( GLUT_DOUBLE | GLUT_RGB );
    glutInitWindowSize( 600, 600 );
    glutCreateWindow( argv[0] );
    glutDisplayFunc( display );
    glutReshapeFunc( reshape );
    glutIdleFunc( display );
    glutKeyboardFunc( keyboard );

    glutMainLoop( );
    return 0;
}