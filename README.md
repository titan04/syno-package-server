Synology Package Server
=======================
Synology introduced a new Package Center with DSM 3.2. This Package Center accept external sources
which is really useful for package developers.
syno-package-server is a PHP environment to build up your own Synology Package Server. It requires
a MySQL database and a web-server with PHP support.

Requirements
------------
* MySQL database
* Web-server with PHP support

Install
-------
* Clone the repository somewhere in your server
* Configure your database in db.php
* Create the database with db.sql
* Create a virtual host to the www directory
* (Optional) Restrict access to uploadpackage.php using .htaccess/.htpasswd

Use
---
* Create a SPK using [syno-packager](https://github.com/Diaoul/syno-packager) or anything you like
* Upload your SPK on the server at http://yourdomain.tld/uploadpackage.php or using `make -f yourpackage.mk publish` with syno-packager
* Add http://yourdomain.tld/getpackages.php in the Package Center in DSM, you should see your packages!


Please contribute to this project and make it even better!
Also, that would be preferable not to make duplicates SPKs, contact me if you feel you are going to make a duplicate ;)
