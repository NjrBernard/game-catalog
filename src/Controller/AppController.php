<?php

namespace Controller;
use Core\Response;
use Repository\GamesRepository;

require_once __DIR__ . '/../Helper/debug.php';

final class AppController
{
    public function __construct(private readonly Response $response, 
    private GamesRepository $gamesRepository,)
    {
}
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

    

    private function home(): void {
        $featuredGames = $this->gamesRepository->findTop(3);
        $this->response->render('home', [
            'featuredGames' => $featuredGames,
            'total' => $this->gamesRepository->countAll(),
        ], 200);
    }


    private function games(): void {
        //1. Récupérer tous les jeux
        $games = $this->gamesRepository->findAllSortedByRating();

        //2. Afficher la vue
        $this->response->render('games', [
            'games' => $games,
        ], 200);
    }

    private function gameById(int $id): void {
        $game = $this->gamesRepository->findById($id);
        $success = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);
        $this->response->render('detail', [
            'id' => $id,
            'game' => $game,
            'success' => $success,
        ], 200);
    }

    private function randomGame(): void {
        $lastId = $_SESSION['last_random_id'] ?? 0;
        $game = null;
        for ($i = 0; $i<5; $i++) {
            $candidate = $this->gamesRepository->findRandomGame();
            if ($candidate && $candidate['id'] !== $lastId) {
                $game = $candidate;
            }

        }
        $id = $game['id'];
        $_SESSION['last_random_id'] = $id;
        $this->response->redirect('/games/' . $id);
        exit;
    }

    public function createNewGame(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleAddGame();
            exit;
        }
        $this->response->render('add-new-game', [], 200);
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
            $this->response->render('add-new-game', [
                'errors' => $errors,
                'old' => $old,
            ], 422);
            return;
        }

        $newGameId = $this->gamesRepository->createGame($old);
        $_SESSION['flash_message'] = 'Le jeu a été ajouté avec succès.';
        $this->response->redirect('/games/' . $newGameId);
        exit;
    }

    private function notFound(): void {

        $this->response->render('not-found', [], 404);
    }
}