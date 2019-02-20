# Art-Explorer
Setup the Apache:
1)	To install apache:		sudo apt install apache2
2)	Edit the configuration of apache2.conf
-	vim /etc/apache2/apache2.conf
-	Add these lines:
<Directory /home/rtiwari1/compiler/>
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
</Directory>
-	vim /etc/apache2/sites-enabled/000-default.conf
-	Replace the line : 
DocumentRoot /var/www/html/  by DocumentRoot /home/rtiwari1/compiler/

3)	Install PHP:			sudo apt-get install php5 libapache2-mod-php5
4)	Start the apache server:		sudo /etc/init.d/apache2 restart
5)	Testing the php:
-	cd  /home/rtiwari1/compiler/
-	Write a small php code to test whether setup is working or not.
-	vim index.php
-	<?php  echo “Setup is working..” ?>
-	Run a php file from localhost browser. Open browser.
-	Add ip address of host in the url, for example: 10.66.245.110
-	It should display message: “Setup is working..”.

Add android binaries:
1)	Create a directory in home to keep the binaries of the Android source.
2)	mkdir  /home/rtiwari1/compiler
3)	Create a directory for Android-O, And-P, And-Q
4)	cd ~/compiler/
5)	mkdir andP andQ andO
6)	 Copy the out directory contents of Android build into the respective android directory.

Add the project:
1) Download codemirror github project https://github.com/codemirror/CodeMirror
2) Replace the codemirror files by above files defined in code.
3) Download the project Art-explorer in compiler directory of your local machine
3)	Open the browser and the url to use the compiler explorer by giving ip address of machine.
