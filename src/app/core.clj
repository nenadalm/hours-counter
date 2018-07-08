(ns app.core
  (:require
   [app.parser :refer [calculate]])
  (:gen-class))

(defn -main [& args]
  (println (calculate (slurp (first args)))))
