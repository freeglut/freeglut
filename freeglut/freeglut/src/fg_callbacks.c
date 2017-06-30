/*
 * fg_callbacks.c
 *
 * The callbacks setting methods.
 *
 * Copyright (c) 1999-2000 Pawel W. Olszta. All Rights Reserved.
 * Written by Pawel W. Olszta, <olszta@sourceforge.net>
 * Creation date: Fri Dec 3 1999
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL
 * PAWEL W. OLSZTA BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

#include <GL/freeglut.h>
#include "fg_internal.h"

/* -- INTERFACE FUNCTIONS -------------------------------------------------- */


/*
 * Global callbacks.
 */
/* Sets the global idle callback */
void FGAPIENTRY glutIdleFuncUcall( FGCBIdleUC callback, FGCBUserData userData )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutIdleFuncUcall" );
    fgState.IdleCallback = callback;
    fgState.IdleCallbackData = userData;
}

static void fghIdleFuncCallback( FGCBUserData userData )
{
    FGCBIdle callback = (FGCBIdle)userData;
    callback();
}

void FGAPIENTRY glutIdleFunc( FGCBIdle callback )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutIdleFunc" );
    if( callback )
        glutIdleFuncUcall( fghIdleFuncCallback, (FGCBUserData)callback );
    else
        glutIdleFuncUcall( NULL, NULL );
}

/* Creates a timer and sets its callback */
void FGAPIENTRY glutTimerFuncUcall( unsigned int timeOut, FGCBTimerUC callback, int timerID, FGCBUserData userData )
{
    SFG_Timer *timer, *node;

    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutTimerFuncUcall" );

    if( (timer = fgState.FreeTimers.Last) )
    {
        fgListRemove( &fgState.FreeTimers, &timer->Node );
    }
    else
    {
        if( ! (timer = malloc(sizeof(SFG_Timer))) )
            fgError( "Fatal error: "
                     "Memory allocation failure in glutTimerFunc()" );
    }

    timer->Callback     = callback;
    timer->CallbackData = userData;
    timer->ID           = timerID;
    timer->TriggerTime  = fgElapsedTime() + timeOut;

    /* Insert such that timers are sorted by end-time */
    for( node = fgState.Timers.First; node; node = node->Node.Next )
    {
        if( node->TriggerTime > timer->TriggerTime )
            break;
    }

    fgListInsert( &fgState.Timers, &node->Node, &timer->Node );
}

static void fghTimerFuncCallback( int ID, FGCBUserData userData )
{
    FGCBTimer callback = (FGCBTimer)userData;
    callback( ID );
}

void FGAPIENTRY glutTimerFunc( unsigned int timeOut, FGCBTimer callback, int timerID )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutTimerFunc" );
    if( callback )
        glutTimerFuncUcall( timeOut, fghTimerFuncCallback, timerID, (FGCBUserData)callback );
    else
        glutTimerFuncUcall( timeOut, NULL, timerID, NULL );
}

/* Deprecated version of glutMenuStatusFunc callback setting method */
void FGAPIENTRY glutMenuStateFunc( FGCBMenuState callback )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutMenuStateFunc" );
    fgState.MenuStateCallback = callback;
}

/* Sets the global menu status callback for the current window */
void FGAPIENTRY glutMenuStatusFuncUcall( FGCBMenuStatusUC callback, FGCBUserData userData )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutMenuStatusFuncUcall" );
    fgState.MenuStatusCallback = callback;
    fgState.MenuStatusCallbackData = userData;
}

static void fghMenuStatusFuncCallback( int menuState, int mouseX, int mouseY, FGCBUserData userData )
{
    FGCBMenuStatus callback = (FGCBMenuStatus)userData;
    callback( menuState, mouseX, mouseY );
}

void FGAPIENTRY glutMenuStatusFunc( FGCBMenuStatus callback )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutMenuStatusFunc" );
    if( callback )
        glutMenuStatusFuncUcall( fghMenuStatusFuncCallback, (FGCBUserData)callback );
    else
        glutMenuStatusFuncUcall( NULL, NULL );
}

/*
 * Menu specific callbacks.
 */
/* Callback upon menu destruction */
void FGAPIENTRY glutMenuDestroyFuncUcall( FGCBDestroyUC callback, FGCBUserData userData )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutMenuDestroyFuncUcall" );
    if( fgStructure.CurrentMenu )
    {
        fgStructure.CurrentMenu->Destroy = callback;
        fgStructure.CurrentMenu->DestroyData = userData;
    }
}

