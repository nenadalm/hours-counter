[![Build Status](https://api.travis-ci.org/nenadalm/hours-counter.png?branch=master)](http://travis-ci.org/nenadalm/hours-counter)

Hours-counter
=============

Simple program to calculate your hours

Format of logging your hours
-----------------------------
```
tu:
===========
9:35 - 10:18; 11:18 - 13:59
* first task

10:18 - 11:18
* second task

mo:
================
9:00 - 9:53
* note here
* one more note
```

Calculate hours per task/day:
-----------------------------
```$ hours-counter hours.txt```

```
tu:
===========
3:24 (3.400000)
9:35 - 10:18; 11:18 - 13:59
* first task

1:00 (1.000000)
10:18 - 11:18
* second task

#4:24 (4.400000)

mo:
================
0:53 (0.883333)
9:00 - 9:53
* note here
* one more note

#0:53 (0.883333)
```

Installation
============
```
$ git clone git@github.com:nenadalm/hours-counter.git /opt/hours-counter
$ composer install --no-dev
$ ln -s /opt/hours-counter/bin/run.php /usr/local/bin/hours-counter
```
