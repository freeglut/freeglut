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

<p>FreeGLUT can initialize an OpenGL ES (GLES) context.  It works under platforms that supports EGL:</p>
<ul>
  <li>Android (see <a href="android.php">dedicated page</a>)</li>
  <li>BlackBerry 10/BlackBerry PlayBook</li>
  <li>Unix X11 with Mesa EGL</li>
</ul>

<p>FreeGLUT ES is provided as a separate library, because OpenGL ES has a distinct,
incompatible library for each version (e.g. -lGLESv1_CM and -lGLESv2).</p>

<p>When compiled for OpenGL ES 2.0, it is possible to use OpenGL ES 3.0 and higher if the device or
driver supports it by calling <code>glutInitContextVersion(3.0, 0.0)</code> before creating a window.</p>

<p>The following explains how to use FreeGLUT ES under Mesa EGL.</p>

<a name="compiling"></a>
<h1>Compiling</h1>

<p>Here's how to compile FreeGLUT for GLES2:</p>

<pre>
aptitude install libgles2-mesa-dev
cd /usr/src/freeglut-3.0.0/
mkdir native-gles2/ && cd native-gles2/
cmake \
  -DCMAKE_INSTALL_PREFIX=/tmp/freeglut-native-gles2 \
  -D CMAKE_BUILD_TYPE=Debug \
  -DFREEGLUT_GLES2=ON \
  -DFREEGLUT_BUILD_DEMOS=NO \
  ..
make
make install
</pre>

<p>For GLES1:</p>

<pre>
aptitude install libgles1-mesa-dev
cd /usr/src/freeglut-3.0.0/
mkdir native-gles1/ && cd native-gles1/
cmake \
  -DCMAKE_INSTALL_PREFIX=/tmp/freeglut-native-gles1 \
  -D CMAKE_BUILD_TYPE=Debug \
  -DFREEGLUT_GLES1=ON \
  -DFREEGLUT_BUILD_DEMOS=NO \
  ..
make
make install
</pre>

<a name="using"></a>
<h1>Using in your projects</h1>

<p>Get the 'freeglut-gles2' module through pkg-config.</p>

<p>If you use CMake, you can do that with:</p>

<pre>
include(FindPkgConfig)
pkg_check_modules(freeglut REQUIRED freeglut-gles2>=3.0.0)
if(freeglut_FOUND)
  include_directories(${freeglut_STATIC_INCLUDE_DIRS})
  link_directories(${freeglut_STATIC_LIBRARY_DIRS})
  set(CMAKE_C_FLAGS "${CMAKE_C_FLAGS} ${freeglut_STATIC_CFLAGS_OTHER}")
  add_definitions(${freeglut_STATIC_CFLAGS_OTHER})
endif()
</pre>

<pre>
cd your_project/
mkdir native-gles2/ && cd native-gles2/
PKG_CONFIG_PATH=/tmp/freeglut-native-gles2/share/pkgconfig/ cmake ..
</pre>

<p>See for instance <code>progs/test-shapes-gles1/</code> in the source distribution:
it is a standalone CMake app that uses FreeGLUT GLES1.</p>

<?php generateFooter(); ?>