static void fghMenuDestroyFuncCallback( FGCBUserData userData )
{
    FGCBDestroy callback = (FGCBDestroy)userData;
    callback();
}

void FGAPIENTRY glutMenuDestroyFunc( FGCBDestroy callback )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutMenuDestroyFunc" );
    if( callback )
        glutMenuDestroyFuncUcall( fghMenuDestroyFuncCallback, (FGCBUserData)callback );
    else
        glutMenuDestroyFuncUcall( NULL, NULL );
}


/*
 * All of the window-specific callbacks setting methods can be generalized to this:
 */
#define SET_CALLBACK(a)                                         \
do                                                              \
{                                                               \
    if( fgStructure.CurrentWindow == NULL )                     \
        return;                                                 \
    SET_WCB( ( *( fgStructure.CurrentWindow ) ), a, callback, userData ); \
} while( 0 )
/*
 * Types need to be defined for callbacks. It's not ideal, but it works for this.
 */
#define IMPLEMENT_CALLBACK_FUNC_CB_ARG0(a,b)                    \
static void fgh##a##FuncCallback( FGCBUserData userData )      \
{                                                               \
    FGCB##b callback = (FGCB##b)userData;                       \
    callback();                                                 \
}
#define IMPLEMENT_CALLBACK_FUNC_CB_ARG1(a,b)                    \
static void fgh##a##FuncCallback( int arg1val, FGCBUserData userData ) \
{                                                               \
    FGCB##b callback = (FGCB##b)userData;                       \
    callback( arg1val );                                        \
}
#define IMPLEMENT_CALLBACK_FUNC_CB_ARG2(a,b)                    \
static void fgh##a##FuncCallback( int arg1val, int arg2val, FGCBUserData userData ) \
{                                                               \
    FGCB##b callback = (FGCB##b)userData;                       \
    callback( arg1val, arg2val );                               \
}
#define IMPLEMENT_CALLBACK_FUNC_CB_ARG3_USER(a,b,arg1,arg2,arg3) \
static void fgh##a##FuncCallback( arg1 arg1val, arg2 arg2val, arg3 arg3val, FGCBUserData userData ) \
{                                                               \
    FGCB##b callback = (FGCB##b)userData;                       \
    callback( arg1val, arg2val, arg3val );                      \
}
#define IMPLEMENT_CALLBACK_FUNC_CB_ARG3(a,b) IMPLEMENT_CALLBACK_FUNC_CB_ARG3_USER(a,b,int,int,int)
#define IMPLEMENT_CALLBACK_FUNC_CB_ARG4(a,b)                    \
static void fgh##a##FuncCallback( int arg1val, int arg2val, int arg3val, int arg4val, FGCBUserData userData ) \
{                                                               \
    FGCB##b callback = (FGCB##b)userData;                       \
    callback( arg1val, arg2val, arg3val, arg4val );             \
}
#define IMPLEMENT_CALLBACK_FUNC_CB_ARG5(a,b)                    \
static void fgh##a##FuncCallback( int arg1val, int arg2val, int arg3val, int arg4val, int arg5val, FGCBUserData userData ) \
{                                                               \
    FGCB##b callback = (FGCB##b)userData;                       \
    callback( arg1val, arg2val, arg3val, arg4val, arg5val );    \
}
/*
 * And almost every time the callback setter function can be implemented like this:
 */
#define IMPLEMENT_CALLBACK_FUNC_2NAME_GLUT(a,b)                 \
void FGAPIENTRY glut##a##FuncUcall( FGCB##b##UC callback, FGCBUserData userData ) \
{                                                               \
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glut"#a"FuncUcall" );   \
    SET_CALLBACK( b );                                          \
}                                                               \
void FGAPIENTRY glut##a##Func( FGCB##b callback )               \
{                                                               \
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glut"#a"Func" );        \
    if( callback )                                              \
        glut##a##FuncUcall( fgh##a##FuncCallback, (FGCBUserData)callback ); \
    else                                                        \
        glut##a##FuncUcall( NULL, NULL ); \
}
/*
 * Combine _glut and _cb macros:
 */
#define IMPLEMENT_CALLBACK_FUNC_ARG0(a)                         \
        IMPLEMENT_CALLBACK_FUNC_CB_ARG0(a,a)                    \
        IMPLEMENT_CALLBACK_FUNC_2NAME_GLUT(a,a)

#define IMPLEMENT_CALLBACK_FUNC_ARG0_2NAME(a,b)                 \
        IMPLEMENT_CALLBACK_FUNC_CB_ARG0(a,b)                    \
        IMPLEMENT_CALLBACK_FUNC_2NAME_GLUT(a,b)

