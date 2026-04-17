CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    status VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    progress INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO projects (title, status, description, progress) VALUES
('Rénovation cuisine', 'En cours', 'Remplacement du carrelage, reprise de la peinture et installation d’un nouvel îlot central.', 65),
('Réfection salle de bain', 'En attente', 'Travaux suspendus en attente de validation du choix de faïence par le propriétaire.', 35),
('Travaux électricité', 'Terminé', 'Mise aux normes du tableau électrique et remplacement de plusieurs prises murales.', 100),
('Aménagement des combles', 'En cours', 'Isolation, pose de cloisons et création d’un espace bureau sous toiture.', 50),
('Rénovation façade', 'En attente', 'Nettoyage, reprise des fissures et application d’un nouvel enduit extérieur.', 20),
('Pose de parquet salon', 'Terminé', 'Dépose de l’ancien revêtement et installation d’un parquet stratifié dans le séjour.', 100),
('Création terrasse extérieure', 'En cours', 'Préparation du sol, coulage de dalle et pose de carrelage extérieur.', 70),
('Remplacement des fenêtres', 'En attente', 'Commande des nouvelles menuiseries et planification de l’intervention.', 15),
('Réfection toiture', 'En cours', 'Remplacement de tuiles abîmées et amélioration de l’étanchéité générale.', 55),
('Peinture intérieure complète', 'Terminé', 'Préparation des murs, sous-couche et peinture des pièces principales.', 100);

CREATE TABLE project_updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_project_updates_project
        FOREIGN KEY (project_id) REFERENCES projects(id)
        ON DELETE CASCADE
);

INSERT INTO project_updates (project_id, title, content) VALUES
(2, 'Début du chantier', 'Installation de la zone de travail et protection des surfaces.'),
(2, 'Livraison des matériaux', 'Les premiers matériaux ont été réceptionnés ce matin.'),
(2, 'Travaux suspendus', 'Le chantier est en attente de validation du choix de faïence.'),
(3, 'Fin de l’intervention', 'Les travaux électriques sont terminés et validés.');

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE projects ADD COLUMN user_id INT NULL AFTER id;

INSERT INTO users (name, email, password) VALUES
('Demo User', 'demo@travo.test', '$2y$12$fK6FkVg9h36hkAZ1q33wg.t4HjH0ZyNR33Qwtdv.GcXABAqARIWDW'); -- Password: "password123"

UPDATE projects SET user_id = 1 WHERE user_id IS NULL;

ALTER TABLE projects MODIFY user_id INT NOT NULL;

ALTER TABLE projects
ADD CONSTRAINT fk_projects_user
FOREIGN KEY (user_id) REFERENCES users(id)
ON DELETE CASCADE;

CREATE TABLE media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entity_type VARCHAR(100) NOT NULL,
    entity_id INT NOT NULL,
    category VARCHAR(50) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(150) NOT NULL,
    extension VARCHAR(20) NOT NULL,
    file_size INT NOT NULL,
    uploaded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE decisions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'draft',
    response_comment TEXT NULL,
    validated_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_decisions_project
        FOREIGN KEY (project_id) REFERENCES projects(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_decisions_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

CREATE INDEX idx_decisions_project_status ON decisions(project_id, status);

CREATE TABLE decision_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    decision_id INT NOT NULL,
    user_id INT NOT NULL,
    from_status VARCHAR(20) NOT NULL,
    to_status VARCHAR(20) NOT NULL,
    message VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_decision_logs_decision
        FOREIGN KEY (decision_id) REFERENCES decisions(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_decision_logs_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

CREATE INDEX idx_decision_logs_decision_created ON decision_logs(decision_id, created_at);

INSERT INTO decisions (project_id, user_id, title, description, status, response_comment, validated_at) VALUES
(1, 1, 'Choix du carrelage cuisine', 'Valider le modele de carrelage 60x60 pour la cuisine.', 'pending', NULL, NULL),
(1, 1, 'Validation plan ilot central', 'Validation du plan technique final de l ilot central.', 'approved', 'Plan valide apres ajustement des prises.', '2026-04-10 14:20:00'),
(2, 1, 'Selection faience salle de bain', 'Comparer trois references de faience murale avant commande.', 'rejected', 'Teinte trop foncee, merci de proposer une alternative claire.', NULL),
(3, 1, 'Ajout ligne budget securite', 'Ajouter un budget complementaire pour remplacement disjoncteurs.', 'draft', NULL, NULL),
(4, 1, 'Annulation variante cloison', 'Abandonner la variante de cloison amovible pour cause de cout.', 'cancelled', 'Variante annulee suite arbitrage budget.', NULL);

INSERT INTO decision_logs (decision_id, user_id, from_status, to_status, message) VALUES
(1, 1, 'draft', 'draft', 'Decision creee en brouillon.'),
(1, 1, 'draft', 'pending', 'Decision envoyee en validation.'),

(2, 1, 'draft', 'draft', 'Decision creee en brouillon.'),
(2, 1, 'draft', 'pending', 'Decision envoyee en validation.'),
(2, 1, 'pending', 'approved', 'Decision approuvee.'),

(3, 1, 'draft', 'draft', 'Decision creee en brouillon.'),
(3, 1, 'draft', 'pending', 'Decision envoyee en validation.'),
(3, 1, 'pending', 'rejected', 'Decision rejetee.'),

(4, 1, 'draft', 'draft', 'Decision creee en brouillon.'),

(5, 1, 'draft', 'draft', 'Decision creee en brouillon.'),
(5, 1, 'draft', 'cancelled', 'Decision annulee.');

