<?php
require("../template.php");

# Now set the title of the page:
setPageTitle("OpenGL ES");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<ul>
<li><a name="#intro">Introduction</a></li>
<li><a href="#compiling">Compiling</a></li>
<li><a href="#using">Using in your projects</a></li>
</ul>

<a name="intro"></a>
<h1>Introduction</h1>

<p>freeglut can initialize an OpenGL ES (GLES) context.  It works under platforms that supports EGL:</p>
<ul>
  <li>Android (see <a href="android.php">dedicated page</a>)</li>
  <li>BlackBerry 10/BlackBerry PlayBook</li>
  <li>Unix X11 with Mesa EGL</li>
  <li>TODO: Raspberry PI: Mesa EGL doesn't work due to X11's fbdev limitation (no GL);
    it needs to use libraries from <tt>libraspeberrypi-dev</tt> which will shortcut X11
    [<a href="https://github.com/openframeworks/openFrameworks/blob/master/libs/openFrameworks/app/ofAppEGLWindow.cpp">1</a>]
    [<a href="http://sourceforge.net/p/freeglut/feature-requests/71/">2</a>]
    [<a href="https://github.com/raspberrypi/userland/">3</a>]
  </li>
</ul>

<p>freeglut ES is provided as a separate library, because OpenGL ES
has a distinct library from plain OpenGL (<tt>-lGLESv1_CM
-lGLESv2</tt> instead of <tt>-lGL</tt>, and different headers too).
We could consider dynamically loading the OpenGL symbols we need,
like <a href="http://libsdl.org/">libSDL</a>.</p>

<p>It is possible to select OpenGL ES 1, 2 or 3 (if the device and
driver supports it) by calling
e.g. <code>glutInitContextVersion(3,0)</code> before creating a
window.</p>

<a name="compiling"></a>
<h1>Compiling</h1>

<p>The following explains how to use freeglut ES for Mesa EGL.<br />
See also the <a href="android.php">Android page</a>.</p>

<p>First, check <tt>README.cmake</tt> to install the dependencies for your system.</p>

<p>Then:</p>

<pre>
apt-get install libgles1-mesa-dev libgles2-mesa-dev
cd /usr/src/freeglut-3.0.0/
mkdir native-gles/ && cd native-gles/
cmake \
  -DCMAKE_INSTALL_PREFIX=/tmp/freeglut-native-gles \
  -D CMAKE_BUILD_TYPE=Debug \
  -DFREEGLUT_GLES=ON \
  -DFREEGLUT_BUILD_DEMOS=NO \
  ..
make -j4
make install
</pre>

<a name="using"></a>
<h1>Using in your projects</h1>

<p>Get the 'freeglut-gles' module through pkg-config.</p>

<p>If you use CMake, you can do that with:</p>

<pre>
include(FindPkgConfig)
pkg_check_modules(freeglut REQUIRED freeglut-gles>=3.0.0)
if(freeglut_FOUND)
  include_directories(${freeglut_STATIC_INCLUDE_DIRS})
  link_directories(${freeglut_STATIC_LIBRARY_DIRS})
  set(CMAKE_C_FLAGS "${CMAKE_C_FLAGS} ${freeglut_STATIC_CFLAGS_OTHER}")
  add_definitions(${freeglut_STATIC_CFLAGS_OTHER})
endif()
</pre>

<pre>
cd your_project/
mkdir native-gles/ && cd native-gles/
PKG_CONFIG_PATH=/tmp/freeglut-native-gles/share/pkgconfig/ cmake ..
</pre>

<p>Examples:</p>
<ul>
  <li>OpenGL Wikibook's
    <a href="https://gitorious.org/wikibooks-opengl/modern-tutorials/source/HEAD:tut04_transform-gles2"><tt>tut04_transform-gles2</tt>
    example</a>: it uses a basic Makefile targeting <tt>freeglut-gles</tt></li>
  <li><tt>progs/test-shapes-gles1/</tt> in the source distribution:
    it is a standalone CMake app that uses freeglut GLES (v1).</li>
</ul>

<?php generateFooter(); ?>
