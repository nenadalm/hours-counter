(ns app.config
  #?(:clj (:require [clojure.java.io])))

#?(:cljs (goog-define VERSION "unknown"))

(defn- app-version []
  #?(:clj (if-some [f (some-> "META-INF/app/config.properties"
                              clojure.java.io/resource
                              clojure.java.io/reader)]
            (let [properties (java.util.Properties.)]
              (.load properties f)
              (.get properties "version"))
            "unknown")
     :cljs VERSION))

(def config {:app.config/version (app-version)})
