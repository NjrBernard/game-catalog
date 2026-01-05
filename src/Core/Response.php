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
}