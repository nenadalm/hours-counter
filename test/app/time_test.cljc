(ns app.time-test
  (:require
   [app.time :as t]
   [clojure.test :refer [deftest is]]))

(deftest add-test
  (is (= {:hour 8 :minute 50}
         (t/add {:hour 5 :minute 25}
                {:hour 3 :minute 25})))
  (is (= {:hour 14 :minute 20}
         (t/add {:hour 10 :minute 32}
                {:hour 3 :minute 48})))
  (is (= {:hour 28 :minute 33}
         (t/add {:hour 11 :minute 58}
                {:hour 16 :minute 35})))
  (is (= {:hour 8 :minute 5}
         (t/add {:hour 3 :minute 2}
                {:hour 5 :minute 3}))))

(deftest sub-test
  (is (= {:hour 2 :minute 0}
         (t/sub {:hour 5 :minute 25}
                {:hour 3 :minute 25})))
  (is (= {:hour 6 :minute 44}
         (t/sub {:hour 10 :minute 32}
                {:hour 3 :minute 48}))))

(deftest format-test
  (is (= "8:50 (8.8333)"
         (t/format {:hour 8 :minute 50})))
  (is (= "14:20 (14.3333)"
         (t/format {:hour 14 :minute 20})))
  (is (= "28:33 (28.5500)"
         (t/format {:hour 28 :minute 33})))
  (is (= "8:05 (8.0833)"
         (t/format {:hour 8 :minute 5}))))
