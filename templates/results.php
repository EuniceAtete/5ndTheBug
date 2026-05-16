<?php
$percentage = $total > 0 ? $score / $total : 0;

if ($percentage >= 0.8) {
    $message = "Amazing! You're a PHP pro! 🔥";
} elseif ($percentage >= 0.5) {
    $message = 'Good job! Keep practicing! 💪';
} else {
    $message = "Keep learning! You'll get there! 📚";
}
?>
<h1 class="text-5xl font-bold text-center mb-8 text-dark-teal">Game Over!</h1>

<div class="text-center mb-8">
    <div class="text-6xl font-bold text-med-teal mb-4">
        <?php echo (int) $score; ?>/<?php echo (int) $total; ?>
    </div>
    <p class="text-2xl text-gray-600"><?php echo $message; ?></p>
</div>

<div class="text-center">
    <a href="?action=reset" class="bg-med-teal hover:bg-dark-teal text-white font-bold py-4 px-12 rounded-full text-xl transition inline-block">
        Play Again
    </a>
</div>