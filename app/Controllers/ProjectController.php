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

    public function create(): void
    {
        $this->view('projects/create');
    }

    function store(): void{
        $title = trim($_POST['title'] ?? '');
        $status = trim($_POST['status'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $progress = (int) ($_POST['progress'] ?? 0);

        if ($title === '' || $status === '' || $description === '') {
            echo "Tous les champs sont obligatoires.";
            return;
        }

        if ($progress < 0 || $progress > 100) {
            echo "La progression doit être comprise entre 0 et 100.";
            return;
        }

        if(!$this->projectModel->create([
            'title' => $title,
            'status' => $status,
            'description' => $description,
            'progress' => $progress
        ])) {
            echo "Erreur lors de la création du projet.";
            return;
        }
        header('Location: '. BASE_URL . '/projects');
        exit;
    }

    public function edit($id): void
    {
        $project = $this->projectModel->getById((int) $id);

        if (!$project) {
            http_response_code(404);
            echo "<h1>Projet introuvable</h1>";
            return;
        }

        $this->view('projects/edit', ['project' => $project]);
    }

    public function update($id): void
    {
        $project = $this->projectModel->getById((int) $id);

        if (!$project) {
            http_response_code(404);
            echo "<h1>Projet introuvable</h1>";
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $status = trim($_POST['status'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $progress = (int) ($_POST['progress'] ?? 0);

        if ($title === '' || $status === '' || $description === '') {
            echo "Tous les champs sont obligatoires.";
            return;
        }

        if ($progress < 0 || $progress > 100) {
            echo "La progression doit être comprise entre 0 et 100.";
            return;
        }

        $this->projectModel->update((int) $id, [
            'title' => $title,
            'status' => $status,
            'description' => $description,
            'progress' => $progress
        ]);

        header('Location: ' . BASE_URL . '/projects/' . (int) $id);
        exit;
    }

    public function destroy($id): void
    {
        $project = $this->projectModel->getById((int) $id);

        if (!$project) {
            http_response_code(404);
            echo "<h1>Projet introuvable</h1>";
            return;
        }

        $this->projectModel->delete((int) $id);

        header('Location: ' . BASE_URL . '/projects');
        exit;
    }
}