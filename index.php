<?php

declare(strict_types=1);

session_start();

spl_autoload_register(static function (string $class): void {
    $prefix = 'FindTheBug\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = __DIR__ . '/../src/' . str_replace('\\', '/', $relative) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

use FindTheBug\Csrf;
use FindTheBug\Game;
use FindTheBug\QuestionRepository;

$repository = new QuestionRepository(__DIR__ . '/../data/questions.json');
$game = new Game($repository);

$action = $_GET['action'] ?? null;

try {
    switch ($action) {
        case 'start':
            $game->start();
            header('Location: index.php');
            exit;

        case 'answer':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Csrf::validate($_POST['csrf_token'] ?? null)) {
                http_response_code(400);
                exit('Invalid or expired request. Please go back and try again.');
            }

            $expectedIndex = isset($_POST['question_index']) ? (int) $_POST['question_index'] : -1;
            $answerIndex = isset($_POST['answer']) ? (int) $_POST['answer'] : -1;

            $game->submitAnswer($expectedIndex, $answerIndex);
            header('Location: index.php');
            exit;

        case 'reset':
            $game->reset();
            header('Location: index.php');
            exit;
    }
} catch (\Throwable $e) {
    http_response_code(500);
    exit('Something went wrong loading the game. Please try again shortly.');
}

/**
 * Tiny template renderer: keeps variables scoped to the included
 * template instead of leaking the whole controller's variable soup
 * into every view.
 */
$render = static function (string $template, array $data = []): string {
    extract($data, EXTR_SKIP);
    ob_start();
    require __DIR__ . '/../templates/' . $template . '.php';
    return (string) ob_get_clean();
};

if (!$game->hasStarted()) {
    $content = $render('start');
} elseif (!$game->isFinished()) {
    $content = $render('question', [
        'question' => $game->currentQuestion(),
        'index' => $game->currentIndex(),
        'total' => $game->totalQuestions(),
        'seconds' => $game->timePerQuestion(),
        'csrfToken' => Csrf::token(),
    ]);
} else {
    $content = $render('result', [
        'score' => $game->score(),
        'total' => $game->totalQuestions(),
    ]);
}

echo $render('layout', ['content' => $content]);