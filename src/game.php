<?php

declare(strict_types=1);

namespace FindTheBug;

class Game
{
    private const TOTAL_QUESTIONS = 10;
    private const TIME_PER_QUESTION_SECONDS = 5;

    public function __construct(private readonly QuestionRepository $repository)
    {
    }

    public function start(): void
    {
        // Regenerating the session id on a fresh game avoids session
        // fixation and gives every playthrough a clean slate.
        session_regenerate_id(true);

        $pool = $this->repository->all();
        shuffle($pool);

        $_SESSION['game'] = [
            'questions' => array_slice($pool, 0, self::TOTAL_QUESTIONS),
            'current' => 0,
            'score' => 0,
        ];
    }

    public function hasStarted(): bool
    {
        return isset($_SESSION['game']);
    }

    public function isFinished(): bool
    {
        return $this->hasStarted() && $_SESSION['game']['current'] >= self::TOTAL_QUESTIONS;
    }

    public function isInProgress(): bool
    {
        return $this->hasStarted() && !$this->isFinished();
    }

    public function currentQuestion(): ?array
    {
        if (!$this->isInProgress()) {
            return null;
        }

        return $_SESSION['game']['questions'][$_SESSION['game']['current']];
    }

    public function currentIndex(): int
    {
        return $_SESSION['game']['current'] ?? 0;
    }

    public function totalQuestions(): int
    {
        return self::TOTAL_QUESTIONS;
    }

    public function timePerQuestion(): int
    {
        return self::TIME_PER_QUESTION_SECONDS;
    }

    public function score(): int
    {
        return $_SESSION['game']['score'] ?? 0;
    }

    /**
     * @param int $expectedIndex The question index the client believed it was answering.
     * @param int $answerIndex The chosen answer's index.
     */
    public function submitAnswer(int $expectedIndex, int $answerIndex): void
    {
        if (!$this->isInProgress()) {
            return;
        }

        // If the submitted question index doesn't match the server's
        // current question, silently ignore it rather than scoring a
        // stale or replayed request.
        if ($expectedIndex !== $_SESSION['game']['current']) {
            return;
        }

        $question = $_SESSION['game']['questions'][$_SESSION['game']['current']];

        if ($answerIndex === $question['correct']) {
            $_SESSION['game']['score']++;
        }

        $_SESSION['game']['current']++;
    }

    public function reset(): void
    {
        unset($_SESSION['game']);
    }
}