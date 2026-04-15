<?php

class ProjectController extends Controller {

    public function index() {
        $projects = [
            ['id' => 1, 'title' => 'Rénovation cuisine', 'status' => 'En cours'],
            ['id' => 2, 'title' => 'Réfection salle de bain', 'status' => 'En attente'],
            ['id' => 3, 'title' => 'Travaux électricité', 'status' => 'Terminé']
        ];

        $this->view('projects/index', ['projects' => $projects]);
    }

    public function show($id): void
    {
        echo "<h1>Détails du projet</h1>";
        echo "<p>ID : " . $id . "</p>";
    }
}