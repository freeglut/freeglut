<?php
require("../template.php");

# Now set the title of the page:
setPageTitle("Android");

# Make the header.
generateHeader($_SERVER['PHP_SELF']);
?>

<link rel=stylesheet href="../freeglut-style.css">

<ul>
<li><a href="#compiling">Compiling</a></li>
<li><a href="#using">Using in your projects</a></li>
<li><a href="#roadmap">Roadmap</a></li>
<li><a href="#api">New API</a></li>
<li><a href="#links">Links</a></li>
</ul>

<p>

  FreeGLUT 3.0 introduces support for the Android platform.<br />

  This platform is different than traditional desktop platforms, requiring cross-compilation, interfacing with a touchscreen, and ability for your application to be paused and resumed at any time.<br />

  Here's how:

</p>

<a name="compiling"></a>
<h1>Compiling</h1>

  <ul>

    <li>Note: at the moment, you need to chose between OpenGL ES 1.0 (FREEGLUT_GLES1) or 2.0 (FREEGLUT_GLES2) at compile time.<br />
    In the near future, we may implement a way to set this at run-time.</li>

    <li>

      Use your own cross-compiler for Android, or export the one from
      the Android NDK:

      <pre>
        /usr/src/android-ndk-r7c/build/tools/make-standalone-toolchain.sh \
          --platform=android-9 \
          --install-dir=/usr/src/ndk-standalone-9
      </pre>

    </li>

    <li>Compile FreeGLUT and install it in your Android cross-compiler
        path:

      <pre>
        PATH=/usr/src/ndk-standalone-9/bin:$PATH
        cd /usr/src/freeglut-x.x/
        mkdir cross-android-gles2/
        cd cross-android-gles2/
        cmake \
          -D CMAKE_TOOLCHAIN_FILE=../android_toolchain.cmake \
          -D CMAKE_INSTALL_PREFIX=/usr/src/ndk-standalone-9/sysroot/usr \
          -D CMAKE_BUILD_TYPE=Debug \
          -D FREEGLUT_GLES2=ON \
          -D FREEGLUT_BUILD_DEMOS=NO \
          ..
        make -j4
        make install
        # Only static for now:
        rm -f /usr/src/ndk-standalone-9/sysroot/usr/lib/libfreeglut-gles?.so*
      </pre>

    </li>

  </ul>

<a name="using"></a>
<h1>Using in your projects</h1>

  <h2>Compile your own project using common build systems</h2>

    <p>For instance if you use the autotools:</p>

    <pre>
      PATH=/usr/src/ndk-standalone-9/bin:$PATH
      export PKG_CONFIG_PATH=/usr/src/ndk-standalone-9/sysroot/usr/share/pkgconfig
      ./configure --host=arm-linux-androideabi --prefix=/somewhere
      make
      make install
    </pre>

    <p>If you use CMake, you may want to copy our Android toolchain
      'android_toolchain.cmake':</p>

    <pre>
      PATH=/usr/src/ndk-standalone-9/bin:$PATH
      export PKG_CONFIG_PATH=/usr/src/ndk-standalone-9/sysroot/usr/share/pkgconfig
      cp .../freeglut-x.x/android_toolchain.cmake .
      mkdir cross-android/
      cd cross-android/
      cmake \
        -D CMAKE_TOOLCHAIN_FILE=../android_toolchain.cmake \
        -D CMAKE_INSTALL_PREFIX=/somewhere \
        -D CMAKE_BUILD_TYPE=Debug \
        -D MY_PROG_OPTION=something ... \
        ..
      make -j4
      make install
    </pre>

    <p>Check <code>progs/test-shapes-gles1/</code> in the FreeGLUT
      source distribution for a complete, stand-alone example.</p>

<h2>Compile your own project using the NDK build-system</h2>

  <ul>

    <li>

      Create a module hierarchy pointing to FreeGLUT, with our Android.mk:

      <pre>
        mkdir freeglut-gles2/
        cp .../freeglut-x.x/android/gles2/Android.mk freeglut-gles2/
        ln -s /usr/src/ndk-standalone-9/sysroot/usr/include freeglut-gles2/include
        ln -s /usr/src/ndk-standalone-9/sysroot/usr/lib freeglut-gles2/lib
      </pre>

    </li>

    <li>

      Reference this module in your jni/Android.mk:

      <pre>
        LOCAL_STATIC_LIBRARIES := ... freeglut-gles2
        ...
        $(call import-module,freeglut-gles2)
      </pre>

    </li>

    <li>

      You now can point your NDK_MODULE_PATH to the directory containing the module:

      <pre>
        ndk-build NDK_MODULE_PATH=.
      </pre>

    </li>

  </ul>


<a name="roadmap"></a>
<h1>Roadmap</h1>

Done:
<ul>
<li>Initialize context with EGL</li>
<li>Keyboard support</li>
<li>Mouse support</li>
<li>Virtual keypad (on touchscreen)</li>
<li>Extract assets in cache dir on start-up</li>
<li>Make EGL support reusable by Mesa X11</li>
<li>freeglut_std.h can be used with GLES1 or GLES2 or non-ES headers<br />
(using -DFREEGLUT_GLES1 and -DFREEGLUT_GLES2)</li>
<li>GLES1 and GLES2 support for geometry</li>
<li>Pause/resume application support</li>
</ul>

TODO:
<ul>
<li>Open new windows (if that's possible)</li>
<li>Joystick support (xperia play...)</li>
<li>Display translucent keys on virtual keypad</li>
<li>API to detect touchscreen presence</li>
<li>API to disable assets extraction</li>
<li>Callback to reload OpenGL resources lost during a pause</li>
<li>Callback for pause/resume notifications</li>
</ul>

Possibly implemented later:
<ul>
<li>Support for menus and basic fonts</li>
</ul>

<a name="api"></a>
<h1>New API</h1>

New functions will be necessary to :
<ul>
<li>detect touchscreen presence</li>
<li>disable assets extraction</li>
</ul>

(Work In Progress)

<a name="links"></a>
<h1>Links</h1>

<ul>

  <li>http://pygame.renpy.org/ : Pygame Subset for Android, it
    designed an API for managing Android app lifecycle
    (<code>android.check_pause</code> and
    <code>android.wait_for_resume</code>)</li>

</u>

<?php generateFooter(); ?>
