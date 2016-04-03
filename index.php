<?php

/**
 * index.php
 * HTML desplegado al usuario.
 */

// Lista de archivos de JavaScript a cargar en la página.
$scripts = [
    'lib/angular.min.js',
    'lib/angular-messages.min.js',
    'lib/angular-route.min.js',
    'lib/angular-animate.min.js',
    'app.js',
    'services/user.js',
    'services/client-storage.js',
    'controllers/home.js',
    'controllers/logout.js',
    'controllers/login.js',
    'controllers/register.js',
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Proyecto Uno: Práctica PHP 01</title>
    <link rel="stylesheet" href="front-end/css/bootstrap.min.css">
</head>
<body>

<div class="col-md-4 col-md-offset-4">
    <div ng-app="practicaPHP01" ng-view></div>
</div>

<?php
// Carga de los archivos de JavaScript
foreach ($scripts as $script) {
    echo "<script src='front-end/js/$script'></script> \n";
}
?>
</body>
</html>
