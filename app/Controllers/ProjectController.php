<?php

class ProjectController extends Controller {

    private array $projects = [
        1 => [
            'id' => 1,
            'title' => 'Rénovation cuisine',
            'status' => 'En cours',
            'description' => 'Remplacement du carrelage, reprise de la peinture et installation d’un nouvel îlot central.',
            'progress' => 65
        ],
        2 => [
            'id' => 2,
            'title' => 'Réfection salle de bain',
            'status' => 'En attente',
            'description' => 'Travaux suspendus en attente de validation du choix de faïence par le propriétaire.',
            'progress' => 35
        ],
        3 => [
            'id' => 3,
            'title' => 'Travaux électricité',
            'status' => 'Terminé',
            'description' => 'Mise aux normes du tableau électrique et remplacement de plusieurs prises murales.',
            'progress' => 100
        ]
    ];


    public function index() {
        $this->view('projects/index', ['projects' => $this->projects]);
    }

    public function show($id): void
    {
        if (!isset($this->projects[$id])) {
            http_response_code(404);
            echo "<h1>Projet introuvable</h1>";
            return;
        }

        $project = $this->projects[$id];

        $this->view('projects/show', ['project' => $project]);
    }
}