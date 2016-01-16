/*
 * fg_callback_macros.h
 *
 * The freeglut library callback macro file.
 *
 * Copyright (c) 1999-2000 Pawel W. Olszta. All Rights Reserved.
 * Written by Pawel W. Olszta, <olszta@sourceforge.net>
 * Creation date: Thu Dec 2 1999
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

#ifndef FREEGLUT_CALLBACK_MACROS_H
#define FREEGLUT_CALLBACK_MACROS_H

#ifndef FREEGLUT_INTERNAL_H
#error "fg_internal.h needs to be included before this header"
#endif

#if 0 /* old. Remove once other testing is complete */
/*
 * EXPAND_WCB() is used as:
 * 
 *     EXPAND_WCB arg_list
 * 
 * ... where {(arg_list)} is the parameter list.
 *
 * This will take the arg_list and extend it by one argument, adding
 * the argument "userData" to the end of the list.
 * 
 * All additional args are to get around trailing ',', argument counts,
 * and not needing a GCC extension to make this work.
 *
 * Minor modification of:
 * http://stackoverflow.com/questions/5355241/generating-function-declaration-using-a-macro-iteration/5355946#5355946
 *
 * Supports up to five arguments
 */

/* GCC-specific design that doesn't require per-callback defines */

#define PP_HAS_ARGS_IMPL2(_0, _1, _2, _3, _4, _5, N, ...) N
#define PP_HAS_ARGS_SOURCE() \
    ONE_OR_MORE, ONE_OR_MORE, ONE_OR_MORE, ONE_OR_MORE, ONE_OR_MORE, ZERO

#define PP_HAS_ARGS_IMPL(...) \
    PP_HAS_ARGS_IMPL2(__VA_ARGS__)
