/*
 * fg_callback_macros.h
 *
 * The freeglut library callback macro file.
 *
 * Copyright (C) 2016 Vincent Simonetti
 * Creation date: Sat Jan 16 2016
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

/*
 * Compiler defines:
 * FG_COMPILER_SUPPORTS_GCC_VA_ARGS_HACK: if the compiler supports GCC's variadic macro implementation (AKA, ##__VA_ARGS__)
 * FG_COMPILER_SUPPORTS_VA_ARGS: if the compiler supports variadic macros
 */

/* What supports variadic macros based off Wikipedia article on it (GCC-like must support C99 or higher to use variadic macros) */
#if (((defined(__GNUC__) && (__GNUC__ >= 3)) || \
	  (defined(__clang__))) && \
		(defined(__STDC_VERSION__) && (__STDC_VERSION__ >= 199901L))) || \
	(defined(_MSC_VER) && (_MSC_VER >= 1400)) || \
	(defined(__BORLANDC__) && (__BORLANDC__ >= 0x570)) || \
	(defined(__SUNPRO_C) && (__SUNPRO_C >= 0x530))
#define FG_COMPILER_SUPPORTS_VA_ARGS 1
#else
#define FG_COMPILER_SUPPORTS_VA_ARGS 0
#endif

/* If __VA_ARGS__ is supported, it needs to be GCC(-like) or Clang (since Clang mimics GCC) */
#if FG_COMPILER_SUPPORTS_VA_ARGS && \
	(defined(__GNUC__)) || \
	(defined(__clang__))
#define FG_COMPILER_SUPPORTS_GCC_VA_ARGS_HACK 1
#else
#define FG_COMPILER_SUPPORTS_GCC_VA_ARGS_HACK 0
#endif

/*
 * Info:
 *
 * This took a while to figure out, so be sure try to understand what is happening so that you can ensure that whatever you
 * change won't break other areas.
 * 
 * If you are just adding a new callback/changing it's argument count, just go to the bottom of the file.
 *
 * This whole file exists purely for the sake of preventing the need to implement additional parsing logic for each callback
 * to pass user arguments. Of course, the necessity to support older compilers means that, as seen in the line above, there
 * is still a requirement to add/modify code to handle callbacks. If freeglut ever requires newer compilers (at minimum, ones
 * that support C99 or higher), code can very slowly be removed from this file. Even better would be if the C standard eventually
 * supports something similar to what GCC has implemented or offers an alternative. Another option is if C++ would be "allowed" by
 * project maintaners, as then templates can be used and function overloading. Ironically, the template would probably look worse
 * then the GCC macro, so maybe it's good to stay as is.
 *
 * Onto the different "versions" of macros:
 * 
 * There is one for GCC/Clang(/and supposedly the Intel compiler) which supports the non-standard ##__VA_ARGS__ token. The code may 
 * look ugly, but the result is, if this was standard, no one would ever need to open this file unless they were curious (or needed 
 * more then 5 arguments for a callback, but that's trivial to add). It works by adding many fake macros to a "picker" macro
 * (PP_HAS_ARGS_IMPL2) which then indictaes which macro counter to use. As we can already use variadic macros (the VA in __VA_ARGS__),
 * this just becomes a "reuse the arguments*.
 * 
 * The next is for any non-GCC/Clang/etc. compiler *cough* MSVC/compiler you probably shouldn't be using *cough* that supports C99
 * by default. It requires each callback to have a specific argument count passthrough macro. The only reason there are specific
 * count macros is so that (see paraghraph below) don't need have their own set of callback macros. Ideally, there would only be
 * ZERO and ONE_OR_MORE. This works by having callback-specific macros call a specific handler macro to return user data (ZERO) or
 * return one or more arguments along with userData (ONE_OR_MORE) where, with variadic macros, it just reuses the arguments.
 *
 * The last set is for the poor individual who has to use a compiler that doesn't support C99 by default, or may not support it at
 * all. Stuff like MSVC6... It works by having a specific-count macro that "extracts" each argument to have them reused without the
 * parathesis.
 *
 * A note on parathesis, as earlier mentioned, if the GCC variadic macro element was standard, then instead of needing:
 *
 * func EXPAND_WCB(Mouse)(( (GLUT_LEFT_BUTTON, GLUT_DOWN, 10, 30), userData));
 *
 * ...you can do the following:
 *
 * func EXPAND_WCB (GLUT_LEFT_BUTTON, GLUT_DOWN, 10, 30);
 *
 * Wow... so much nice and easier to understand. Sub-note: I have not worked on a version that explicitly takes userData, so for now
 * if you can get to that version, look in the version control change history for this file and you'll find that version which
 * implicitly passes "userData" and only works on GCC vardiac macro supporting compilers.
 */

