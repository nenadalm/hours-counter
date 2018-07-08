(defproject hours-counter "0.0.0"
  :global-vars {*warn-on-reflection* true}
  :dependencies [[org.clojure/clojure "1.9.0"]
                 [org.clojure/clojurescript "1.9.946"]
                 [instaparse "1.4.8"]
                 [cljsjs/nodejs-externs "1.0.4-1"]]
  :profiles {:dev {:dependencies [[pjstadig/humane-test-output "0.8.3"]]
                   :injections [(require 'pjstadig.humane-test-output)
                                (pjstadig.humane-test-output/activate!)]
                   :plugins [[com.jakemccrary/lein-test-refresh "0.22.0"]
                             [lein-cljsbuild "1.1.7"]
                             [lein-doo "0.1.10"]]}
             :uberjar {:aot :all}}
  :main app.core
  :cljsbuild {:builds [{:id "min"
                        :source-paths ["src"]
                        :compiler {:main app.core
                                   :target :nodejs
                                   :output-to "target/app.js"
                                   :optimizations :advanced}}
                       {:id "test"
                        :source-paths ["src" "test"]
                        :compiler {:main app.run-all
                                   :target :nodejs
                                   :output-to "target/test.js"}}]}
  :aliases {"cljfmt" ["update-in" ":plugins" "conj" "[lein-cljfmt \"0.5.7\"]" "--" "cljfmt"]})
