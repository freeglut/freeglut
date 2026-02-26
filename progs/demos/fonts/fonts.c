/*
 * fonts.c - Bitmap Character Table Demo
 * Written by Andrew Woods <drew.woods at gmail.com>
 *
 * Displays all 256 characters (0x00-0xFF) for each bitmap font
 * in a 16-column x 16-row grid with hex row/column labels.
 *
 * Controls:
 *   n / N   - Next / Previous font
 *   q / ESC - Quit
 */

#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <stdarg.h>

#include <GL/freeglut.h>

/* ======================================================================== */

struct FontEntry {
    void       *font;
    const char *name;
};

static struct FontEntry fonts[] = {
    { GLUT_BITMAP_8_BY_13, "GLUT_BITMAP_8_BY_13" },
    { GLUT_BITMAP_9_BY_15, "GLUT_BITMAP_9_BY_15" },
    { GLUT_BITMAP_TIMES_ROMAN_10, "GLUT_BITMAP_TIMES_ROMAN_10" },
    { GLUT_BITMAP_TIMES_ROMAN_24, "GLUT_BITMAP_TIMES_ROMAN_24" },
    { GLUT_BITMAP_HELVETICA_10, "GLUT_BITMAP_HELVETICA_10" },
    { GLUT_BITMAP_HELVETICA_12, "GLUT_BITMAP_HELVETICA_12" },
    { GLUT_BITMAP_HELVETICA_18, "GLUT_BITMAP_HELVETICA_18" },
};

static const int NUM_FONTS    = sizeof( fonts ) / sizeof( fonts[0] );
static int       current_font = 0;

static int win_width  = 900;
static int win_height = 700;

static const int MARGIN_LEFT   = 70;
static const int MARGIN_TOP    = 80; /* Leave space for HUD */
static const int MARGIN_RIGHT  = 20;
static const int MARGIN_BOTTOM = 20;
static const int HUD_HEIGHT    = 36;

static const float CONTROL_CLR[]  = { 0.7f, 0.45f, 0.45f };
static const float ASCII_CLR[]    = { 0.95f, 0.95f, 0.95f };
static const float EXT_CTRL_CLR[] = { 0.5f, 0.4f, 0.2f };
static const float EXTENDED_CLR[] = { 0.6, 0.9, 0.6f };

/* ======================================================================== */

void bitmapPrintf( float x, float y, void *font, const char *fmt, ... );
void reshape( int w, int h );
void keyboard( unsigned char key, int x, int y );
void display( void );

int main( int argc, char **argv )
{
    glutInit( &argc, argv );
    glutInitDisplayMode( GLUT_DOUBLE | GLUT_RGB );
    glutInitWindowSize( win_width, win_height );
    glutCreateWindow( "freeglut Bitmap Character Table" );

    glutDisplayFunc( display );
    glutReshapeFunc( reshape );
    glutKeyboardFunc( keyboard );

    glutMainLoop( );
    return 0;
}

/* ======================================================================== */

void bitmapPrintf( float x, float y, void *font, const char *fmt, ... )
{
    char    buf[256];
    va_list args;
    va_start( args, fmt );
#if defined( WIN32 ) && !defined( __CYGWIN__ )
    (void)_vsnprintf( buf, sizeof( buf ), fmt, args );
#else
    (void)vsnprintf( buf, sizeof( buf ), fmt, args );
#endif
    va_end( args );

    glRasterPos2f( x, y );
    glutBitmapString( font, (const unsigned char *)buf );
}

void reshape( int w, int h )
{
    win_width  = w;
    win_height = h;

    glMatrixMode( GL_PROJECTION );
    glLoadIdentity( );
    glOrtho( 0, win_width, 0, win_height, -1, 1 );
    glMatrixMode( GL_MODELVIEW );
    glLoadIdentity( );
    glViewport( 0, 0, w, h );
    glutPostRedisplay( );
}

void keyboard( unsigned char key, int x, int y )
{
    switch ( key ) {
    case 'n':
        current_font = ( current_font + 1 ) % NUM_FONTS;
        break;
    case 'N':
        current_font = ( current_font - 1 + NUM_FONTS ) % NUM_FONTS;
        break;
    case 'q':
    case 'Q':
    case 27:
        exit( 0 );
        break;
    }

    glutPostRedisplay( );
}

