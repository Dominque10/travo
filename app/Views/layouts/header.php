<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travo</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
    <header class="bg-blue-900 text-white shadow-md">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <a href="<?php echo BASE_URL; ?>/" class="text-2xl font-bold tracking-tight">Travo</a>

            <nav class="flex items-center gap-6 text-sm font-medium">
                <a href="<?php echo BASE_URL; ?>/" class="transition hover:text-blue-200">Accueil</a>
                <a href="<?php echo BASE_URL; ?>/projects" class="transition hover:text-blue-200">Chantiers</a>
                <a href="<?php echo BASE_URL; ?>/about" class="transition hover:text-blue-200">À propos</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-10">