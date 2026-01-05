<?php

namespace Controller;
use Core\Response;
use Core\Session;
use Core\Request;
use Helper\Debug;
use Repository\GamesRepository;


require_once __DIR__ . '/../Helper/debug.php';

final class AppController
{
    public function __construct(private readonly Response $response, 
    private GamesRepository $gamesRepository,
    private Session $session,
    private Request $request,)
    {
}


    //Créer une fonction render - type string view, data (array)

    

    public function home(): void {
        $featuredGames = $this->gamesRepository->findTop(3);
        $this->response->render('home', [
            'featuredGames' => $featuredGames,
            'total' => $this->gamesRepository->countAll(),
        ], 200);
    }


    public function games(): void {
        //1. Récupérer tous les jeux
        $games = $this->gamesRepository->findAllSortedByRating();

        //2. Afficher la vue
        $this->response->render('games', [
            'games' => $games,
        ], 200);
    }

    public function gameById(int $id): void {
        $game = $this->gamesRepository->findById($id);
        $success = $this->session->pullFlash('success');
        $this->response->render('detail', [
            'id' => $id,
            'game' => $game,
            'success' => $success,
        ], 200);
    }

    public function randomGame(): void {
        $lastId = $this->session->get('last_random_id') ?? null;
        $game = null;
        for ($i = 0; $i<5; $i++) {
            $candidate = $this->gamesRepository->findRandomGame();
            if ($candidate && $candidate['id'] !== $lastId) {
                $game = $candidate;
            }

        }
        $id = $game['id'];
        $this->session->set('last_random_id', $id);
        $this->response->redirect('/games/' . $id);
        exit;
    }

    public function createNewGame(): void {
        if ($this->request->isPost()) {
            $this->handleAddGame();
            exit;
        }
        $this->response->render('add-new-game', [], 200);
    }


    public function handleAddGame  (): void {
        $title = trim($this->request->post('title') );
        $platform = trim($this->request->post('platform') ); 
        $genre = trim($this->request->post('genre') );
        $releaseYear = (int)(trim($this->request->post('releaseYear')));
        $rating = (int)(trim($this->request->post('rating')));
        $description = trim($this->request->post('description') );
        $notes = trim($this->request->post('notes') );

        $errors = [];

        if ($title === '')  $errors['title'] = 'Le titre est obligatoire.';
        if ($platform === '')  $errors['platform'] = 'La plateforme est obligatoire.';
        if ($genre === '')  $errors['genre'] = 'Le genre est obligatoire.';
        if ($releaseYear <= 1900 || $releaseYear > (int)date('Y'))  $errors['releaseYear'] = "L'année de sortie est obligatoire.";
        if ($rating < 0 || $rating > 10)  $errors['rating'] = 'La note doit être entre 0 et 10.';
        if ($description === '')  $errors['description'] = 'La description est obligatoire.';

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
        $this->session->flash('success', 'Le jeu a été ajouté avec succès.');
        $this->response->redirect('/games/' . $newGameId);
        exit;
    }

    public function notFound(): void {

        $this->response->render('not-found', [], 404);
    }
}