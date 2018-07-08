(ns app.parser-test
  (:require
   [app.parser :as parser]
   [clojure.test :refer [deftest is testing]]
   [instaparse.core :as insta]
   #?(:cljs [app.interop :refer [slurp]])))

(deftest parse-test
  (testing "Invalid input"
    (is (insta/failure? (parser/parser (slurp "test/fixtures/missingSemicolon")))))
  (testing "Valid input"
    (let [parsed-result (parser/parser (slurp "test/fixtures/fixture"))]
      (is (not (insta/failure? parsed-result))
          parsed-result))))

(deftest calculate-test
  (testing "Valid input"
    (is (= (slurp "test/fixtures/fixtureResult")
           (parser/calculate (slurp "test/fixtures/fixture"))))))