#define IMPLEMENT_CALLBACK_FUNC_ARG1(a)                         \
        IMPLEMENT_CALLBACK_FUNC_CB_ARG1(a,a)                    \
        IMPLEMENT_CALLBACK_FUNC_2NAME_GLUT(a,a)

#define IMPLEMENT_CALLBACK_FUNC_ARG2(a)                         \
        IMPLEMENT_CALLBACK_FUNC_CB_ARG2(a,a)                    \
        IMPLEMENT_CALLBACK_FUNC_2NAME_GLUT(a,a)

#define IMPLEMENT_CALLBACK_FUNC_ARG2_2NAME(a,b)                 \
        IMPLEMENT_CALLBACK_FUNC_CB_ARG2(a,b)                    \
        IMPLEMENT_CALLBACK_FUNC_2NAME_GLUT(a,b)

#define IMPLEMENT_CALLBACK_FUNC_ARG3(a)                         \
        IMPLEMENT_CALLBACK_FUNC_CB_ARG3(a,a)                    \
        IMPLEMENT_CALLBACK_FUNC_2NAME_GLUT(a,a)

#define IMPLEMENT_CALLBACK_FUNC_ARG3_USER(a,arg1,arg2,arg3)     \
        IMPLEMENT_CALLBACK_FUNC_CB_ARG3_USER(a,a,arg1,arg2,arg3)\
        IMPLEMENT_CALLBACK_FUNC_2NAME_GLUT(a,a)

#define IMPLEMENT_CALLBACK_FUNC_ARG4(a)                         \
        IMPLEMENT_CALLBACK_FUNC_CB_ARG4(a,a)                    \
        IMPLEMENT_CALLBACK_FUNC_2NAME_GLUT(a,a)

#define IMPLEMENT_CALLBACK_FUNC_ARG5(a)                         \
        IMPLEMENT_CALLBACK_FUNC_CB_ARG5(a,a)                    \
        IMPLEMENT_CALLBACK_FUNC_2NAME_GLUT(a,a)

/* Implement all these callback setter functions... */
IMPLEMENT_CALLBACK_FUNC_ARG2(Position)
IMPLEMENT_CALLBACK_FUNC_ARG3_USER(Keyboard,unsigned char,int,int)
IMPLEMENT_CALLBACK_FUNC_ARG3_USER(KeyboardUp,unsigned char,int,int)
IMPLEMENT_CALLBACK_FUNC_ARG3(Special)
IMPLEMENT_CALLBACK_FUNC_ARG3(SpecialUp)
IMPLEMENT_CALLBACK_FUNC_ARG4(Mouse)
IMPLEMENT_CALLBACK_FUNC_ARG4(MouseWheel)
IMPLEMENT_CALLBACK_FUNC_ARG2(Motion)
IMPLEMENT_CALLBACK_FUNC_ARG2_2NAME(PassiveMotion,Passive)
IMPLEMENT_CALLBACK_FUNC_ARG1(Entry)
/* glutWMCloseFunc is an alias for glutCloseFunc; both set the window's Destroy callback */
IMPLEMENT_CALLBACK_FUNC_ARG0_2NAME(Close,Destroy)
IMPLEMENT_CALLBACK_FUNC_ARG0_2NAME(WMClose,Destroy)
IMPLEMENT_CALLBACK_FUNC_ARG0(OverlayDisplay)
IMPLEMENT_CALLBACK_FUNC_ARG1(WindowStatus)
IMPLEMENT_CALLBACK_FUNC_ARG2(ButtonBox)
IMPLEMENT_CALLBACK_FUNC_ARG2(Dials)
IMPLEMENT_CALLBACK_FUNC_ARG2(TabletMotion)
IMPLEMENT_CALLBACK_FUNC_ARG4(TabletButton)
IMPLEMENT_CALLBACK_FUNC_ARG2(MultiEntry)
IMPLEMENT_CALLBACK_FUNC_ARG5(MultiButton)
IMPLEMENT_CALLBACK_FUNC_ARG3(MultiMotion)
IMPLEMENT_CALLBACK_FUNC_ARG3(MultiPassive)
IMPLEMENT_CALLBACK_FUNC_ARG0(InitContext)
IMPLEMENT_CALLBACK_FUNC_ARG1(AppStatus)

/*
 * Sets the Display callback for the current window
 */
