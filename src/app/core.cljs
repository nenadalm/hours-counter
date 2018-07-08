(ns app.core
  (:require
   [app.parser :refer [calculate]]
   [app.interop :refer [slurp]]))

(enable-console-print!)

(println (calculate (slurp (aget (.-argv js/process) 2))))
