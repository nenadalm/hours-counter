(ns app.run-all
  (:require
   [doo.runner :refer-macros [doo-tests]]
   [app.time-test]
   [app.parser-test]))

(doo-tests 'app.time-test
           'app.parser-test)

