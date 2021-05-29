(ns app.core
  (:require
   [app.parser :refer [calculate]]
   [app.config :refer [config]])
  (:gen-class))

(defn- usage []
  (str "Version: " (:app.config/version config) "

SYNOPSIS
  hours-counter <file>"))

(defn -main [& args]
  (if-let [file (first args)]
    (println (calculate (slurp file)))
    (println (usage))))
