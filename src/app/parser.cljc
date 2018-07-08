(ns app.parser
  (:require
   #?(:clj [instaparse.core :as insta :refer [defparser]]
      :cljs [instaparse.core :as insta :refer-macros [defparser]])
   [app.time :as t]
   [app.interop :refer [string->int exit]]))

(defparser parser "S = DAY_LOG*
              DAY_LOG = (DAY END_OF_LINE
                         END_OF_BLOCK END_OF_LINE
                         LOG_ENTRY*)
              LOG_ENTRY = (TIME_INTERVAL (END_OF_STATEMENT ' ' TIME_INTERVAL)*
                           (END_OF_LINE DESCRIPTION)* END_OF_LINE*)
              DAY = ('mo' | 'tu' | 'we' | 'th' | 'fr' | 'sa' | 'su') ':'
              END_OF_BLOCK = #'=+'
              HOUR = #'[0-2]?[0-9]'
              MINUTE = #'[0-5]?[0-9]'
              TIME = HOUR ':' MINUTE
              TIME_INTERVAL = TIME ' - ' TIME
              DESCRIPTION = #\"\\* [^\n]*\"
              END_OF_STATEMENT = ';'
              END_OF_LINE = '\n'")

(def log-entry-transforms {:HOUR string->int
                           :MINUTE string->int
                           :TIME (fn [h _ m]
                                   {:hour h
                                    :minute m})
                           :TIME_INTERVAL (fn [since _ until]
                                            (t/sub until since))})

(defn log-entry-time [coll]
  (let [time-intervals (filter #(= :TIME_INTERVAL (first %)) (rest coll))]
    (reduce t/add
            {:hour 0 :minute 0}
            (map (partial insta/transform log-entry-transforms) time-intervals))))

(defn tree->str [tree]
  (apply str
         (map (fn [item]
                (cond
                  (keyword? item) ""
                  (string? item) item
                  (coll? item) (tree->str item)))
              tree)))

(defn transform-day-log [total-sums tree]
  (let [log-entries (take-while #(= :LOG_ENTRY (first %))
                                (reverse (rest tree)))
        cnt (count log-entries)
        time-sums (atom [{:hour 0 :minute 0}])]
    (apply conj
           (into [] (drop-last cnt tree))
           (reverse (mapv (fn [log-entry]
                            (let [time-sum (log-entry-time log-entry)]
                              (swap! time-sums conj time-sum)
                              (swap! total-sums conj time-sum)
                              (into []
                                    (conj (rest log-entry)
                                          [:END_OF_LINE "\n"]
                                          [:LOG_ENTRY_TIME_SUM (t/format time-sum)]
                                          (first log-entry)))))
                          log-entries))
           [:DAY_LOG_TIME_SUM "#" (t/format (reduce t/add
                                                    {:hour 0 :minute 0}
                                                    @time-sums))]
           [:END_OF_LINE "\n"]
           [:END_OF_LINE "\n"])))

(defn transform [tree]
  (let [total-sums (atom [{:hour 0 :minute 0}])]
    (conj (mapv (partial transform-day-log total-sums) (rest tree))
          [:TOTAL_TIME_SUM "### Total hours: " (t/format (reduce t/add
                                                                 {:hour 0 :minute 0}
                                                                 @total-sums))]
          [:END_OF_LINE "\n"])))

(defn calculate [s]
  (let [result (insta/parse parser s)]
    (when (insta/failure? result)
      (println result)
      (exit 1))
    (tree->str (transform result))))
