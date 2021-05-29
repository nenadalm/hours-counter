(ns app.core
  (:require
   [app.parser :refer [calculate]]
   [app.interop :refer [slurp]]
   [app.config :refer [config]]))

(enable-console-print!)

(defn- usage []
  (str "Version: " (:app.config/version config) "

SYNOPSIS
  hours-counter <file>"))

(if-let [file (aget (.-argv js/process) 2)]
  (println (calculate (slurp file)))
  (println (usage)))
