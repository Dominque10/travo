<?php

class ProjectController extends Controller {

    private Project $projectModel;

    public function __construct()
    {
        $this->projectModel = new Project();
    }

    public function index() {
        $projects = $this->projectModel->getAll();

        $this->view('projects/index', ['projects' => $projects]);
    }

    public function show($id): void
    {   
        $project = $this->projectModel->getById($id);

        if (!$project) {
            http_response_code(404);
            echo "<h1>Projet introuvable</h1>";
            return;
        }

        $this->view('projects/show', ['project' => $project]);
    }
}