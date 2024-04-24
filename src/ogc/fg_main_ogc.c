/*
 * Copyright (c) 2024 Alberto Mardegan <mardy@users.sourceforge.net>
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
 * MANUEL BACHMANN BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

#include "fg_common_ogc.h"

fg_time_t fgPlatformSystemTime(void)
{
    fgWarning("%s() : not implemented", __func__);
    return 0;
}

void fgPlatformSleepForEvents(fg_time_t msec)
{
    fgWarning("%s() : not implemented", __func__);
}

void fgPlatformProcessSingleEvent(void)
{
    fgWarning("%s() : not implemented", __func__);
}

void fgPlatformMainLoopPreliminaryWork(void)
{
    fgWarning("%s() : not implemented", __func__);
}

void fgPlatformInitWork(SFG_Window *window)
{
    fgWarning("%s() : not implemented", __func__);
}

void fgPlatformPosResZordWork(SFG_Window *window, unsigned int workMask)
{
    fgWarning("%s() : not implemented", __func__);
}

void fgPlatformVisibilityWork(SFG_Window *window)
{
    fgWarning("%s() : not implemented", __func__);
}

void fgPlatformSetColor(int idx, float r, float g, float b)
{
    fgWarning("%s() : not implemented", __func__);
}

float fgPlatformGetColor(int idx, int comp)
{
    fgWarning("%s() : not implemented", __func__);
    return 0.0;
}

void fgPlatformCopyColormap(int win)
{
    fgWarning("%s() : not implemented", __func__);
}
