<?php
session_start();
//1. On charge la classe Router.

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Notification.php';
require_once __DIR__ . '/../app/Core/Validator.php';
require_once __DIR__ . '/../app/Core/Auth.php';

//Models
require_once __DIR__ . '/../app/Models/Project.php';
require_once __DIR__ . '/../app/Models/ProjectUpdate.php';

//Controllers
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/ProjectController.php';
require_once __DIR__ . '/../app/Controllers/UpdateController.php';

//2. On crée un objet routeur.
$router = new Router();
$homeController = new HomeController();
$projectController = new ProjectController();
$updateController = new UpdateController();


//3. On charge le fichier de routes.
require_once __DIR__ . '/../config/routes.php';

//4. On demande au routeur d’analyser la requête actuelle et dexécuter la bonne action.
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);