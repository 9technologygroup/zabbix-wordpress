# zabbix-wordpress
A zabbix template for monitoring wordpress websites

## Features

Monitor :
1) Wordpres current version
2) Available WP version
3) If WP update is needed
4) Users registerd on website
5) if wp-config is on permission 600
6) if salts are defined
7) if file editing is disabled
8) if https is enforced or not for wp-admin

## Instructions

1) Place the wpcheckv2.php file in the root of your website directory
2) edit wpcheckv2.php to enter in the zabbix ip, which will be the only ip to perform the check and invoke the php file for JSON response
3) upload the xml template to zabbix (tested with version 7.0)
4) Apply the template to a host.

### Notes

1) Macro of {HOST.HOST} doesnt always work for some reason, so on your host, change hte macro value to the website domain like "example.com"
2) default protocol macros is "https" and can be changed if you need http for whatever reason
3) if your site lives on "example.com/wordpress" as in the directory is additional, then reflect this in the "URL" macro

Version 1.0

## To Do

1) Create triggers for Users added, Users deleted
3) Create dashboard template
4) Additional Information around plugin versions
5) Additional Information around security
6) Additional Information around Users last login, Last password set
7) Create trigger for when password is reset
8) Start looking at monitoring wp performance
9) Start integrating with litehouse