void display( void )
{
    int           col, row, rowIdx;
    int           char_w, char_h;
    float         cell_w, cell_h;
    float         grid_x0, grid_y0;
    float         x, y, off_x, off_y;
    unsigned char c;

    glClearColor( 0.1f, 0.1f, 0.1f, 1.0f );
    glClear( GL_COLOR_BUFFER_BIT );

    grid_x0 = MARGIN_LEFT;
    grid_y0 = MARGIN_BOTTOM;
    cell_w  = ( win_width - MARGIN_LEFT - MARGIN_RIGHT ) / 16.0f;
    cell_h  = ( win_height - MARGIN_TOP - MARGIN_BOTTOM ) / 16.0f;

    /* ---- HUD bar ------------------------------------------------- */
    glColor3f( 0.18f, 0.18f, 0.22f );
    glBegin( GL_QUADS );
    glVertex2f( 0, win_height );
    glVertex2f( win_width, win_height );
    glVertex2f( win_width, win_height - HUD_HEIGHT );
    glVertex2f( 0, win_height - HUD_HEIGHT );
    glEnd( );

    glColor3f( 0.3f, 0.85f, 0.95f );
    bitmapPrintf( 10,
        win_height - 22,
        GLUT_BITMAP_9_BY_15,
        "n/N = next/prev font, q = quit | Font [%d/%d]: ",
        current_font + 1,
        NUM_FONTS );
    glutBitmapString( fonts[current_font].font, (const unsigned char *)fonts[current_font].name );

    /* ---- Legend --------------------------------------------------- */
    y = grid_y0 + 16 * cell_h + 26.0f;
    glColor3fv( CONTROL_CLR );
    bitmapPrintf( grid_x0, y, GLUT_BITMAP_8_BY_13, "Control" );
    glColor3fv( ASCII_CLR );
    bitmapPrintf( grid_x0 + 80, y, GLUT_BITMAP_8_BY_13, "ASCII" );
    glColor3fv( EXT_CTRL_CLR );
    bitmapPrintf( grid_x0 + 140, y, GLUT_BITMAP_8_BY_13, "Ext Ctrl" );
    glColor3fv( EXTENDED_CLR );
    bitmapPrintf( grid_x0 + 220, y, GLUT_BITMAP_8_BY_13, "Extended" );

    /* ---- Column headers (0-F) ------------------------------------ */
    glColor3f( 0.9f, 0.75f, 0.3f );
    for ( col = 0; col < 16; col++ ) {
        x = grid_x0 + col * cell_w + cell_w * 0.5f - 4.0f;
        y = grid_y0 + 16 * cell_h + 8.0f;
        bitmapPrintf( x, y, GLUT_BITMAP_9_BY_15, "%X", col );
    }

    /* ---- Row labels (0x00, 0x10, ..., 0xF0) ---------------------- */
    for ( row = 0; row < 16; row++ ) {
        rowIdx = 15 - row;
        y      = grid_y0 + row * cell_h + cell_h * 0.5f - 5.0f;
        bitmapPrintf( 10, y, GLUT_BITMAP_9_BY_15, "0x%02X", rowIdx * 16 );
    }

    /* ---- Grid lines ---------------------------------------------- */
    glColor3f( 0.25f, 0.25f, 0.30f );
    glBegin( GL_LINES );
    for ( col = 0; col <= 16; col++ ) {
        x = grid_x0 + col * cell_w;
        glVertex2f( x, grid_y0 );
        glVertex2f( x, grid_y0 + 16 * cell_h );
    }
    for ( row = 0; row <= 16; row++ ) {
        y = grid_y0 + row * cell_h;
        glVertex2f( grid_x0, y );
        glVertex2f( grid_x0 + 16 * cell_w, y );
    }
    glEnd( );

    /* ---- Character cells ----------------------------------------- */
    for ( row = 0; row < 16; row++ ) {
        for ( col = 0; col < 16; col++ ) {
            rowIdx = 15 - row;
            c      = (unsigned char)( rowIdx * 16 + col );

            x = grid_x0 + col * cell_w + cell_w * 0.5f;
            y = grid_y0 + row * cell_h + cell_h * 0.5f;

            if ( c < 0x20 || c == 0x7F )
                glColor3fv( CONTROL_CLR );
            else if ( c >= 0x20 && c <= 0x7E )
                glColor3fv( ASCII_CLR );
            else if ( c >= 0x80 && c <= 0x9F )
                glColor3fv( EXT_CTRL_CLR );
            else
                glColor3fv( EXTENDED_CLR );

            char_w = glutBitmapWidth( fonts[current_font].font, c );
            char_h = glutBitmapHeight( fonts[current_font].font );
            off_x  = (float)char_w * 0.5f;
            off_y  = (float)char_h * 0.25;
            glRasterPos2f( x - off_x, y - off_y );
            glutBitmapCharacter( fonts[current_font].font, c );
        }
    }

    glutSwapBuffers( );
}
