(ns app.run-all
  (:require
   [cljs.test :refer [run-all-tests]]
   [pjstadig.humane-test-output]
   [app.time-test]
   [app.parser-test]))

(defmethod cljs.test/report [:cljs.test/default :end-run-tests] [m]
  (if-not (cljs.test/successful? m)
    (js/process.exit 1)))

(run-all-tests #"app\..*-test")
