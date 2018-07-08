(ns app.interop
  (:require
   [cljs.nodejs :as node]))

(def fs (node/require "fs"))

(defn slurp [path]
  (.toString (.readFileSync fs path)))

(defn string->int [v]
  (js/parseInt v 10))

(defn exit [status]
  (.exit node/process status))