#if FG_COMPILER_SUPPORTS_GCC_VA_ARGS_HACK

 /*
 * EXPAND_WCB() is used as:
 * 
 *     EXPAND_WCB( cbname )(( arg_list, userData ))
 * 
 * ... where {(arg_list)} is the parameter list and userData is user
 * provided data.
 *
 * All additional macros are to get around trailing ',' for zero-arg
 * callbacks.
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
 * Supports callbacks up to 5 args (follow the pattern of 
 * EXPAND_WCB_PP_HAS_ARGS_IMPL2 and EXPAND_WCB_PP_HAS_ARGS_SOURCE to add more)
 *
 * Edit with care.
 */

#define EXPAND_WCB_UNPARAN(...) __VA_ARGS__

#define EXPAND_WCB_PP_HAS_ARGS_IMPL2(_0, _1, _2, _3, _4, _5, N, ...) N
#define EXPAND_WCB_PP_HAS_ARGS_SOURCE() ONE_OR_MORE, ONE_OR_MORE, ONE_OR_MORE, ONE_OR_MORE, ONE_OR_MORE, ZERO

#define EXPAND_WCB_PP_HAS_ARGS_IMPL(...) EXPAND_WCB_PP_HAS_ARGS_IMPL2( __VA_ARGS__ )
#define EXPAND_WCB_PP_HAS_ARGS(...) EXPAND_WCB_PP_HAS_ARGS_IMPL( NOT_EXIST, ##__VA_ARGS__, EXPAND_WCB_PP_HAS_ARGS_SOURCE() )

#define EXPAND_WCB_ZERO(args, userData) ( userData )
#define EXPAND_WCB_ONE_OR_MORE(args, userData) ( EXPAND_WCB_UNPARAN args, userData )

#define EXPAND_WCB_DISAMBIGUATE2(has_args, args, userData) EXPAND_WCB_ ## has_args ( args, userData )
#define EXPAND_WCB_DISAMBIGUATE(has_args, args, userData) EXPAND_WCB_DISAMBIGUATE2( has_args, args, userData )

#define EXPAND_WCB_UNWRAP_ARGS2(args, userData) EXPAND_WCB_DISAMBIGUATE( EXPAND_WCB_PP_HAS_ARGS args, args, userData )
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

#if FG_COMPILER_SUPPORTS_VA_ARGS

#define EXPAND_WCB_UNPARAN(...) __VA_ARGS__
#define EXPAND_WCB_ONE_OR_MORE(args, userData) ( EXPAND_WCB_UNPARAN args, userData )

#define EXPAND_WCB_ONE(args, userData) EXPAND_WCB_ONE_OR_MORE( args, userData )
#define EXPAND_WCB_TWO(args, userData) EXPAND_WCB_ONE_OR_MORE( args, userData )
#define EXPAND_WCB_THREE(args, userData) EXPAND_WCB_ONE_OR_MORE( args, userData )
#define EXPAND_WCB_FOUR(args, userData) EXPAND_WCB_ONE_OR_MORE( args, userData )
#define EXPAND_WCB_FIVE(args, userData) EXPAND_WCB_ONE_OR_MORE( args, userData )

#else

#define EXPAND_WCB_EXTRACT_ONE_ARGS(arg1) arg1
#define EXPAND_WCB_EXTRACT_TWO_ARGS(arg1, arg2) arg1, arg2
#define EXPAND_WCB_EXTRACT_THREE_ARGS(arg1, arg2, arg3) arg1, arg2, arg3
#define EXPAND_WCB_EXTRACT_FOUR_ARGS(arg1, arg2, arg3, arg4) arg1, arg2, arg3, arg4
#define EXPAND_WCB_EXTRACT_FIVE_ARGS(arg1, arg2, arg3, arg4, arg5) arg1, arg2, arg3, arg4, arg5

#define EXPAND_WCB_ONE(args, userData) (EXPAND_WCB_EXTRACT_ONE_ARGS args, userData)
#define EXPAND_WCB_TWO(args, userData) (EXPAND_WCB_EXTRACT_TWO_ARGS args, userData)
#define EXPAND_WCB_THREE(args, userData) (EXPAND_WCB_EXTRACT_THREE_ARGS args, userData)
#define EXPAND_WCB_FOUR(args, userData) (EXPAND_WCB_EXTRACT_FOUR_ARGS args, userData)
#define EXPAND_WCB_FIVE(args, userData) (EXPAND_WCB_EXTRACT_FIVE_ARGS args, userData)

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
