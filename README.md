# CakePHP Mustache Helper Plugin
A helper for configuring and wrapping methods for the [Mustache template engine](http://mustache.github.com/).

## Configuring

Install it from command line with the [Composer](http://http://getcomposer.org/) in your project:


```
composer require nod.st/cake-mustache
```

Load the plugin in your:


```php
<?php
//./Config/bootstrap.php
CakePlugin::load(array(
    'Mustache'  => array(
        'bootstrap' => true
    )
));
?>
```

Add it to a Controller:
(Note: Below option values are default settings, and you don't need to pass them as long as you would like to change it.)

```php
<?php
//./Controller/AppController.ctp
class AppController extends Controller {
    public $helpers = array(
        'Mustache.Mustache' => array(
            'path'          => './../webroot/mustache',
            'extension'     => 'mustache',
            'viewVariables' => true
        )
    );
}
?>
```

Optionally you can also change set the path with `Configure` class with:

```php
<?php
//For example, ./Config/bootstrap.php
Configure::write('Mustache.path', '/my/custom/and/awesome/folder/path/to/mustache/templates');
?>
```

Create some mustache templates for being sure it's working:

```html
<!-- ./webroot/mustache/test.mustache -->
<p>Test1: {{text}}</p>
{{> test2}}
```

```html
<!-- ./webroot/mustache/test2.mustache -->
<p>Test2: {{text}}</p>
```

You can now render the template on your views:

```php
<?php
//./View/Layouts/default.ctp
echo $this->Mustache->render('test.mustache', array(
    'text'  => 'Hello Mustache!'
));
?>
```

Is it working? Awesome! No? Then you're welcome to create an issue with some details.