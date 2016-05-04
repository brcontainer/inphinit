#About

PHP framework, routes, controllers and views

#Requirements

1. PHP 5.3.0+
1. Multibyte String (GD also) (optional, only used in `Inphinit\Helper::makeUrl`)
1. libiconv (optional, only used in `Inphinit\Helper::makeUrl`)
1. fileinfo (optional, only used in `Inphinit\File::mimeType`)
1. Apache or Nginx or IIS for production

#Perfomance

The reason I created this framework was to try to achieve a good performance and have the basic features of a framework with routes.

##Machine

- i5 CPU 2.53Ghz
- 8GB Ram
- Windows 8.1 64bit
- PHP5.6
- Apache2.4 (apache2handler module)


##Memory peak comparison

- Inphinit: 389.4921875
- Silex: 2426.9140625
- Lumen: 2595.03125
- laravel5.2: 7869.96875
- CodeIgniter3: 1284.6875
- FatFree: 990.140625

![memory peak](https://i.imgsafe.org/cdd4786.png)

##Requests per second comparison

- Inphinit: 688.15 requests per second
- Silex: 93.45 requests per second
- Lumen: 87.89 requests per second
- Laravel5.2: 14.17 requests per second
- CodeIgniter3: 141.33 requests per second
- FatFree: 385.82 requests per second

![requests per second](https://i.imgsafe.org/cf583c9.png)

#Getting start

1. Have two method for install
1. First method, download [Composer](http://getcomposer.org/doc/00-intro.md)
1. Install in Windows:

  ```
  cd c:\wamp\www\
  composer create-project brcontainer/inphinit:dev-master [project_name]
  ```

1. Or (if no using Wamp/Xampp/easyphp)

    ```
    cd c:\Users\[username]\Documents\
    composer create-project brcontainer/inphinit:dev-master [project_name]
    ```

1. Install in Unix-like:

    ```
    cd /etc/www/
    php composer.phar create-project brcontainer/inphinit [project_name]
    ```

1. Or (if no using Apache)

    ```
    cd /etc/www/
    php composer.phar create-project brcontainer/inphinit:dev-master [project_name]
    ```

1. Alternate is download git repository and copy content from zip file to folder project

#Apache

1. If using Apache (or Xampp, Wamp, Easyphp, etc) only navigate to `http://localhost/[project_name]/`
1. Navigate `http://localhost/[project_name]/generate-htaccess.php` for create .htaccess

#PHP built-in web server in Windows

1. Navigate with explorer.exe to project folder
1. Find and edit server.bat, change php.exe path and php.ini for your php path:

    ```
    set PHP_BIN="C:\php\php.exe"
    set PHP_INI="C:\php\php.ini"
    set HOST_PORT=9000
    ```
1. Save the edition and run `server.bat`
1. Open webbrowser and navigate to `http://localhost:9000`

#PHP built-in web server in Linux and Mac (or Unix-like)

1. If using Linux or Mac navigate to project folder and run using terminal:

    ```
    cd /home/[project_name]/
    php -S localhost:9000 server.php
    ```

1. Open web-browser and navigate to `http://localhost:9000`

1. Or edit server.sh

    ```
    #!/bin/bash
    PHP_BIN="/usr/bin/php"
    PHP_INI="/etc/php5/cli/php.ini"
    HOST_PORT=9000
    ```

1. Save edition and run server.sh

#Routing

1. In folder `[project_name]/system/` find `main.php` and put something like this:

    Route::set('GET', '/foo', 'MyController:action');

1. In `[project_name]/system/application/Controller/` folder create an file with this name `MyController.php` (case sensitive)
1. Put this content:

    ```
    <?php
    namespace Controller;

    use Inphinit\View;

    class MyController
    {
        public function action()
        {
            $data = array( 'foo' => 'Hello', 'Baz' => 'World!' );
            View::render('myview', $data);
        }
    }
    ```

1. In `[project_name]/system/application/View/` create file with this name `myview.php` (case sensitive) and put:

    ```
    <p><?php echo $foo, ' ', $baz; ?></p>
    ```

1. Navigate to `http://localhost:9000/foo` or `http://localhost/[project_name]/foo`

#Developer vs production

For setup access `[project_name]/system/application/Config/config.php` with your text editor and change `developer` key to `true` or `false`:

    ```
    <?php
    return array(
        'appdata_expires' => 86400,
        'developer'       => true,
        'maintenance'     => false
    );
    ```

#Checking webserver requirements

For check requeriments navigate with your web-browser to `http://localhost:9000/check.php` or `http://localhost/[project_name]/check.php`

#Nginx

For create nginx config run with terminal:

```
cd /home/[project_name]/
php generate-nginx.php
```

And copy content to clipboard and adjust `nginx.conf`

#IIS

Move content of project folder to root IIS folder (note: Inphinit is tested in IIS Express)
