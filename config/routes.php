<?php

//Routes pour la page d'accueil et autre pages statiques
$router->get('/', [$homeController, 'index']);
$router->get('/about', [$homeController, 'about']);

// Routes pour les projets
$router->get('/projects', [$projectController, 'index']);

$router->get('/projects/{id}', [$projectController, 'show']);

