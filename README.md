ylang-ylang [![Build Status](https://travis-ci.org/spolischook/ylang-ylang.svg?branch=master)](https://travis-ci.org/spolischook/ylang-ylang) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spolischook/ylang-ylang/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/spolischook/ylang-ylang/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/spolischook/ylang-ylang/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/spolischook/ylang-ylang/?branch=master)
=======

Web application that provides access to web server access logs in a
shared hosting environment.

Setting PAM
-----------

You need to set you server for properly work with PAM.
First of all, install pam extension:
```
pecl install pam
```
To have ability login with unix user you need to add php process user (www-data) to group of /etc/shadow (shadow).
See group owner by typing:
```
ls -l /etc/shadow
```
see more about this - http://svn.php.net/viewvc/pecl/pam/trunk/README?view=markup#l85

What inside
-----------

You need to login as Unix* user with your username and password

At admin you can see your user logs.
Filter it.
If you are one of admin users (setted in parameters.yml) you can see all logs for all users, and filter logs by user

Time track
----------

![time](http://i.imgur.com/QhJenEv.png)

ToDo
----

- Rest Api
- Fix time interval label view
- Add db indexes
- Fix per page pagination
- Add tests for console command
- Add functional tests for admin index
