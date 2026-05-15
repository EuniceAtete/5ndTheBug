<?php

declare(strict_types=1);

namespace FindTheBug;

use RuntimeException;

class QuestionRepository
{
    public function __construct(private readonly string $jsonPath)
    {
    }

    /**
     * @return array<int, array{question: string, answers: array<int, string>, correct: int}>
     */
    public function all(): array
    {
        if (!is_readable($this->jsonPath)) {
            throw new RuntimeException("Question file not found: {$this->jsonPath}");
        }

        $raw = file_get_contents($this->jsonPath);
        $data = json_decode($raw !== false ? $raw : '', true);

        if (!is_array($data) || $data === []) {
            throw new RuntimeException('Question data is empty or malformed.');
        }

        foreach ($data as $question) {
            $this->validate($question);
        }

        return $data;
    }

    private function validate(mixed $question): void
    {
        if (
            !is_array($question)
            || !isset($question['question'], $question['answers'], $question['correct'])
            || !is_string($question['question'])
            || !is_array($question['answers'])
            || !is_int($question['correct'])
        ) {
            throw new RuntimeException('Malformed question entry in question bank.');
        }

        if (!array_key_exists($question['correct'], $question['answers'])) {
            throw new RuntimeException('Question has a correct-answer index that does not exist.');
        }
    }
}