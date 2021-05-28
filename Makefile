NATIVE_IMAGE_BIN := $(realpath ${GRAALVM_HOME}/bin/native-image)
SOURCES := $(shell find src/ -type f)

.DEFAULT_GOAL := target/hours-counter

target/hours-counter-0.0.0-standalone.jar: ${SOURCES}
	lein uberjar

target/hours-counter: target/hours-counter-0.0.0-standalone.jar
	${NATIVE_IMAGE_BIN} -jar ./target/hours-counter-0.0.0-standalone.jar ./target/hours-counter

target/app.js: ${SOURCES}
	lein cljsbuild once min

.PHONY: install
install:
	cp ./target/hours-counter /usr/local/bin/hours-counter

.PHONY: uninstall
uninstall:
	rm -f /usr/local/bin/hours-counter
