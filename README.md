ylang-ylang [![Build Status](https://travis-ci.org/spolischook/ylang-ylang.svg?branch=master)](https://travis-ci.org/spolischook/ylang-ylang)
=======

Web application that provides access to web server access logs in a
shared hosting environment.

Setting PAM
-----------

You need to set you server for properly work with PAM.
To have ability login with unix user you need to add php process user (www-data) to group of /etc/shadow (shadow).
See group owner by typing:
```
ls -l /etc/shadow
```
see more about this - http://svn.php.net/viewvc/pecl/pam/trunk/README?view=markup#l85
