<?php

class ProjectController extends Controller {

    public function index() {
        $projects = [
            ['title' => 'Rénovation cuisine', 'status' => 'En cours'],
            ['title' => 'Réfection salle de bain', 'status' => 'En attente'],
            ['title' => 'Travaux électricité', 'status' => 'Terminé']
        ];

        $this->view('projects/index', ['projects' => $projects]);
    }
}