# HiHungry
Spring 18 CS411 - Database Systems

Live Site: http://gizmohihungry.web.engr.illinois.edu/ (test user: *samhuang*, password: *password*)

Video Demo: https://www.youtube.com/watch?v=BPI_TsDOnHs&feature=youtu.be

- **Advanced Function 1** - Personalized Restaurant Recommendations (index.php)[../index.php]
- **Advanced Function 2** - Text and Sentiment Analysis on Restaurant Reviews (analysis_local.py)[../analysis/analysis_local.py]

## Run Locally
- Dependencies: PHP 3.1.0, Boostrap 3.3.7, jQuery 3.3.1, Font Awesome 4.3.0
- Add database information in /app/connect.php
- Run `php -S localhost:8000`

## Folders
- /analysis/ - contains the Python scripts and data used to perform one of our advanced functions
- /populate_database/ - contains the Python scripts used to populate the MySQL database with data from Zomato API

## Setup Links
- https://stackoverflow.com/questions/4359131/brew-install-mysql-on-mac-os
- https://jason.pureconcepts.net/2015/10/install-apache-php-mysql-mac-os-x-el-capitan/

## cPanel Server Information
- Hosting Package: ProjectShellPackage
- Server Name:	cpanel3
- cPanel Version:	68.0 (build 37)
- Apache Version:	2.4.29
- PHP Version:	5.6.32
- MySQL Version:	10.0.33-MariaDB
- Architecture:	x86_64
- Operating System:	linux
- Shared IP Address:	192.17.90.133
- Path to Sendmail:	/usr/sbin/sendmail
- Path to Perl:	/usr/bin/perl
- Perl Version:	5.16.3
- Kernel Version:	3.10.0-327.22.2.el7.x86_64
