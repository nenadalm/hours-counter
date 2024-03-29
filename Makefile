NATIVE_IMAGE_BIN := $(realpath ${GRAALVM_HOME}/bin/native-image)
SOURCES := $(shell find deps.edn src/ test/ -type f)
APP_VERSION ?= unknown

.DEFAULT_GOAL := target/hours-counter

target/hours-counter.jar: ${SOURCES}
	clojure -X:uberjar

target/hours-counter: target/hours-counter.jar
	${NATIVE_IMAGE_BIN} -jar ./target/hours-counter.jar ./target/hours-counter

target/hours-counter.js: ${SOURCES}
	clojure -M -m cljs.main \
		--optimizations advanced \
		--target node \
		--output-to target/hours-counter.js \
		--compile-opts '{:closure-defines {app.config/VERSION "${APP_VERSION}"}}' \
		--compile app.core

target/test.js: ${SOURCES}
	clojure -M:build-js-test

.PHONY: test
test: target/test.js
	clojure -M:cljfmt check
	clojure -M:clj-kondo
	clojure -M:test
	node ./target/test.js

.PHONY: install
install:
	cp ./target/hours-counter /usr/local/bin/hours-counter

.PHONY: uninstall
uninstall:
	rm -f /usr/local/bin/hours-counter

.PHONY: clean
clean:
	rm -rf target
