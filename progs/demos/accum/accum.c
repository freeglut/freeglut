/*
 * accum.c - Accumulation Buffer Motion Blur Example
 * Written by Andrew Woods <drew.woods at gmail.com>
 *
 * Demonstrates the use of the accumulation buffer to create a motion blur
 * effect.  A line rotates about the origin, and if accumulation is enabled,
 * the intermediate passes are accumulated to create a blur effect.
 *
 * Interaction:
 * Press the following keys to change parameters:
 * 'a' or space: Toggle accumulation buffer use
 * 'y'/'h':      Increase/decrease number of accumulation passes
 * 't'/'g':      Increase/decrease line width
 * 'r'/'f':      Increase/decrease line length
 * 'e'/'d':      Increase/decrease rotation speed
 * 'p':          Pause/animate rotation
 * 'c':          Toggle deferred scaling (scale after accumulation, or during each pass)
 * 's':          Toggle line smoothing
 * 'b':          Toggle blending
 * Esc:          Exit the program
 */

#include <stdio.h>
#include <stdlib.h>

#include <GL/freeglut.h>

/* Application state */
float deg_per_frame = 6.0f;
float len           = 0.5f;
float width         = 1.0f;
int   accum         = 1;
int   accum_passes  = 8;
int   defer_scale;
int   paused;

/* Key bindings */
#define INC_PASSES_KEY  'y'
#define DEC_PASSES_KEY  'h'
#define INC_WIDTH_KEY   't'
#define DEC_WIDTH_KEY   'g'
#define INC_LEN_KEY     'r'
#define DEC_LEN_KEY     'f'
#define INC_ANGLE_KEY   'e'
#define DEC_ANGLE_KEY   'd'
#define SMOOTH_KEY      's'
#define PAUSE_KEY       'p'
#define ALT_ACCUM_KEY   ' '
#define ACCUM_KEY       'a'
#define DEFER_SCALE_KEY 'c'
#define BLEND_KEY       'b'
#define QUIT_KEY        27

void reshape( int x, int y );
void display( void );
void timer( int value );
void render_shape( void );
void show_current_configuration( void );
void keypress( unsigned char key, int x, int y );

int main( int argc, char **argv )
{
    glutInit( &argc, argv );
    glutInitDisplayMode( GLUT_RGBA | GLUT_DOUBLE | GLUT_ACCUM );
    glutInitWindowSize( 700, 700 );
    glutCreateWindow( "Accumulation Buffer Motion Blur" );

    printf( "--- Press '%c' or '%c' to toggle accumulation buffer use\n", ACCUM_KEY, ALT_ACCUM_KEY );

    glMatrixMode( GL_MODELVIEW );
    glClearColor( 0.1f, 0.1f, 0.1f, 0.0f );
    glColor3f( 0.8f, 0.6f, 0.6f );
    glEnable( GL_BLEND ); /* Needed for GL_LINE_SMOOTH */
    glBlendFunc( GL_SRC_ALPHA, GL_ONE_MINUS_SRC_ALPHA );

    glutDisplayFunc( display );
    glutReshapeFunc( reshape );
    glutKeyboardFunc( keypress );
    glutTimerFunc( 100 / 60, timer, 0 );

    glutMainLoop( );
    return 0;
}

void reshape( int x, int y )
{
    glViewport( 0, 0, x, y );
}

void timer( int value )
{
    glutPostRedisplay( );
    glutTimerFunc( 100 / 60, timer, 0 );
}

void display( void )
{
    int   i;
    float deg_per_render = accum ? deg_per_frame / accum_passes : deg_per_frame;

    /* Reverse the last frame's rotation if paused */
    if ( paused ) glRotatef( -deg_per_frame, 0.0, 0.0, 1.0 );

    glClear( GL_COLOR_BUFFER_BIT | GL_ACCUM_BUFFER_BIT );

    if ( accum ) {
        for ( i = 0; i < accum_passes; i++ ) {
            glRotatef( deg_per_render, 0.0, 0.0, 1.0 );
            render_shape( );
            glAccum( GL_ACCUM, defer_scale ? 1.0f : 1.0f / accum_passes );
            glClear( GL_COLOR_BUFFER_BIT );
        }
        /* defer scaling until after all passes are accumulated can avoid truncation but may result in overflow */
        glAccum( GL_RETURN, defer_scale ? 1.0f / accum_passes : 1.0f );
    } else {
        glRotatef( deg_per_render, 0.0, 0.0, 1.0 );
        render_shape( );
    }

    show_current_configuration( );
    glutSwapBuffers( );
}

