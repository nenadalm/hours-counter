(defproject hours-counter "0.0.0"
  :global-vars {*warn-on-reflection* true}
  :dependencies [[org.clojure/clojure "1.10.3"]
                 [org.clojure/clojurescript "1.10.866"]
                 [instaparse "1.4.10"]
                 [cljsjs/nodejs-externs "1.0.4-1"]]
  :profiles {:dev {:dependencies [[pjstadig/humane-test-output "0.11.0"]]
                   :injections [(require 'pjstadig.humane-test-output)
                                (pjstadig.humane-test-output/activate!)]
                   :plugins [[com.jakemccrary/lein-test-refresh "0.24.1"]]}
             :uberjar {:aot :all}}
  :main app.core
  :aliases {"cljfmt" ["update-in" ":plugins" "conj" "[lein-cljfmt \"0.7.0\"]" "--" "cljfmt"]})
