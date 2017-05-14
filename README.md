FreeGLUT
========

This readme file applies to the git clone of FreeGLUT's svn repo on [sourceforge.net](http://freeglut.sourceforge.net/) that is hosted on [github](https://github.com/dcnieho/FreeGLUT). This is provided here to enable user to contribute easier.

Every effort is made to ensure that the ``git_master`` branch only progresses in a fastforward manner, but complete guarantees cannot be given as the [sourceforge.net trunk](https://sourceforge.net/p/freeglut/code/HEAD/tree/) is at all times leading.

I will be unable to merge a pull request if the github clone is behind ``svn/trunk`` on sourceforge.net. Should you detect this, please contact me, maybe through making an issue here on github.

This copy of the svn repository was not created with ``git svn`` but with the excellent svn support of [SmartGit](http://www.syntevo.com/).


Notes to self on how to work with this:
 Basic workflow upon receiving a pull request is as follows:
- Merge pull request on github
- pull in changes to my local clone of the ``git_master`` branch.
- cherry pick the merged commits onto my local clone of the ``svn/trunk`` branch
- push these commits to svn remote (this alters the commits in the local 
  ``svn/trunk`` clone as extra meta data is added)
- merge ``svn/trunk`` into local ``git_master``
- push merge commit to github (its fast forward!)
