NATIVE_IMAGE_BIN := $(realpath ${GRAALVM_HOME}/bin/native-image)
SOURCES := $(shell find src/ -type f)

.DEFAULT_GOAL := target/hours-counter

target/hours-counter-0.0.0-standalone.jar: ${SOURCES}
	lein uberjar

target/hours-counter: target/hours-counter-0.0.0-standalone.jar
	${NATIVE_IMAGE_BIN} -jar ./target/hours-counter-0.0.0-standalone.jar ./target/hours-counter

target/app.js: ${SOURCES}
	lein run -m cljs.main --optimizations advanced --target node --output-to target/app.js --compile app.core

target/test.js: ${SOURCES}
	lein run -m cljs.main --target node --output-to target/test.js --compile app.run-all

.PHONY: test
test: target/test.js
	lein cljfmt check
	lein test
	node ./target/test.js

.PHONY: install
install:
	cp ./target/hours-counter /usr/local/bin/hours-counter

.PHONY: uninstall
uninstall:
	rm -f /usr/local/bin/hours-counter

.PHONY: clean
clean:
	lein clean
