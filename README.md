# 5ndTheBug 🐛

A small PHP quiz game that tests your PHP knowledge under time pressure — 10 random questions, 5 seconds each, straight from a pool of common PHP concepts and classic beginner bugs.

Built originally while learning PHP, later refactored into a small MVC-style structure with proper separation of concerns, CSRF protection, and JSON-driven content.

## Features

- 🎯 10 random questions pulled from a pool of 20
- ⏱️ 5-second countdown timer per question (auto-submits when time runs out)
- 📊 Server-side score tracking (the client never controls the score)
- 🔒 CSRF-protected answer submissions
- 🗂️ Question bank stored as JSON, decoupled from application logic

## Tech Stack

- PHP 8.1+ (typed properties, `readonly`, `str_starts_with`, match-style routing)
- Tailwind CSS (via CDN) for styling
- Vanilla JavaScript for the countdown timer
- No frameworks, no database — sessions hold all game state