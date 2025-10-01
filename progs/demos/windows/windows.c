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

#ifndef M_PI
#define M_PI 3.14159265358979323846
#endif

/* disable precision conversion warnings */
#ifdef _MSC_VER
#pragma warning( disable : 4305 4244 )
#endif

/* backward compatibility with GLUT for testing */
#if 1
#include <GL/freeglut.h>
#else
#include <GLUT/glut.h>
#endif

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
    printf( "GLUT_WINDOW_HEADER_HEIGHT: %d\n", glutGet( GLUT_WINDOW_HEADER_HEIGHT ) );
#endif
}

void display( void )
{
    static int frames;
    int        X, Y;

    glClearColor( 0.2, 0.2, 0.4, 1.0 );
    glClear( GL_COLOR_BUFFER_BIT );

    X = glutGet( GLUT_WINDOW_X );
    Y = glutGet( GLUT_WINDOW_Y );

    printf( "%5d: Window %d: X:%d Y:%d\n", frames, glutGetWindow( ), X, Y );
    glutPositionWindow( X, Y );

    frames++;
    glutSwapBuffers( );
}

void timer( int value )
{
    glutPostRedisplay( );
    glutTimerFunc( 1000 / 60, timer, 0 );
}

int main( int argc, char **argv )
{
    static int dim = 200;
    glutInit( &argc, argv );
    glutInitDisplayMode( GLUT_SINGLE | GLUT_RGBA );
    glutInitWindowSize( dim, dim );
    glutInitWindowPosition( 100, 100 );

    global_info( );

    glutCreateWindow( "Window A" );
    glutDisplayFunc( display );
    glutTimerFunc( 1000 / 60, timer, 0 );
    window_info( );
    glutMainLoop( );

    return 0;
}
