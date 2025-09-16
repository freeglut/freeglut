
/*
 * Copyright (c) 2025 Andrew Woods <drew.woods@gmail.com>
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

#include "fg_internal_cocoa.h"
#include "fg_internal_instrumentation_cocoa.h"

#include <math.h>

#import <Cocoa/Cocoa.h>

#ifdef DEBUG_LOG
#ifdef DBG
#undef DBG
#endif
#define DBG( ... ) NSLog( __VA_ARGS__ )
#else
#define DBG( ... )
#endif

/*
 * Signposts instrumentations api is used to emit events and intervals to the
 * Instruments.app. This is useful for debugging and performance analysis.
 */

os_log_t         OSLog;
os_signpost_id_t currentScope;

static void init( )
{
    if ( OSLog ) {
        return;
    }
    OSLog = os_log_create( "freeglut-trace", OS_LOG_CATEGORY_POINTS_OF_INTEREST );
}

static void enter_scope( const char *scope_name )
{
    init( );

    /* Create a signpost ID for this specific operation instance */
    currentScope = os_signpost_id_make_with_pointer( OSLog, scope_name );
    os_signpost_interval_begin( OSLog, currentScope, "Scope", "Start %s", scope_name );
}

static void leave_scope( const char *scope_name )
{
    init( );

    os_signpost_id_t expected = os_signpost_id_make_with_pointer( OSLog, scope_name );
    os_signpost_interval_end( OSLog, expected, "Scope", "End %s", scope_name );

    currentScope = 0; /* reset signpost ID */
}

/*
 * Some basic timing functions to help with debugging and performance analysis.
 * Will print a report on the configured interval (Timer.reportIntervalNS) if DEBUG_LOG is defined.
 *
 * Example output:
 * [Timer Name] -- Avg: 15.76ms   Min/Max:  9.79/16.81ms   STD:0.47ms   CNT: 300
 */

void startTimer( struct Timer *self )
{
    if ( self->stats.startTime ) {
        endTimer( self );
    }
    enter_scope( self->name );
    if ( self->reportIntervalNS == 0 ) {
        self->reportIntervalNS = 10e9;
    }
    self->stats.startTime = clock_gettime_nsec_np( CLOCK_REALTIME );
    if ( !self->nextReport ) {
        self->nextReport = self->stats.startTime + self->reportIntervalNS;
    }
}

void endTimer( struct Timer *self )
{
    if ( !self->stats.startTime ) {
        DBG( @"WARNING - Timer %s was not started", self->name );
        return;
    }

    uint64_t now    = clock_gettime_nsec_np( CLOCK_REALTIME );
    float    dur    = ( now - self->stats.startTime ) / 1e6;
    self->stats.min = ( self->stats.min == 0 || dur < self->stats.min ) ? dur : self->stats.min;
    self->stats.max = ( dur > self->stats.max ) ? dur : self->stats.max;
    self->stats.sumOfSquares += dur * dur;
    self->stats.sum += dur;
    self->stats.count++;

    leave_scope( self->name );

    /* Is it time to report? */
    if ( now > self->nextReport ) {
        float          avg = self->stats.sum / self->stats.count;
        float FGUNUSED var = self->stats.sumOfSquares / self->stats.count - avg * avg;
        DBG( @"%16s -- Avg: %5.2fms   Min/Max: %5.2f/%5.2fms   STD:%.2fms   CNT: %llu",
            self->name,
            avg,
            self->stats.min,
            self->stats.max,
            sqrt( var ),
            self->stats.count );

        self->nextReport += self->reportIntervalNS;
        self->stats = TIMER_STATS_INIT; /* clear stats */
    }

    self->stats.startTime = 0; /* stop the timer */
}
