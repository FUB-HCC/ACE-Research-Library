
This Readme is for installaion of Wordpress-Fontend.

To run WordPress we recommend your host supports:

    PHP version 5.6 or greater
    MySQL version 5.6 or greater OR MariaDB version 10.0 or greater

/That’s really it. We recommend Apache or Nginx as the most robust and featureful server for running WordPress, but any server that supports PHP and MySQL will do. That said, we can’t test every possible environment and each of the hosts on our hosting page supports the above and more with no problems./


Steps:
1. You must copy the whole directory to server.
2. You must import the database from dump-file. This file find you in folder "dump"
3. You must to change the WordPress Address and Blog Address. Go to the database of your site and find the wp_options table. This table stores all the options that you can set in the interface. The WordPress Address and Blog Address are stored as siteurl and home (the option_name field). All you have to do is change the option_value field to the correct URL for the records with option_name=’siteurl‘(by me http://192.168.151.128/wordpress) or option_name=’home‘(by me http://192.168.151.128/wordpress).
4. Database name, login and password must you change in file wp-config.php in root directory according to you database configuration.

5. Change the file config_app.js in "~/wp-content/themes/genesis/js" directory with actual server ip address.
6. Put the file paper.json in a root directory of you web-server(in /var/www/html/ by default, when you use apache2).
The main files are in directories ~/wp-content/themes/genesis and ~/wp-content/themes/genesis-sample


I used on server with ip address 192.168.151.128

You can access the admin site of wordpress, when you put after link of website wp-admin.

Login and password for wp admin:
login: tarix
password: 123321

Database configuration, that i use:
databasename: wordpress
database login: tarix
database password: 12

