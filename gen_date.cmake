# gen_date.cmake
string(TIMESTAMP VERSION_BUILD_DATE "%Y-%m-%d %H:%M:%S")
configure_file(${INPUT_FILE} ${OUTPUT_FILE} @ONLY)
