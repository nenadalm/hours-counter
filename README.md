[![Build Status](https://api.travis-ci.org/nenadalm/hours-counter.png?branch=master)](http://travis-ci.org/nenadalm/hours-counter)

# Hours-counter

Simple program to calculate your hours

## Format of logging your hours

```
we:
==========
15:25 - 17:30
* stuff here
* more stuff here

7:30 - 11:15
* colon: makes a troubles

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

## Calculate hours per task/day:

```$ hours-counter hours.txt```

```
we:
==========
2:05 (2.083333)
15:25 - 17:30
* stuff here
* more stuff here

3:45 (3.750000)
7:30 - 11:15
* colon: makes a troubles

#5:50 (5.833333)

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

### Total hours: 11:07 (11.116667)
```

## Installation

```
$ git clone git@github.com:nenadalm/hours-counter.git
$ cd ./hours-counter
```

### GraalVM (recommended)

This option is recommended as the program is fastest.

GraalVM can be downloaded [here](https://github.com/oracle/graal/releases).

```
$ lein uberjar
$ "${GRAALVM_HOME}/bin/native-image" -jar ./target/hours-counter-0.0.0-standalone.jar ./target/hours-counter
$ cp ./target/hours-counter /usr/local/bin/hours-counter
```

### NodeJS

```
$ lein cljsbuild once min
$ cp target/app.js /usr/local/bin/hours-counter
$ chmod +x /usr/local/bin/hours-counter
```

### JVM

```
$ lein uberjar
$ cp ./target/hours-counter-0.0.0-standalone.jar /usr/local/bin/hours-counter.jar
$ cat > /usr/local/bin/hours-counter <<'EOF'
#!/usr/bin/env sh
java -jar /usr/local/bin/hours-counter.jar "$@"
EOF
$ chmod +x /usr/local/bin/hours-counter
```

## Tips


* if you want to have monthly reports, you can create bash aliases or some scripts which will open current file for current month or create one if it doesn't exists yet

```
alias hce='gvim --cmd "cd ~/Documents/hours-counter" ~/Documents/hours-counter/hours_$(date +%Y_%m)'
alias hc='hours-counter ~/Documents/hours-counter/hours_$(date +%Y_%m) | less'
```

* ```hce``` command from example above will open or create file for current mount (e.g.  ~/Documents/hours-counter/hours_2015_01)
* ```hc``` command from example above will calculate hours from file for current month (e.g. ~/Documents/hours-counter/hours_2015_01)
