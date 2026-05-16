<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <span class="text-gray-600 font-semibold">Question <?php echo $index + 1; ?>/<?php echo $total; ?></span>
        <span id="timer" data-seconds="<?php echo (int) $seconds; ?>" class="text-2xl font-bold text-med-teal"><?php echo (int) $seconds; ?></span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
        <div class="bg-med-teal h-2 rounded-full transition-all duration-300" style="width: <?php echo ($index / $total) * 100; ?>%"></div>
    </div>
</div>

<h2 class="text-2xl font-bold text-gray-800 mb-6"><?php echo htmlspecialchars($question['question']); ?></h2>

<form id="answerForm" method="POST" action="?action=answer">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
    <input type="hidden" name="question_index" value="<?php echo (int) $index; ?>">
    <div class="space-y-4">
        <?php foreach ($question['answers'] as $answerIndex => $answer): ?>
            <label class="block">
                <input type="radio" name="answer" value="<?php echo (int) $answerIndex; ?>" class="peer hidden">
                <div class="border-2 border-gray-300 rounded-lg p-4 cursor-pointer hover:border-med-teal hover:bg-light-teal/20 transition peer-checked:border-med-teal peer-checked:bg-light-teal/30 peer-checked:font-semibold">
                    <?php echo htmlspecialchars($answer); ?>
                </div>
            </label>
        <?php endforeach; ?>
    </div>
</form>