<?php

class UpdateController extends Controller
{
    private Project $projectModel;
    private ProjectUpdate $updateModel;

    public function __construct()
    {
        $this->projectModel = new Project();
        $this->updateModel = new ProjectUpdate();
    }

    public function index($projectId): void
    {
        $project = $this->projectModel->getById((int) $projectId);

        if (!$project) {
            http_response_code(404);
            echo "<h1>Projet introuvable</h1>";
            return;
        }

        $updates = $this->updateModel->getByProjectId((int) $projectId);

        $this->view('updates/index', [
            'project' => $project,
            'updates' => $updates
        ]);
    }

    public function create($projectId): void
    {
        $project = $this->projectModel->getById((int) $projectId);

        if (!$project) {
            http_response_code(404);
            echo "<h1>Projet introuvable</h1>";
            return;
        }

        $this->view('updates/create', [
            'project' => $project
        ]);
    }

    public function store($projectId): void
    {
        $project = $this->projectModel->getById((int) $projectId);

        if (!$project) {
            http_response_code(404);
            echo "<h1>Projet introuvable</h1>";
            return;
        }

        $validator = Validator::make($_POST, [
            'title' => 'required|min:3',
            'content' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            Notification::setFlash('error', $validator->firstError());
            header('Location: ' . BASE_URL . '/projects/' . (int) $projectId . '/updates/create');
            exit;
        }

        $data = $validator->validated();
        $data['project_id'] = (int) $projectId;

        $this->updateModel->create($data);

        Notification::setFlash('success', 'L’update a bien été ajoutée.');
        header('Location: ' . BASE_URL . '/projects/' . (int) $projectId . '/updates');
        exit;
    }
}