(ns app.interop)

(defn string->int [^String v]
  (Integer/valueOf v))

(defn exit [status]
  (System/exit status))
