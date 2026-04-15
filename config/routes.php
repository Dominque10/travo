<?php

//Routes pour la page d'accueil et autre pages statiques
$router->get('/', [$homeController, 'index']);
$router->get('/about', [$homeController, 'about']);

// Routes pour les projets
$router->get('/projects', [$projectController, 'index']);
$router->get('/projects/create', [$projectController, 'create']); //Afficher le formulaire de création
$router->post('/projects/store', [$projectController, 'store']); //Ajouter le projet en BDD
$router->get('/projects/{id}/edit', [$projectController, 'edit']);
$router->post('/projects/{id}/update', [$projectController, 'update']);
$router->post('/projects/{id}/delete', [$projectController, 'destroy']);
$router->get('/projects/{id}', [$projectController, 'show']);

// Routes pour les updates
$router->get('/projects/{id}/updates', [$updateController, 'index']);
$router->get('/projects/{id}/updates/create', [$updateController, 'create']);
$router->post('/projects/{id}/updates/store', [$updateController, 'store']);