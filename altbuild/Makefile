# Simple UNIX makefile
# Builds freeglut as a static library.

# ------- options --------

# installation prefix
PREFIX = /usr/local

# subdirectory for libraries (lib, lib32, lib64?)
LIBDIR = lib

# optimization flags
opt = -O3

# debug symbols
#dbg = -g

# libraries to link when building the demo program (make demo). You might need
# to adjust these, depending on the choices in config.h
demo_libs = $(liba) -lGL -lX11 -lXmu -lXext -lXi -lXrandr -lXxf86vm -lm

# ---- end of options ----

coreobj = src/fg_gl2.o src/fg_misc.o src/fg_input_devices.o src/fg_ext.o \
	src/fg_joystick.o src/fg_init.o src/fg_callbacks.o \
	src/fg_menu.o src/fg_stroke_roman.o src/fg_structure.o src/fg_overlay.o \
	src/fg_window.o src/fg_state.o src/fg_videoresize.o src/fg_spaceball.o \
	src/fg_cursor.o src/fg_main.o src/fg_gamemode.o \
	src/fg_stroke_mono_roman.o src/fg_geometry.o src/fg_font.o src/fg_display.o \
	src/fg_font_data.o src/fg_teapot.o

x11obj = src/x11/fg_state_x11_glx.o src/x11/fg_glutfont_definitions_x11.o \
	src/x11/fg_joystick_x11.o src/x11/fg_structure_x11.o src/x11/fg_cursor_x11.o \
	src/x11/fg_state_x11.o src/x11/fg_main_x11.o src/x11/fg_spaceball_x11.o \
	src/x11/fg_input_devices_x11.o src/x11/fg_ext_x11.o src/x11/fg_window_x11_glx.o \
	src/x11/fg_gamemode_x11.o src/x11/fg_init_x11.o src/x11/fg_menu_x11.o \
	src/x11/fg_xinput_x11.o src/x11/fg_window_x11.o src/x11/fg_display_x11_glx.o \
	src/x11/fg_cmap_x11.o

obj = $(coreobj) $(x11obj)

liba = libglut.a
incpath = -I. -Isrc -Iinclude -I/usr/local/include -I/usr/X11R6/include
libpath = -L/usr/local/lib -L/usr/X11R6/lib

CFLAGS = -O2 $(incpath) -DHAVE_CONFIG_H
LDFLAGS = $(libpath)


$(liba): $(obj)
	$(AR) rcs $@ $(obj)

demo: progs/demos/3dview/3dview.o $(liba)
	$(CC) -o $@ progs/demos/3dview/3dview.o $(LDFLAGS) $(demo_libs)

.c.o:
	$(CC) -o $@ $(CFLAGS) -c $<

.PHONY: clean
clean:
	rm -f $(obj) $(liba) demo

.PHONY: install
install: $(liba)
	mkdir -p $(DESTDIR)$(PREFIX)/$(LIBDIR) $(DESTDIR)$(PREFIX)/include/GL
	cp include/GL/* $(DESTDIR)$(PREFIX)/include/GL
	cp $(liba) $(DESTDIR)$(PREFIX)/$(LIBDIR)/$(liba)

.PHONY: uninstall
uninstall:
	rm -f $(DESTDIR)$(PREFIX)/$(LIBDIR)/$(liba)
	rm -f $(DESTDIR)$(PREFIX)/include/GL/glut.h
	rm -f $(DESTDIR)$(PREFIX)/include/GL/freeglut.h
	rm -f $(DESTDIR)$(PREFIX)/include/GL/freeglut_std.h
	rm -f $(DESTDIR)$(PREFIX)/include/GL/freeglut_ext.h
	rm -f $(DESTDIR)$(PREFIX)/include/GL/freeglut_ucall.h
