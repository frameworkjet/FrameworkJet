# FrameworkJet - light web application framework 
![Logo](https://raw.githubusercontent.com/pavel-tashev/FrameworkJet/master/resources/frameworkJet-logo.png)

FrameworkJet is a web application framework created to cover the following requirements:
- **Low response time**.
- To work **as light as possible**, without additional heavy libraries and dependencies. It's up to you to add external libraries and helpers depending on your particular needs.
- **Server-side rendering** of the content.
- **Client-side rendering** of the content via communication with an API.
- **Multilingual** templating.

**Table of Contents**
- [Used technologies](#used-technologies)
- [Server requirements](#server-requirements)
- [Installation](#installation)
  - [Checkout](#checkout)
  - [Services](#services)
  - [Configuration](#configuration)
    - [Back-end application](#back-end-application)
		- [Framework](#framework)
		- [Third-party services](#third-party-services)
		- [Cache](#cache)
		- [Database](#database)
	- [Front-end application](#front-end-application)
  - [File permissions](#file-permissions)
  - [Web service](#web-service)
	- [Nginx](#nginx)
	- [Apache](#apache)
  - [Additional configurations (optional)](#additional-configurations-optional)
	- [CRON tasks](#cron-tasks)
- [Structure of the framework and development](#structure-of-the-framework-and-development)
  - [Structure of the framework](#structure-of-the-framework)
  - [Development](#development)
  - [Server-side](#server-side)
  	- [Routing, Controllers and Templating](#routing-controllers-and-templating)
	- [Mapping](#mapping)
	- [User session](#user-session)
  - [Client-side](#client-side) 
	- [Variables](#variables)
- [Contributing](#contributing)
- [License](#license)
	
# Used technologies
The following technologies and libraries have been used:

**Front-end:**
- JavaScript:
	- jQuery
	- JS app (router /Url: [http://krasimirtsonev.com/blog/article/A-modern-JavaScript-router-in-100-lines-history-api-pushState-hash-url](http://krasimirtsonev.com/blog/article/A-modern-JavaScript-router-in-100-lines-history-api-pushState-hash-url)/, controllers, data manager, template engine, translations). The purpose of this javascript application is to render the content on the client-side. The reason is to have an application with a reasonable loading speed.
- Templates:
	- HTML5
	- CSS3
	- LESS or SASS
	- Handlebarsjs

**Back-end:**
- Core:
	- PHP core based on APIJet framework (Url: [https://github.com/APIJet/APIJet/tree/master](https://github.com/APIJet/APIJet/tree/master))
	- Memcached
	- Monolog
	- PHPMailer
- Templates:
	- HTML5
	- Twig *(php template engine)*
	- Translations

**Server-side services:**
- composer
- npm
- grunt
- npm task "handlebars" used for precompilation of the handlebar templates
- npm task "uglify" used for javascript minimization
- npm task "less" used for the compilation of LESS to CSS
- npm task "sass" used for the compilation of SASS to CSS
- npm task "clean" used to clean the cache directory used by Twig *(php template engine)*
- npm task "watch" used to watch for changes in particular directories and files and if there are changes, it will automatically call the default grunt task which will call all other tasks (handlebars, uglify, less, sass, clean)*.

# Server requirements
The framework has a few system requirements. You will need to make sure your server meets them:
- PHP >= 5.6.4
- PHP cURL Extension
- PDO PHP Extension

# Installation
To install the framework, follow the steps below. During the installation we will use commands used in Unix based system, but for the sake of the detailed explanation, we have given a few examples for Windows based systems.

## Checkout
Checkout the project on your machine:
```
git clone https://github.com/pavel-tashev/FrameworkJet.git
```
For the current example, the name of the directory where we will clone and install the project is “example”.

Go to the directory of the project:
```
cd example
```

## Services
Before we install any of the required services, we may want to update the file composer.json as it is shown below:
```
{
	“name”: “NAME-OF-THE-PROJECT/SUB-NAME-OF-THE-PROJECT”,
	“description”: “ADD YOUR OWN DESCRIPTION”,
	...
}
```
Example:
```
{
	“name”: “framework/framework”,
	“description”: “Client side of the framework”,
	...
}
```
Save and close. 

Now we will have to install the following services:
- **composer**, follow the instructions from here [https://getcomposer.org/doc/00-intro.md](https://getcomposer.org/doc/00-intro.md) or the instructions from here [https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-14-04 ](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-14-04 )
- **npm**, follow the instructions from here: [https://github.com/npm/npm](https://github.com/npm/npm)
- **grunt**, follow the instructions from here: [http://gruntjs.com/getting-started](http://gruntjs.com/getting-started)
- also the following npm tasks for grunt are required: 
	- **uglify**, follow the instructions from here: [https://github.com/gruntjs/grunt-contrib-uglify](https://github.com/gruntjs/grunt-contrib-uglify )
	- **handlebars**, follow the instructions from here: [https://github.com/gruntjs/grunt-contrib-handlebars](https://github.com/gruntjs/grunt-contrib-handlebars) 
	- **less**, follow the instructions from here: [https://github.com/gruntjs/grunt-contrib-less](https://github.com/gruntjs/grunt-contrib-less) 
	- **sass**, follow the instructions from here: [https://github.com/gruntjs/grunt-contrib-sass](https://github.com/gruntjs/grunt-contrib-sass)
	- **clean**, follow the instructions from here: [https://github.com/gruntjs/grunt-contrib-clean](https://github.com/gruntjs/grunt-contrib-clean)
	- **watch**, follow the instructions from here: [https://github.com/gruntjs/grunt-contrib-watch](https://github.com/gruntjs/grunt-contrib-watch)

Sometimes during the installation of grunt and the npm tasks it is possible to encounter errors and missing dependencies. A possible solution is to delete the folder “node_modules” and to start the process of installation again.
```
rm -R node_modules/
```

## Configuration

### Back-end application

#### Framework
Go to Config/App.php and set up the following lines:
```php
...
return [
	...
	'DEFAULT_SYSTEM_ADMIN_EMAIL' => 'EMAIL-OF-THE-ADMINISTRATOR',
	'DEFAULT_URL' => 'URL-OF-THE-PUBLIC-WEBSITE'
];
```
Example:
```php
...
return [
	...
	'DEFAULT_SYSTEM_ADMIN_EMAIL' => 'admin@dev.framework.com',
	'DEFAULT_URL' => 'https://dev.framework.com'
];
```
Save and close.

#### Third-party services
Go to Config/Services.php and set up the following:
```php
...
return [
	'mail' => [
		<SETTINGS-USED-TO-SEND-EMAILS>
	],
	'api' => [
		<SETTINGS-USED-TO-CONNECT-TO-THE-API>
	]
];
```
Save and close.

The mail section is dedicated for the mail server which you may need to send emails. The api section is dedicated for the URL address and the output data format used to make calls to an external RESTful API.

#### Cache
Go to Config/Cache.php. This file contains the credentials required by the framework in order to connect to the Memcached server.

#### Database
Go to Config/SqlDatabase.php. This file contains the credentials required by the framework in order to connect to the SQL server.

### Front-end application
Go to public/js/app.js and set up the following:
```js
...
dataManager.config({
	main: 'URL-OF-THE-PUBLIC-WEBSITE',
	api: 'URL-OF-THE-API'
});
...
```
Example:
```js
...
dataManager.config({
	main: 'https://dev.framework.com',
	api: 'https://dev-api.framework.com'
});
...
```
Save and close.

## File permissions
Execute the following:
```
chmod -R 0755 example/
chown -R www-data example/
chgrp -R www-data example/
```

## Web service

### Nginx
If you use Nginx and SSL certificate for the encryption of the traffic, use the configuration below *(correct it according to your needs)*.
```
server {
	listen 80;
	listen [::]:80;

	root /path-to-the-framework/example/public;
	index index.php index.html index.htm;

	server_name dev.framework.com;
	rewrite ^(.*) https://dev.framework.com$1 permanent;
}

server {
	isten 443;
	server_name dev.framework.com;

	root /path-to-the-framework/example/public;
	index index.php index.html index.htm;

	ssl on;
	ssl_certificate /etc/ssl/certs/path-to-the-ssl-cert/framework.crt;
	ssl_certificate_key /etc/ssl/certs/path-to-the-ssl-cert/framework.key;

	#enables all versions of TLS, but not SSLv2 or 3 which are weak and now deprecated.
			
	ssl_protocols TLSv1 TLSv1.1 TLSv1.2;

	#Disables all weak ciphers
	ssl_ciphers "ECDHE-RSA-AES256-GCM-SHA384:ECDHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:ECDHE-RSA-AES256-SHA384:ECDHE-RSA-AES128-SHA256:ECDHE-RSA-A$”;
	ssl_prefer_server_ciphers on;

	location / {
		try_files $uri $uri/ /index.php?$query_string;
	}

	location ~ \.php$ {
		try_files $uri /index.php =404;
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		fastcgi_pass unix:/var/run/php/php7.0-fpm.sock; 
		fastcgi_index index.php;
		fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
		include fastcgi_params;
	}
}
```
If you use Nginx and DON'T use SSL certificate for the encryption of the traffic, use the configuration below *(correct it according to your needs)*.
```
server {
	listen 80;
	listen [::]:80;

	root /path-to-the-framework/example/public;
	index index.php index.html index.htm;

	server_name dev.framework.com;

	location / {
		try_files $uri $uri/ /index.php?$query_string;
	}

	location ~ \.php$ {
		try_files $uri /index.php =404;
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		fastcgi_pass unix:/var/run/php/php7.0-fpm.sock; 
		fastcgi_index index.php;
		fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
		include fastcgi_params;
	}
}
```
Check the validity of the configuration and reload:
```
nginx -t
service nginx reload
```

### Apache
If you want to run the application on Apache you will have to make sure to enable “mod_rewrite” module. For Windows, go to the directory of the Apache installation and open the folder “conf”. Open the file *httpd.conf* and find the line:
```
#LoadModule rewrite_module modules/mod_rewrite.so
```
and uncomment it.

Find all occurrences of:
```
AllowOverride None
```
And change them to:
```
AllowOverride All
```
Restart apache.

For Ubuntu you can check this short tutorial: [https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite-for-apache-on-ubuntu-14-04](https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite-for-apache-on-ubuntu-14-04).

## Additional configurations (optional)

### CRON tasks
In the root directory of the framework you will find a folder named “Tasks“. The purpose of this folder is to contain php files which can be requested by CRON tasks on regular basis. For example, the file “SystemCheckForLogs.php” checks if there are error logs generated during the last 36 hours and if there are, it sends an email to the system administrator with a detail report. You can also write your own logic.

For Ubuntu, if you want to set up a CRON task for that specific file:
```
crontab -e
```
Add the following line:
```
0 0 * * * php /path-to-the-framework/example/Tasks/SystemCheckForLogs.php
```
Save and close.

This will call the above-mentioned file on every 24 hours and it will generate reports for the system administrator, if there any error logs inside folder “logs”.

# Structure of the framework and development

More detail document will be provided in the future.

## File structure
Before we start with the development of our own application, we will need to know the purpose of each file and folder part of the framework.
```
App/ - this is the core of the framework you don’t have to change anything here

Config/ - this folder contains all configuration required by the framework

	Translations/ - here are all translations required by the php template engine

	App.php - basic configuration of the framework

	Cache.php - connection to the Memcached server

	Router.php - routers

	Services.php - here are all third-party services used by the framerwork

	SqlDatabase.php - connection to the SQL server

Controller/ - here are all controllers called by the routers

Helpers/ - here are stored classes written by us, which execute specific tasks (db connection, communication with the cache server, error logging, sending of emails, curl requests, user sessions, etc.)

images/ - every time when we use the API mapper and we have transfer of images, we will store the images inside this directory before we pass them to the API

logs/ - here are stored all error logs

node_modules/ - packages installed by npm

public/ - public directory

	assets/ - assets like images, fonts, etc.

	css/ - CSS and LESS/SASS files

	js/

		controllers/ - here are all controllers used by the javascript application

		lib/ - contains important javascript libraries like jquery, handlebars, routers, etc.
			
			dataManager.js - data manager used to executed AJAX requests to the API or other service
			
			handlebars.min.js - Handlebars front-end template engine
			
			jquery.min.js - jQuery
			
			router.js - Router
		
		translations/ - translations required by the javascript application
		
		app.js - starting point of the javascript application
		
		lib.min.js - this is minimized js file generated by grunt
		
		routes.js - routes required by the javascript application
		
		templates.js - handlebars pre-compiled templates
	
	templates/ - Handlebars templates used by the front-end
	
	.htaccess - configuration for Apache
	
	index.php - starting point of the framework
	
	robots.txt - robots file for the search engines

Tasks/ - tasks requested by CRON tasks written by us

Templates/ - Twig templates used by the back-end

vendor/ - packages managed by composer

composer.json - a list of packages required by the framework

Gruntfile.js - grunt configuration

package.json - grunt configuration
```

## Task runners and package managers

In addition, we will mention a few important things required during the development of your own application. First, the composer is used to update the packages required by the framework. To update to the most current versions, use this command:
```
composer update
```
Grunt and all npm tasks which we’ve already installed, have the following purpose.

```
grunt less
```
Compiles the LESS file */public/css/style.less* and converts it to the CSS file */public/css/style.css*.

```
grunt sass
```
Compiles the SASS file */public/css/style.scss* and converts it to a the CSS file */public/css/style.css*.

```
grunt uglify
```
Takes all javascript files, minimize them and unites them in one single file named */public/js/lib.min.js*.

```
grunt handlebars
```
Takes all handlebar templates located in *"/public/templates"* and pre-compiles them. The pre-compiled templates are saved in */public/js/templates.js*.

```
grunt clean
```
Cleans the content of folder *“cache/”*.

```
grunt
```
Executes all npm tasks listed above.

```
grunt watch
```
If you run this task in the terminal, grunt will start a script which will watch for changes in all directories and files which are related to all above tasks. If there are changes, the script will automatically run the default grunt task which on other hand will run all other tasks. As a consequence, all LESS/SASS files will be regenerated, all js files will be minified, all handlebars templates will be pre-complited, etc.

## Server-side
### Routing, Controllers and Templating
The names of the controllers and the Twig templates must be chosen according to the routes defined inside */Config/Router.php*. For example, if we have a route:
```
'example-first-page' => [Router::GET, 'example\FirstPage'],
```
We must take into account:
- The HTTP Request type */GET, DELETE, PUT, POST/*;
- The strings *“example”* and *“FirstPage”*;

They will require the following controller and template. In the folder */Controllers* we must create a new controller *(the word “example” with first capital letter)*:
```
ExampleController.php
```
With the following method *(the HTTP Request with lowercase letters and the word “FirstPage”)*:
```
getFirstPage()
```
In the folder */Templates* we must create the following sub-folder *(if it still not created)* and template *(the word “example” and the word “FirstPage”)*:
```
example/exampleFirstPage.html.twig
```
When we request *URL/example-first-page*, the framework will check for the controllers, methods and templates with these names. If any of them is not defined, it will return an error.

In the folder with Twig templates we can define other templates and macroses which can be included in any of the other templates according to the Twig rules.

### Mapping
In the routes configuration you can add the following example pattern:
```
':mapping' => '[A-Za-z0-9\-\_\/]+',
```
and example route:
```
'mapper/:mapping' => [Router::ALL, 'mapper\Request'],
```
This type of routing can be used to map requests sent to an external API. For example, if we send the following request to the framework:
```
URL/mapper/v1/content/countries/json
```
This request will be forwarded to controller “Mapper”, method “Request” and we can write our own logic which sends it to the following example API URL:
```
API-URL/v1/content/countries/json
```

### User session
The user session is managed by the helper Session which takes into account oAuth2 protocol.

Also for each Twig template we can access the variable:
```
{{ is_logged }}
```
whose purpose is to tell us uf the user is logged. It can take one of the two values - TRUE or FALSE.

## Cliend-side
### Variables
On the client-side you can access the cookie “is_logged” which has the only purpose to tell us if the user is logged. It can take one of the two values - TRUE or FALSE.	
	
# Contributing
How can you contribute:

1. Fork it.
2. Create your feature branch (git checkout -b my-new-feature).
3. Commit your changes (git commit -am 'Added some feature').
4. Push to the branch (git push origin my-new-feature).
5. Create a new Pull Request.

# License
The FrameworkJet framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