void FGAPIENTRY glutDisplayFuncUcall( FGCBDisplayUC callback, FGCBUserData userData )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutDisplayFuncUcall" );
    if( !callback )
        fgError( "Fatal error in program.  NULL display callback not "
                 "permitted in GLUT 3.0+ or freeglut 2.0.1+" );
    SET_CALLBACK( Display );
}

static void fghDisplayFuncCallback( FGCBUserData userData )
{
    FGCBDisplay callback = (FGCBDisplay)userData;
    callback();
}

void FGAPIENTRY glutDisplayFunc( FGCBDisplay callback )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutDisplayFunc" );
    if( callback )
        glutDisplayFuncUcall( fghDisplayFuncCallback, (FGCBUserData)callback );
    else
        glutDisplayFuncUcall( NULL, NULL );
}

void fghDefaultReshape( int width, int height, FGCBUserData userData )
{
    glViewport( 0, 0, width, height );
}

void FGAPIENTRY glutReshapeFuncUcall( FGCBReshapeUC callback, FGCBUserData userData )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutReshapeFuncUcall" );
    
    if( !callback )
    {
        callback = fghDefaultReshape;
        userData = NULL;
    }

    SET_CALLBACK( Reshape );
}

static void fghReshapeFuncCallback( int width, int height, FGCBUserData userData )
{
    FGCBReshape callback = (FGCBReshape)userData;
    callback( width, height );
}

void FGAPIENTRY glutReshapeFunc( FGCBReshape callback )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutReshapeFunc" );
    if( callback )
        glutReshapeFuncUcall( fghReshapeFuncCallback, (FGCBUserData)callback );
    else
        glutReshapeFuncUcall( NULL, NULL );
}

/*
 * Sets the Visibility callback for the current window.
 * NB: the Visibility func is deprecated in favor of the WindowStatus func,
 * which provides more detail. The visibility func callback is implemented
 * as a translation step from the windowStatus func. When the user sets the
 * windowStatus func, any visibility func is overwritten.
 * DEVELOPER NOTE: in the library, only invoke the window status func, this
 * gets automatically translated to the visibility func if thats what the
 * user has set.
 * window status is kind of anemic on win32 as there are no window messages
 * to notify us that the window is covered by other windows or not.
 * Should one want to query this, see
 * http://stackoverflow.com/questions/5445889/get-which-process-window-is-actually-visible-in-c-sharp
 * for an implementation outline (but it would be polling based, not push based).
 */
static void fghVisibility( int status, FGCBUserData userData )
{
    int vis_status;

    FREEGLUT_INTERNAL_ERROR_EXIT_IF_NOT_INITIALISED ( "Visibility Callback" );
    freeglut_return_if_fail( fgStructure.CurrentWindow );

    /* Translate window status func states to visibility states */
    if( ( GLUT_HIDDEN == status )  || ( GLUT_FULLY_COVERED == status ) )
        vis_status = GLUT_NOT_VISIBLE;
    else    /* GLUT_FULLY_RETAINED, GLUT_PARTIALLY_RETAINED */
        vis_status = GLUT_VISIBLE;

    INVOKE_WCB( *( fgStructure.CurrentWindow ), Visibility, ( vis_status ) );
}

void FGAPIENTRY glutVisibilityFuncUcall( FGCBVisibilityUC callback, FGCBUserData userData )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutVisibilityFuncUcall" );

    if ( !callback )
    {
        userData = NULL;
    }

    SET_CALLBACK( Visibility );

    if( callback )
        glutWindowStatusFuncUcall( fghVisibility, NULL );
    else
        glutWindowStatusFuncUcall( NULL, NULL );
}

static void fghVisibilityFuncCallback( int visibility, FGCBUserData userData )
{
    FGCBVisibility callback = (FGCBVisibility)userData;
    callback( visibility );
}

void FGAPIENTRY glutVisibilityFunc( FGCBVisibility callback )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutVisibilityFunc" );
    if( callback )
        glutVisibilityFuncUcall( fghVisibilityFuncCallback, (FGCBUserData)callback );
    else
        glutVisibilityFuncUcall( NULL, NULL );
}

/*
 * Sets the joystick callback and polling rate for the current window
 */
