(ns app.time
  (:refer-clojure :exclude [format])
  (:require
   [clojure.string]))

(defn sub [t1 t2]
  (let [h (- (:hour t1) (:hour t2))
        m (- (:minute t1) (:minute t2))]
    (if (< m 0)
      {:hour (dec h)
       :minute (+ 60 m)}
      {:hour h
       :minute m})))

(defn add [t1 t2]
  (let [h (+ (:hour t1) (:hour t2))
        m (+ (:minute t1) (:minute t2))
        rom (mod m 60)]
    {:hour (+ h (/ (- m rom) 60))
     :minute rom}))

(defn format-float [f]
  #?(:clj (clojure.core/format "%.4f" f)
     :cljs (let [str-float (str (/ (Math/round (* 10000 f)) 10000))
                 [f s] (clojure.string/split str-float #"\.")]
             (apply (partial str f ".")
                    (concat s (repeat (- 4 (count s)) "0"))))))

(defn format [t]
  (let [h (:hour t)
        m (:minute t)]
    (str h ":" (when (< m 10) "0") m " (" (format-float (float (+ h (/ m 60)))) ")")))

