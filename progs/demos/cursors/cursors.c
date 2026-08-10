/*
 * cursors.c - Resize cursors demo.
 * Written by Andrew Woods <drew.woods at gmail.com>
 *
 * Demonstrates freeglut resize cursors with a resizable rectangle.  Resize the
 * rectangle by dragging its edges and corners, or click its centre to spin it!
 */

#include <stdlib.h>

#include <GL/freeglut.h>

/* Use macros for constants to support MSVC */
#define GRAB_WIDTH     0.06f
#define BORDER_WIDTH   0.03f
#define MIN_SIZE       ( 4.0f * GRAB_WIDTH )
#define MAX_EDGE       ( 1.0f - GRAB_WIDTH - BORDER_WIDTH )
#define SPIN_MS        1500.0f
#define SPIN_ROTATIONS 1

typedef struct
{
    float l, t, r, b;
} Rect;

/* The protagonist rectangle's dimensions */
static Rect g_rect = { -0.5f, 0.5f, 0.5f, -0.5f };

/*
 * Drag state set when mouse clicked.
 * GLUT_CURSOR_INHERIT means that no edge is being dragged.
 * The g_dragCursor value is also used to determine the drag action.
 */
static int   g_dragCursor = GLUT_CURSOR_INHERIT;
static Rect  g_dragRect;
static float g_dragX, g_dragY;

static int   g_isSpinning = 0;
static float g_spinAngle  = 0.0f;

static const float CLR_COL[]    = {0.10f, 0.10f, 0.20f, 1.0f};
static const float RECT_COL[]   = {0.25f, 0.30f, 0.40f};
static const float TEXT_COL[]   = {0.55f, 0.60f, 0.70f};
static const float BORDER_COL[] = {0.70f, 0.75f, 0.85f};

void display( void );
void mouse( int button, int state, int x, int y );
void motion( int x, int y );
void keyboard( unsigned char key, int x, int y );

int main( int argc, char **argv )
{
    glutInit( &argc, argv );
    glutInitDisplayMode( GLUT_RGB | GLUT_DOUBLE );
    glutInitWindowSize( 400, 400 );
    glutCreateWindow( "resize cursors demo" );

    glutDisplayFunc( display );
    glutMouseFunc( mouse );
    glutMotionFunc( motion );
    glutPassiveMotionFunc( motion );
    glutKeyboardFunc( keyboard );

    glutMainLoop( );
    return EXIT_SUCCESS;
}

/* Convert window pixels to clip coordinates. */
void toClip( int x, int y, float *cx, float *cy )
{
    *cx = 2.0f * x / glutGet( GLUT_WINDOW_WIDTH  ) - 1.0f;
    *cy = 1.0f - 2.0f * y / glutGet( GLUT_WINDOW_HEIGHT );
}

/* Choose the appropriate cursor for a point based on its position relative to
 * the rectangle. */
int resizeCursorAt( float x, float y )
{
    int onLeft, onRight, onTop, onBottom;

    if( x < g_rect.l - GRAB_WIDTH || x > g_rect.r + GRAB_WIDTH ||
        y < g_rect.b - GRAB_WIDTH || y > g_rect.t + GRAB_WIDTH )
        return GLUT_CURSOR_INHERIT;

    onLeft   = ( x <= g_rect.l + GRAB_WIDTH );
    onRight  = ( x >= g_rect.r - GRAB_WIDTH );
    onTop    = ( y >= g_rect.t - GRAB_WIDTH );
    onBottom = ( y <= g_rect.b + GRAB_WIDTH );

    /* Corners win over edges */
    if( onTop    && onLeft  ) return GLUT_CURSOR_TOP_LEFT_CORNER;
    if( onTop    && onRight ) return GLUT_CURSOR_TOP_RIGHT_CORNER;
    if( onBottom && onLeft  ) return GLUT_CURSOR_BOTTOM_LEFT_CORNER;
    if( onBottom && onRight ) return GLUT_CURSOR_BOTTOM_RIGHT_CORNER;
    if( onTop               ) return GLUT_CURSOR_TOP_SIDE;
    if( onBottom            ) return GLUT_CURSOR_BOTTOM_SIDE;
    if( onLeft              ) return GLUT_CURSOR_LEFT_SIDE;
    if( onRight             ) return GLUT_CURSOR_RIGHT_SIDE;

    return GLUT_CURSOR_CYCLE;
}

float clamp( float v, float lo, float hi )
{
    return v < lo ? lo : ( v > hi ? hi : v );
}

/* Resize by applying deltas to the starting rectangle, clamp to keep it on-screen. */
void applyDrag( float x, float y )
{
    float dx = x - g_dragX;
    float dy = y - g_dragY;
    Rect  r  = g_dragRect;

    if( g_dragCursor == GLUT_CURSOR_LEFT_SIDE ||
        g_dragCursor == GLUT_CURSOR_TOP_LEFT_CORNER ||
        g_dragCursor == GLUT_CURSOR_BOTTOM_LEFT_CORNER )
        r.l = clamp( r.l + dx, -MAX_EDGE, r.r - MIN_SIZE );

    if( g_dragCursor == GLUT_CURSOR_RIGHT_SIDE ||
        g_dragCursor == GLUT_CURSOR_TOP_RIGHT_CORNER ||
        g_dragCursor == GLUT_CURSOR_BOTTOM_RIGHT_CORNER )
        r.r = clamp( r.r + dx, r.l + MIN_SIZE, MAX_EDGE );

    if( g_dragCursor == GLUT_CURSOR_TOP_SIDE ||
        g_dragCursor == GLUT_CURSOR_TOP_LEFT_CORNER ||
        g_dragCursor == GLUT_CURSOR_TOP_RIGHT_CORNER )
        r.t = clamp( r.t + dy, r.b + MIN_SIZE, MAX_EDGE );

    if( g_dragCursor == GLUT_CURSOR_BOTTOM_SIDE ||
        g_dragCursor == GLUT_CURSOR_BOTTOM_LEFT_CORNER ||
        g_dragCursor == GLUT_CURSOR_BOTTOM_RIGHT_CORNER )
        r.b = clamp( r.b + dy, -MAX_EDGE, r.t - MIN_SIZE );

    g_rect = r;
}

