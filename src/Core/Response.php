<?php

namespace Core;

final class Response {
    public function render(string $view, array $data = [], int $status = 200): void{
        extract($data);
        // Header
        require __DIR__ . '/../../views/partials/header.php';
        // Page 
        require __DIR__ . '/../../views/pages/' . $view . '.php';
        // Footer
        require __DIR__ . '/../../views/partials/footer.php';
    }

    public function redirect(string $to, int $status = 302): void {
        header('Location: ' . $to, true, $status);
        exit;
    }

    public function json(mixed $data, int $status = 200): void {
        // 1. Définir le code HTTP de la réponse 
        http_response_code($status);
        // 2. Spécifier que ce sera au format JSON
        header('Content-Type: application/json; charset=utf-8');
        // 3. Convertir des données en JSON
        echo json_encode($data);
}
}