<?php

require_once __DIR__ . '/../services/games.php';
require_once __DIR__ . '/../helpers/debug.php';

final class AppController
{
    public function handleRequest(string $path): void {
        if (preg_match('#^/games/(\d+)$#', $path, $m)) {
            $this->gameById((int) $m[1]);
            return;
        }

        switch ($path) {
            case '/':
                $this->home();
                break;
            case '/games':
                $this->games();
                break;
            case '/random-game':
                $this->randomGame();
                break;
            case '/games/add-new-game':
                $this->createNewGame();
                break;
            default:
                $this->notFound();
                break;
        }

    }

    //Créer une fonction render - type string view, data (array)

    private function render(string $view, array $data = [], int $status = 200): void{
        extract($data);
        // Header
        require __DIR__ . '/../../views/partials/header.php';
        // Page 
        require __DIR__ . '/../../views/pages/' . $view . '.php';
        // Footer
        require __DIR__ . '/../../views/partials/footer.php';
    }

    private function home(): void {
        $featuredGames = getLimitedGames(3);
        $this->render('home', [
            'featuredGames' => $featuredGames,
            'total' => countAllGames(),
        ], 200);
    }


    private function games(): void {
        //1. Récupérer tous les jeux
        $games = getAllGamesSortedByRating();

        //2. Afficher la vue
        $this->render('games', [
            'games' => $games,
        ], 200);
    }

    private function gameById(int $id): void {
        $game = getGameById($id);
        $success = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);
        $this->render('detail', [
            'id' => $id,
            'game' => $game,
            'success' => $success,
        ], 200);
    }

    private function randomGame(): void {
        $lastId = $_SESSION['last_random_id'] ?? 0;
        $game = null;
        for ($i = 0; $i<5; $i++) {
            $candidate = getRandomGame();
            if ($candidate && $candidate['id'] !== $lastId) {
                $game = $candidate;
            }

        }
        $id = $game['id'];
        $_SESSION['last_random_id'] = $id;
        header('Location: /games/' . $id, true, 302);
        200;
        exit;
    }

    public function createNewGame(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleAddGame();
            exit;
        }
        $this->render('add-new-game', [], 200);
    }


    private function handleAddGame  (): void {
        $title = trim($_POST['title'] );
        $platform = trim($_POST['platform'] ); 
        $genre = trim($_POST['genre'] );
        $releaseYear = (int)(trim($_POST['releaseYear']));
        $rating = (int)(trim($_POST['rating']));
        $description = trim($_POST['description'] );
        $notes = trim($_POST['notes'] );

        $errors = [];

        if ($title === '')  $errors['title'] = 'Le titre est obligatoire.';
        if ($platform === '')  $errors['platform'] = 'La plateforme est obligatoire.';
        if ($genre === '')  $errors['genre'] = 'Le genre est obligatoire.';
        if ($releaseYear <= 1900 || $releaseYear > (int)date('Y'))  $errors['releaseYear'] = "L'année de sortie est obligatoire.";
        if ($rating < 0 || $rating > 10)  $errors['rating'] = 'La note doit être entre 0 et 10.';
        if ($description === '')  $errors['description'] = 'La description est obligatoire.';
        if ($notes === '')  $errors['notes'] = 'Les notes sont obligatoires.';

        $old = [
            'title' => $title,
            'platform' => $platform,
            'genre' => $genre,
            'releaseYear' => $releaseYear,
            'rating' => $rating,
            'description' => $description,
            'notes' => $notes,
        ];

        if (!empty($errors)) {
            $this->render('add-new-game', [
                'errors' => $errors,
                'old' => $old,
            ], 422);
            return;
        }

        $newGameId = createNewGame($old);
        $_SESSION['flash_message'] = 'Le jeu a été ajouté avec succès.';
        header('Location: /games/' . $newGameId, true, 302);
        exit;
    }

    private function notFound(): void {

        $this->render('not-found', [], 404);
    }
}