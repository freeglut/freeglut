
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

#ifndef FREEGLUT_INTERNAL_COCOA_INSTRUMENTATION_H
#define FREEGLUT_INTERNAL_COCOA_INSTRUMENTATION_H

#include <unistd.h>

#import <os/signpost.h>

#define TIMER_INTERVAL_NS 5e9

extern os_log_t OSLog;
extern os_signpost_id_t currentScope;

#define EMIT_EVENT(...)                                                        \
  os_signpost_event_emit(OSLog, currentScope,                                  \
                         "Event:" __VA_OPT__(, )##__VA_ARGS__)

struct TimerStats {
  uint64_t startTime;
  uint64_t count;
  float sum;
  double sumOfSquares; /* for variance */
  float min, max;
};

struct Timer {
  /* config */
  const char *name;
  uint64_t reportIntervalNS;

  /* stats */
  uint64_t nextReport;
  struct TimerStats stats;
};

#define TIMER_STATS_INIT                                                       \
  (struct TimerStats) {                                                        \
    .startTime = 0, .count = 0, .sum = 0, .sumOfSquares = 0, .min = INT_MAX,   \
    .max = INT_MIN                                                             \
  }

void startTimer(struct Timer *self);
void endTimer(struct Timer *self);

#endif /* FREEGLUT_INTERNAL_COCOA_INSTRUMENTATION_H */