void FGAPIENTRY glutJoystickFuncUcall( FGCBJoystickUC callback, int pollInterval, FGCBUserData userData )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutJoystickFuncUcall" );
    fgInitialiseJoysticks ();

    if ( (
           fgStructure.CurrentWindow->State.JoystickPollRate <= 0 ||        /* Joystick callback was disabled */
           !FETCH_WCB(*fgStructure.CurrentWindow,Joystick)
         ) &&
         ( 
           callback && ( pollInterval > 0 )                                 /* but is now enabled */
         ) )
        ++fgState.NumActiveJoysticks;
    else if ( ( 
                fgStructure.CurrentWindow->State.JoystickPollRate > 0 &&    /* Joystick callback was enabled */
                FETCH_WCB(*fgStructure.CurrentWindow,Joystick)
              ) &&  
              ( 
                !callback || ( pollInterval <= 0 )                          /* but is now disabled */
              ) )
        --fgState.NumActiveJoysticks;

    SET_CALLBACK( Joystick );
    fgStructure.CurrentWindow->State.JoystickPollRate = pollInterval;

    /* set last poll time such that joystick will be polled asap */
    fgStructure.CurrentWindow->State.JoystickLastPoll = fgElapsedTime();
    if (fgStructure.CurrentWindow->State.JoystickLastPoll < pollInterval)
        fgStructure.CurrentWindow->State.JoystickLastPoll = 0;
    else
        fgStructure.CurrentWindow->State.JoystickLastPoll -= pollInterval;
}

static void fghJoystickFuncCallback( unsigned int buttons, int axis0, int axis1, int axis2, FGCBUserData userData )
{
    FGCBJoystick callback = (FGCBJoystick)userData;
    callback( buttons, axis0, axis1, axis2 );
}

void FGAPIENTRY glutJoystickFunc( FGCBJoystick callback, int pollInterval )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutJoystickFunc" );
    if( callback )
        glutJoystickFuncUcall( fghJoystickFuncCallback, pollInterval, (FGCBUserData)callback );
    else
        glutJoystickFuncUcall( NULL, pollInterval, NULL );
}

/*
 * Sets the spaceball motion callback for the current window
 */
void FGAPIENTRY glutSpaceballMotionFuncUcall( FGCBSpaceMotionUC callback, FGCBUserData userData )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutSpaceballMotionFuncUcall" );
    fgInitialiseSpaceball();

    SET_CALLBACK( SpaceMotion );
}

static void fghSpaceballMotionFuncCallback( int x, int y, int z, FGCBUserData userData )
{
    FGCBSpaceMotion callback = (FGCBSpaceMotion)userData;
    callback( x, y, z );
}

void FGAPIENTRY glutSpaceballMotionFunc( FGCBSpaceMotion callback )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutSpaceballMotionFunc" );
    if( callback )
        glutSpaceballMotionFuncUcall( fghSpaceballMotionFuncCallback, (FGCBUserData)callback );
    else
        glutSpaceballMotionFuncUcall( NULL, NULL );
}

/*
 * Sets the spaceball rotate callback for the current window
 */
void FGAPIENTRY glutSpaceballRotateFuncUcall( FGCBSpaceRotationUC callback, FGCBUserData userData )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutSpaceballRotateFuncUcall" );
    fgInitialiseSpaceball();

    SET_CALLBACK( SpaceRotation );
}

static void fghSpaceballRotateFuncCallback( int x, int y, int z, FGCBUserData userData )
{
    FGCBSpaceRotation callback = (FGCBSpaceRotation)userData;
    callback( x, y, z );
}

void FGAPIENTRY glutSpaceballRotateFunc( FGCBSpaceRotation callback )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutSpaceballRotateFunc" );
    if( callback )
        glutSpaceballRotateFuncUcall( fghSpaceballRotateFuncCallback, (FGCBUserData)callback );
    else
        glutSpaceballRotateFuncUcall( NULL, NULL );
}

/*
 * Sets the spaceball button callback for the current window
 */
void FGAPIENTRY glutSpaceballButtonFuncUcall( FGCBSpaceButtonUC callback, FGCBUserData userData )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutSpaceballButtonFuncUcall" );
    fgInitialiseSpaceball();

    SET_CALLBACK( SpaceButton );
}

static void fghSpaceballButtonFuncCallback( int button, int buttonState, FGCBUserData userData )
{
    FGCBSpaceButton callback = (FGCBSpaceButton)userData;
    callback( button, buttonState );
}

void FGAPIENTRY glutSpaceballButtonFunc( FGCBSpaceButton callback )
{
    FREEGLUT_EXIT_IF_NOT_INITIALISED ( "glutSpaceballButtonFunc" );
    if( callback )
        glutSpaceballButtonFuncUcall( fghSpaceballButtonFuncCallback, (FGCBUserData)callback );
    else
        glutSpaceballButtonFuncUcall( NULL, NULL );
}

/*** END OF FILE ***/