void render_shape( void )
{
    glLineWidth( width );
    glBegin( GL_LINES );
    glVertex2f( -len, 0.0 );
    glVertex2f( len, 0.0 );
    glVertex2f( 0.0, -len );
    glVertex2f( 0.0, len );
    glEnd( );
}

void show_current_configuration( void )
{
    char  buffer[512];
    char *buf = buffer;
    GLint R = 0, G = 0, B = 0, A = 0;
    glGetIntegerv( GL_ACCUM_RED_BITS, &R );
    glGetIntegerv( GL_ACCUM_GREEN_BITS, &G );
    glGetIntegerv( GL_ACCUM_BLUE_BITS, &B );
    glGetIntegerv( GL_ACCUM_ALPHA_BITS, &A );

#define SEP "      "

    /* Row 1 */
    buf += sprintf( buf, "Accumulation Buffer Precision: %db/%db/%db/%db\n", R, G, B, A );
    /* Row 2 */
    buf += sprintf( buf, SEP "  accum (%c): %4s", ACCUM_KEY, accum ? "on" : "off" );
    buf += sprintf( buf, SEP " passes (%c/%c): %4d", INC_PASSES_KEY, DEC_PASSES_KEY, accum ? accum_passes : 1 );
    buf += sprintf( buf, SEP "    animate (%c): %4s\n", PAUSE_KEY, paused ? "off" : "on" );
    /* Row 3 */
    buf += sprintf( buf, SEP "speed (%c/%c): %4.1f", INC_ANGLE_KEY, DEC_ANGLE_KEY, deg_per_frame );
    buf += sprintf( buf, SEP "    len (%c/%c): %4.2f", INC_LEN_KEY, DEC_LEN_KEY, len );
    buf += sprintf( buf, SEP "    width (%c/%c): %4.2f\n", INC_WIDTH_KEY, DEC_WIDTH_KEY, width );
    /* Row 4 */
    buf += sprintf( buf, SEP " smooth (%c): %4s", SMOOTH_KEY, glIsEnabled( GL_LINE_SMOOTH ) ? "on" : "off" );
    buf += sprintf( buf, SEP "    blend (%c): %4s", BLEND_KEY, glIsEnabled( GL_BLEND ) ? "on" : "off" );
    buf += sprintf( buf, SEP "defer scale (%c): %4s\n", DEFER_SCALE_KEY, defer_scale ? "on" : "off" );

#undef SEP

    glPushMatrix( );
    glLoadIdentity( );
    glRasterPos2f( -0.99f, 0.90f );
    glutBitmapString( GLUT_BITMAP_8_BY_13, (unsigned char *)buffer );
    glPopMatrix( );
}

void keypress( unsigned char key, int x, int y )
{
    switch ( key ) {
    case INC_ANGLE_KEY:
        deg_per_frame += 0.5;
        break;
    case DEC_ANGLE_KEY:
        deg_per_frame -= 0.5;
        break;
    case INC_LEN_KEY:
        len = len < 1.0f ? len + 0.05f: 1.0f;
        break;
    case DEC_LEN_KEY:
        len = len > 0.05f ? len - 0.05f : 0.05f;
        break;
    case INC_WIDTH_KEY:
        width = width < 10.0f ? width + 0.5f : 10.0f;
        break;
    case DEC_WIDTH_KEY:
        width = width > 0.5f ? width - 0.5f : 0.5f;
        break;
    case INC_PASSES_KEY:
        accum_passes++;
        break;
    case DEC_PASSES_KEY:
        if ( accum_passes > 1 )
            accum_passes--;
        break;
    case PAUSE_KEY:
        paused = !paused;
        break;
    case ACCUM_KEY:
    case ALT_ACCUM_KEY:
        accum = !accum;
        break;
    case DEFER_SCALE_KEY:
        defer_scale = !defer_scale;
        break;
    case SMOOTH_KEY:
        glIsEnabled( GL_LINE_SMOOTH ) ? glDisable( GL_LINE_SMOOTH ) : glEnable( GL_LINE_SMOOTH );
        break;
    case BLEND_KEY:
        glIsEnabled( GL_BLEND ) ? glDisable( GL_BLEND ) : glEnable( GL_BLEND );
        break;

    case QUIT_KEY:
        glutLeaveMainLoop( );
        break;
    }
}
