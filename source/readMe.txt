==============================================================================
[[[[The things you must do:]]]]

*Must enable the following line in httpd.conf for url rewritting
LoadModule rewrite_module modules/mod_rewrite.so

*Must enable php_openssl for sending email

*Must install php_curl for web service connection

*Normally, tomcat doesn't accept slash in the URL. It will cause failure when post message if it contains any slash.
Solution:
using the following command line arguments:
-Dorg.apache.tomcat.util.buf.UDecoder.ALLOW_ENCODED_SLASH=true
-Dorg.apache.catalina.connector.CoyoteAdapter.ALLOW_BACKSLASH=true
or by adding the following to $CATALINA_HOME/conf/catalina.properties:
org.apache.tomcat.util.buf.UDecoder.ALLOW_ENCODED_SLASH=true
org.apache.catalina.connector.CoyoteAdapter.ALLOW_BACKSLASH=true
==============================================================================
[[[[The things you may need to do:]]]]

*May need to define the timezone in php.ini
; Defines the default timezone used by the date functions
date.timezone = UTC

*May need to define your web service path
change in system/config/settings.php
define("curlConnectionUrl",'http://localhost:8080/GSN_WS');

*For convenience, you may need to grant all permissions to the root folder
$ sudo chmod -R 777 /var/www