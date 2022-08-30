/* fg_cmap_mswin.h
 *
 * TODO add windows implementation of the colormap management functions
 */
#include <GL/freeglut.h>
#include "../fg_internal.h"

void fgPlatformSetColor(int idx, float r, float g, float b)
{
}

float fgPlatformGetColor(int idx, int comp)
{
	return -1.0f;
}

void fgPlatformCopyColormap(int win)
{
}
