<?php

declare(strict_types=1);

namespace FindTheBug;

/**
 * Minimal CSRF token helper. The token lives in the session and is
 * checked with a timing-safe comparison before any state-changing
 * POST request is honoured.
 */
class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function validate(mixed $submittedToken): bool
    {
        return is_string($submittedToken)
            && !empty($_SESSION['csrf_token'])
            && is_string($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $submittedToken);
    }
}