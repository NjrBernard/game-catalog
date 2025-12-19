<?php

require_once __DIR__ . '/../services/games.php';
require_once __DIR__ . '/../helpers/debug.php';

final class AppController
{
    public function handleRequest(string $path): void
    {
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
            default:
                $this->notFound();
                break;
        }

    }

    //Créer une fonction render - type string view, data (array)

    private function render(string $view, array $data = []): void
    {
        extract($data);
        // Header
        require __DIR__ . '/../../views/partials/header.php';
        // Page 
        require __DIR__ . '/../../views/pages/' . $view . '.php';
        // Footer
        require __DIR__ . '/../../views/partials/footer.php';
    }

    private function home(): void
    {
        $featuredGames = getLimitedGames(3);
        http_response_code(200);
        $this->render('home', [
            'featuredGames' => $featuredGames,
            'total' => countAllGames(),
        ]);
    }


    private function games(): void
    {
        //1. Récupérer tous les jeux
        $games = getAllGamesSortedByRating();
        http_response_code(200);
        //2. Afficher la vue
        $this->render('games', [
            'games' => $games,
        ]);
    }

    private function gameById(int $id): void
    {
        $game = getGameById($id);
        $this->render('detail', [
            'id' => $id,
            'game' => $game,
        ]);
    }

    private function notFound(): void
    {
        http_response_code(404);
        $this->render('not-found');
    }
}