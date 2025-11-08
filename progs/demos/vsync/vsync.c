/* v-sync (swap control) demo
 * Written by John Tsiombikas <nuclear@mutantstargoat.com>
 *
 * Controls:
 *  - 0: disable vsync
 *  - 1-9: enable vsync and select the corresponding swap interval in frames
 *  - ctrl + 1-9: to select negative swap intervals (adaptive v-sync)
 */
#include <stdio.h>
#include <stdlib.h>
#include <math.h>
#include <limits.h>
#include <GL/freeglut.h>

#if defined(unix) || defined(__unix__)
#define BUILD_UNIX
#include <unistd.h>
#elif defined(_WIN32)
#define BUILD_WIN32
#include <windows.h>
#endif

void display(void);
void idle(void);
void reshape(int x, int y);
void keypress(unsigned char key, int x, int y);

int interv = 1;
int win_width, win_height;
long delay;
const char *exttext[8];
int exttext_width;

const char *check_ext[] = {
#ifdef BUILD_UNIX
	"GLX_EXT_swap_control", "GLX_EXT_swap_control_tear",
	"GLX_MESA_swap_control", "GLX_SGI_swap_control",
#endif
#ifdef BUILD_WIN32
	"WGL_EXT_swap_control", "WGL_EXT_swap_control_tear",
#endif
	0
};


int main(int argc, char **argv)
{
	int i, len, n = 0;

	glutInit(&argc, argv);
	glutInitDisplayMode(GLUT_RGB | GLUT_DOUBLE);
	glutInitWindowSize(640, 480);
	glutCreateWindow("vsync test");

	glutDisplayFunc(display);
	glutReshapeFunc(reshape);
	glutIdleFunc(idle);
	glutKeyboardFunc(keypress);

	exttext[n++] = "extensions:";
	exttext_width = glutBitmapLength(GLUT_BITMAP_HELVETICA_12, (unsigned char*)exttext[0]);
	for(i=0; check_ext[i]; i++) {
		if(glutExtensionSupported(check_ext[i])) {
			exttext[n++] = check_ext[i];
			len = glutBitmapLength(GLUT_BITMAP_HELVETICA_12, (unsigned char*)check_ext[i]);
			if(len > exttext_width) {
				exttext_width = len;
			}
		}
	}

	glutSwapInterval(interv);

	glutMainLoop();
	return 0;
}

void display(void)
{
	static long nframes, prev_fps_upd;
	static char fpsbuf[128], text[128];
	int i;
	long upd_time, msec = glutGet(GLUT_ELAPSED_TIME);
	float t = (float)msec / 1000.0f;

	glClear(GL_COLOR_BUFFER_BIT);

	glMatrixMode(GL_MODELVIEW);
	glLoadIdentity();
	glTranslatef(sin(t * 8.0f), 0, 0);
	glScalef(0.5, 0.5, 0.5);

	glBegin(GL_QUADS);
	glColor3f(1.0, 0.3, 0.2);
	glVertex2f(-1, -1);
	glVertex2f(1, -1);
	glVertex2f(1, 1);
	glVertex2f(-1, 1);
	glEnd();

	if((upd_time = msec - prev_fps_upd) >= 1000) {
		float fps = nframes * 1000.0f / (float)upd_time;
		nframes = 0;
		prev_fps_upd = msec;
		sprintf(fpsbuf, "%.1f fps", fps);
	}

	glLoadIdentity();
	glMatrixMode(GL_PROJECTION);
	glPushMatrix();
	glLoadIdentity();
	glOrtho(0, win_width, 0, win_height, -1, 1);

	glColor3f(0.2, 1, 0.2);
	glRasterPos2i(5, win_height - 20);
	sprintf(text, "swap interval: %d", interv);
	glutBitmapString(GLUT_BITMAP_HELVETICA_18, (unsigned char*)text);

	glRasterPos2i(5, win_height - 40);
	glutBitmapString(GLUT_BITMAP_HELVETICA_18, (unsigned char*)fpsbuf);

	if(delay) {
		glRasterPos2i(5, win_height - 60);
		sprintf(text, "delay: %ld ms", delay);
		glutBitmapString(GLUT_BITMAP_HELVETICA_18, (unsigned char*)text);
	}

	if(exttext[1]) {
		for(i=0; exttext[i]; i++) {
			glRasterPos2i(win_width - exttext_width - 5, win_height - i * 16 - 16);
			glutBitmapString(GLUT_BITMAP_HELVETICA_12, (unsigned char*)exttext[i]);
		}
	}

	glPopMatrix();

	if(delay) {
#if defined(unix) || defined(__unix__)
		usleep(delay * 1000);
#elif defined(_WIN32)
		Sleep(delay);
#endif
	}

	glutSwapBuffers();
	nframes++;
	glFinish();
}

void idle(void)
{
	glutPostRedisplay();
}

void reshape(int x, int y)
{
	float aspect = (float)x / (float)y;
	win_width = x;
	win_height= y;
	glViewport(0, 0, x, y);

	glMatrixMode(GL_PROJECTION);
	glLoadIdentity();
	glOrtho(-aspect, aspect, -1, 1, -1, 1);
}

void keypress(unsigned char key, int x, int y)
{
	switch(key) {
	case 27:
		exit(0);

	case '=':
		delay += 5;
		break;
	case '-':
		delay -= 5;
		if(delay < 0) delay = 0;
		break;

	default:
		if(key >= '0' && key <= '9') {
			interv = key - '0';
			if(glutGetModifiers()) {
				interv = -interv;
			}
			glutSwapInterval(interv);
		}
	}
}
