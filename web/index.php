<?php

error_reporting(-1);

$cacheDir = __DIR__ . '/../silex/resources/cache';

if (!is_dir($cacheDir) || !is_writable($cacheDir)) {
    $assetsPath = ((!is_file(__DIR__ . '/../vendor/autoload.php')) ? './../fezfez/code-generator/web/' : '' );
?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="<?php echo $assetsPath; ?>'/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $assetsPath; ?>'/assets/css/styles.css">
        <title>Code Generator error</title>
        </head>
        <body class="container">
        <div class="col-lg-8" style="float: none;margin: 0 auto;">
            <h1 class="page-header">Code generator can't be load !</h1>
            <h2>Cache directory is not writable</h2>
            <p>Please execute</p>
            <pre>mkdir <?php echo $cacheDir; ?></pre>
        </div>
        </body>
    </html>
<?php
} else {

    if (!(@include_once __DIR__ . '/../vendor/autoload.php') && !(@include_once __DIR__ . '/../../../autoload.php')) {
        throw new RuntimeException('Error: vendor/autoload.php could not be found. Did you run php composer.phar install?');
    }

    if (is_file(__DIR__ . '/../vendor/autoload.php')) {
        chdir(__DIR__ . '/../'); // standalone
    } else {
        chdir(__DIR__ . '/../../../../'); // install with composer
    }

    $app = new Silex\Application();

    require __DIR__.'/../silex/resources/config/prod.php';
    require __DIR__.'/../silex/src/app.php';
    require __DIR__.'/../silex/src/controllers.php';

    $app->run();
}