#define PP_HAS_ARGS(...) \
    PP_HAS_ARGS_IMPL(NOT_EXIST, ##__VA_ARGS__, PP_HAS_ARGS_SOURCE())

#define EXPAND_WCB_ZERO(x) \
    (userData)
#define EXPAND_WCB_ONE_OR_MORE(...) \
    (__VA_ARGS__, userData)

#define EXPAND_WCB_DISAMBIGUATE2(has_args, ...) \
    EXPAND_WCB_ ## has_args (__VA_ARGS__)
#define EXPAND_WCB_DISAMBIGUATE(has_args, ...) \
    EXPAND_WCB_DISAMBIGUATE2(has_args, __VA_ARGS__)
#define EXPAND_WCB(...) \
    EXPAND_WCB_DISAMBIGUATE(PP_HAS_ARGS(__VA_ARGS__), __VA_ARGS__)
#endif

/*
 * Compiler defines:
 * FG_COMPILER_SUPPORTS_GCC_VA_ARGS_HACK: if the compiler supports GCC's varadic macro implementation (AKA, ##__VA_ARGS__)
 * FG_COMPILER_SUPPORTS_VA_ARGS: if the compiler supports varadic macros
 */

#ifdef FG_COMPILER_SUPPORTS_GCC_VA_ARGS_HACK

 /*
 * EXPAND_WCB() is used as:
 *
 *     EXPAND_WCB arg_list
 *
 * ... where {(arg_list)} is the parameter list.
 *
 * This will take the arg_list and extend it by one argument, adding
 * the argument "userData" to the end of the list.
 *
 * All additional args are to get around trailing ',', argument counts,
 * and not needing a GCC extension to make this work.
 *
 * Modification of:
 * http://stackoverflow.com/questions/5355241/generating-function-declaration-using-a-macro-iteration/5355946#5355946
 *
 * --------------
 *
 * GCC-specific design that doesn't require per-callback defines
 *
 * The naming is terrible... and it's very convuluted and complex, but
 * should not require any modification unless additional arguments are to 
 * be supported.
 * 
 * Supports callbacks up to 5 args (follow the pattern of PP_HAS_ARGS_IMPL2
 * and PP_HAS_ARGS_SOURCE to add more)
 *
 * Edit with care.
 */

#define EXPAND_WCB_UNPARAN(...) __VA_ARGS__

#define PP_HAS_ARGS_IMPL2(_0, _1, _2, _3, _4, _5, N, ...) N
#define PP_HAS_ARGS_SOURCE() ONE_OR_MORE, ONE_OR_MORE, ONE_OR_MORE, ONE_OR_MORE, ONE_OR_MORE, ZERO

#define PP_HAS_ARGS_IMPL(...) PP_HAS_ARGS_IMPL2( __VA_ARGS__ )
#define PP_HAS_ARGS(...) PP_HAS_ARGS_IMPL( NOT_EXIST, ##__VA_ARGS__, PP_HAS_ARGS_SOURCE() )

#define EXPAND_WCB_ZERO(args, userData) ( userData )
#define EXPAND_WCB_ONE_OR_MORE(args, userData) ( EXPAND_WCB_UNPARAN args, userData )

#define EXPAND_WCB_DISAMBIGUATE(has_args, args, userData) EXPAND_WCB_ ## has_args ( args, userData )

#define EXPAND_WCB_UNWRAP_ARGS2(args, userData) EXPAND_WCB_DISAMBIGUATE( PP_HAS_ARGS args, args, userData )
#define EXPAND_WCB_UNWRAP_ARGS(args) EXPAND_WCB_UNWRAP_ARGS2 args

#define EXPAND_WCB(cbname) EXPAND_WCB_UNWRAP_ARGS

#else

/*
 * EXPAND_WCB() is used as:
 * 
 *     EXPAND_WCB( cbname )(( arg_list, userData ))
 * 
 * ... where {(arg_list)} is the parameter list and userData is user
 * provided data.
 *
 * This will take the arg_list and extend it by one argument, adding
 * the argument "userData" to the end of the list.
 *
 * In order for this to work, each callback must have a define that
 * properly handles the arguments as needed by the callback.
 * This callback is in the format of EXPAND_WCB_SUB_<cbname>.
 * Helper functions exist for zero to five parameters: EXPAND_WCB_ZERO,
 * EXPAND_WCB_ONE, EXPAND_WCB_TWO, EXPAND_WCB_THREE< EXPAND_WCB_FOUR,
 * and EXPAND_WCB_FIVE. Each handle the callback argument counts.
 *
 * An example for the "Entry" callback, where "Entry" is the cbname:
 * typedef void (* FGCBEntry  )( int );
 * typedef void (* FGCBEntryUC)( int, FGCBUserData );
 * #define EXPAND_WCB_SUB_Entry(args) EXPAND_WCB_ONE args
 */

#define FG_COMPILER_SUPPORTS_VA_ARGS //XXX (should be compiler defined)
#ifdef FG_COMPILER_SUPPORTS_VA_ARGS

#define EXPAND_WCB_UNPARAN(...) __VA_ARGS__
#define EXPAND_WCB_ONE_OR_MORE(args, userData) ( EXPAND_WCB_UNPARAN args, userData )

#define EXPAND_WCB_ONE(args, userData) EXPAND_WCB_ONE_OR_MORE( args, userData )
#define EXPAND_WCB_TWO(args, userData) EXPAND_WCB_ONE_OR_MORE( args, userData )
#define EXPAND_WCB_THREE(args, userData) EXPAND_WCB_ONE_OR_MORE( args, userData )
#define EXPAND_WCB_FOUR(args, userData) EXPAND_WCB_ONE_OR_MORE( args, userData )
#define EXPAND_WCB_FIVE(args, userData) EXPAND_WCB_ONE_OR_MORE( args, userData )

#else

//TODO
#error "Compiler does not support varadic argument macros"

#endif

#define EXPAND_WCB_ZERO(args, userData) ( userData )

#define EXPAND_WCB(cbname) EXPAND_WCB_SUB_ ## cbname

/* 
 * Freeglut callbacks type definitions macros
 *
 * Every time a callback is updated in fg_internal.h is updated, this needs updated
 * if argument counts change, new callbacks are added, or callbacks are removed.
 */

#define EXPAND_WCB_SUB_Display(args) EXPAND_WCB_ZERO args
#define EXPAND_WCB_SUB_Reshape(args) EXPAND_WCB_TWO args
#define EXPAND_WCB_SUB_Position(args) EXPAND_WCB_TWO args
#define EXPAND_WCB_SUB_Visibility(args) EXPAND_WCB_ONE args
#define EXPAND_WCB_SUB_Keyboard(args) EXPAND_WCB_THREE args
#define EXPAND_WCB_SUB_KeyboardUp(args) EXPAND_WCB_THREE args
#define EXPAND_WCB_SUB_Special(args) EXPAND_WCB_THREE args
#define EXPAND_WCB_SUB_SpecialUp(args) EXPAND_WCB_THREE args
#define EXPAND_WCB_SUB_Mouse(args) EXPAND_WCB_FOUR args
#define EXPAND_WCB_SUB_MouseWheel(args) EXPAND_WCB_FOUR args
#define EXPAND_WCB_SUB_Motion(args) EXPAND_WCB_TWO args
#define EXPAND_WCB_SUB_Passive(args) EXPAND_WCB_TWO args
#define EXPAND_WCB_SUB_Entry(args) EXPAND_WCB_ONE args
#define EXPAND_WCB_SUB_WindowStatus(args) EXPAND_WCB_ONE args
#define EXPAND_WCB_SUB_Joystick(args) EXPAND_WCB_FOUR args
#define EXPAND_WCB_SUB_OverlayDisplay(args) EXPAND_WCB_ZERO args
#define EXPAND_WCB_SUB_SpaceMotion(args) EXPAND_WCB_THREE args
#define EXPAND_WCB_SUB_SpaceRotation(args) EXPAND_WCB_THREE args
#define EXPAND_WCB_SUB_SpaceButton(args) EXPAND_WCB_TWO args
#define EXPAND_WCB_SUB_Dials(args) EXPAND_WCB_TWO args
#define EXPAND_WCB_SUB_ButtonBox(args) EXPAND_WCB_TWO args
#define EXPAND_WCB_SUB_TabletMotion(args) EXPAND_WCB_TWO args
#define EXPAND_WCB_SUB_TabletButton(args) EXPAND_WCB_FOUR args
#define EXPAND_WCB_SUB_Destroy(args) EXPAND_WCB_ZERO args
#define EXPAND_WCB_SUB_MultiEntry(args) EXPAND_WCB_TWO args
#define EXPAND_WCB_SUB_MultiButton(args) EXPAND_WCB_FIVE args
#define EXPAND_WCB_SUB_MultiMotion(args) EXPAND_WCB_THREE args
#define EXPAND_WCB_SUB_MultiPassive(args) EXPAND_WCB_THREE args
#define EXPAND_WCB_SUB_InitContext(args) EXPAND_WCB_ZERO args
#define EXPAND_WCB_SUB_AppStatus(args) EXPAND_WCB_ONE args

#endif

#endif /* FREEGLUT_CALLBACK_MACROS_H */

/*** END OF FILE ***/