/* Calculates the angle of the spinning rectangle. Called from a timer when the
 * center of the rentangle is clicked */
void animateSpin( int start )
{
    float elapsed = (float)( glutGet( GLUT_ELAPSED_TIME ) - start );
    float t       = elapsed / SPIN_MS;

    /*
     * For something more exciting than lerp, we use linearly decreasing velocity:
     *     v(t) = 1 - t
     *
     * Integrating gives the rotation progress:
     *     t - t^2 / 2 = t(1 - t/2)
     *
     * But this only reaches 1/2 at t = 1, so normalize it by multiplying by 2:
     *     t(2 - t)
     *
     * Note: To keep the hit detection simple, we want the rectangle to spin a
     *       multiple of a full rotation.
     */

    if( t < 1.0f )
    {
        g_spinAngle = t * ( 2.0f - t ) * SPIN_ROTATIONS * 360.0f;
        glutTimerFunc( 16, animateSpin, start );
    }
    else
    {
        g_isSpinning = 0;
        g_spinAngle  = 0.0f;
    }

    glutPostRedisplay( );
}

void display( void )
{
    float w = (float)glutGet( GLUT_WINDOW_WIDTH  );
    float h = (float)glutGet( GLUT_WINDOW_HEIGHT );

    glClearColor( CLR_COL[0], CLR_COL[1], CLR_COL[2], CLR_COL[3] );
    glClear( GL_COLOR_BUFFER_BIT );

    /*
     * Rotate the rectangle about its center.  Also scale by the aspect ratio to
     * prevent eye-bleeding sheering with non-square windows.
     *
     * Note: the scale is only needed because our project matrix is always 1 x 1
     * regarless of the actual window aspect.  This means rotation requires
     * scaling, but makes for simpler hit and raster positions and ensures
     * the whole rectangle is always inside the window.
     */
    glPushMatrix( );
        glTranslatef( ( g_rect.l + g_rect.r ) * 0.5f,
                      ( g_rect.b + g_rect.t ) * 0.5f, 0.0f );
        glScalef( 1.0f / w, 1.0f / h, 1.0f );
        glRotatef( g_spinAngle, 0.0f, 0.0f, 1.0f );
        glScalef( w, h, 1.0f );
        glTranslatef( -( g_rect.l + g_rect.r ) * 0.5f,
                      -( g_rect.b + g_rect.t ) * 0.5f, 0.0f );

        glColor3fv( BORDER_COL );
        glRectf( g_rect.l - BORDER_WIDTH, g_rect.b - BORDER_WIDTH,
                 g_rect.r + BORDER_WIDTH, g_rect.t + BORDER_WIDTH );

        glColor3fv( RECT_COL );
        glRectf( g_rect.l, g_rect.b, g_rect.r, g_rect.t );
    glPopMatrix( );

    glColor3fv( TEXT_COL );
    glRasterPos2f( -0.95f, 0.88f );
    glutBitmapString( GLUT_BITMAP_HELVETICA_12,
                      (const unsigned char *)
                      "Drag an edge or a corner to resize\n"
                      "Click the middle to spin\n"
                      "q or ESC to quit" );

    glutSwapBuffers( );
}

void motion( int x, int y )
{
    float cx, cy;
    int   cursor;

    if( g_isSpinning )
        return;

    toClip( x, y, &cx, &cy );
    cursor = resizeCursorAt( cx, cy );

    if( g_dragCursor != GLUT_CURSOR_INHERIT )
        applyDrag( cx, cy );

    glutSetCursor( g_dragCursor != GLUT_CURSOR_INHERIT ? g_dragCursor : cursor );
    glutPostRedisplay( );
}

void mouse( int button, int state, int x, int y )
{
    float cx, cy;
    int   cursor;

    if( button != GLUT_LEFT_BUTTON )
        return;

    if( g_isSpinning )
        return;

    toClip( x, y, &cx, &cy );
    cursor = resizeCursorAt( cx, cy );

    if( state == GLUT_DOWN && cursor == GLUT_CURSOR_CYCLE )
    {
        g_isSpinning = 1; /* whee! */
        glutTimerFunc( 0, animateSpin, glutGet( GLUT_ELAPSED_TIME ) );
    }
    else if( state == GLUT_DOWN && cursor != GLUT_CURSOR_INHERIT )
    {
        g_dragCursor = cursor;
        g_dragRect   = g_rect;
        g_dragX      = cx;
        g_dragY      = cy;
    }
    else if( state == GLUT_UP )
        g_dragCursor = GLUT_CURSOR_INHERIT;

    glutSetCursor( g_dragCursor != GLUT_CURSOR_INHERIT ? g_dragCursor : cursor );
}

void keyboard( unsigned char key, int x, int y )
{
    if( key == 27 || key == 'q' || key == 'Q' )
        exit( EXIT_SUCCESS );
}
