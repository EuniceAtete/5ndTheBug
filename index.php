<?php
session_start();
include 'qns.php';

if(isset($_GET['action'])){
    if($_GET['action'] == 'start'){
        shuffle($questions);
        $_SESSION['game_qns'] = array_slice($questions,0,10);
        $_SESSION['current_qn'] = 0;
        $_SESSION['score'] = 0;

        header('Location: index.php');
        exit;
    }

    if($_GET['action'] == 'answer'){  // Changed from 'answers' to 'answer'
        $user_answer = isset($_POST['answer']) ? (int)$_POST['answer'] : -1;
        $current_Qn = $_SESSION['current_qn'];
        $correct_answer = $_SESSION['game_qns'][$current_Qn]['correct'];  // Changed to 'game_qns'

        if($user_answer == $correct_answer){
            $_SESSION['score']++;
        }
        $_SESSION['current_qn']++;

        header("Location: index.php");
        exit;
    }
    
    if($_GET['action'] == 'reset'){  // Moved outside of answer block
        session_destroy();
        header('Location: index.php');
        exit;
    }
}

$gameStarted = isset($_SESSION['game_qns']);  // Changed variable name to match HTML
$currentQuestion = $gameStarted ? $_SESSION['current_qn'] : 0;  // Changed to match HTML
$gameFinished = $gameStarted && $currentQuestion >= 10;  // Changed to match HTML
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>5ndTheBug - PHP Quiz Game</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark-teal': '#09637E',
                        'med-teal': '#088395',
                        'light-teal': '#7AB2B2',
                        'off-white': '#EBF4F6'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-dark-teal to-med-teal min-h-screen flex items-center justify-center p-4">
    
    <div class="bg-off-white rounded-2xl shadow-2xl p-12 max-w-2xl w-full">
        
        <?php if (!$gameStarted): ?>
            <!-- START SCREEN -->
            <h1 class="text-5xl font-bold text-center mb-8 text-dark-teal">5ndTheBug</h1>
            <p class="text-center text-gray-600 mb-8 text-lg">Test your PHP knowledge! 10 questions, 5 seconds each.</p>
            <div class="text-center">
                <a href="?action=start" class="bg-med-teal hover:bg-dark-teal text-white font-bold py-4 px-12 rounded-full text-xl transition inline-block">
                    Start Game
                </a>
            </div>
        
        <?php elseif (!$gameFinished): ?>
            <!-- QUESTION SCREEN -->
            <?php 
            $question = $_SESSION['game_qns'][$currentQuestion];  // Changed to 'game_qns'
            ?>
            
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-600 font-semibold">Question <?php echo $currentQuestion + 1; ?>/10</span>
                    <span id="timer" class="text-2xl font-bold text-med-teal">5</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
                    <div class="bg-med-teal h-2 rounded-full transition-all duration-300" style="width: <?php echo ($currentQuestion / 10) * 100; ?>%"></div>
                </div>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-6"><?php echo htmlspecialchars($question['question']); ?></h2>
            
            <form id="answerForm" method="POST" action="?action=answer">
                <div class="space-y-4">
                    <?php foreach ($question['answers'] as $index => $answer): ?>
                        <label class="block">
                            <input type="radio" name="answer" value="<?php echo $index; ?>" class="peer hidden">
                            <div class="border-2 border-gray-300 rounded-lg p-4 cursor-pointer hover:border-med-teal hover:bg-light-teal/20 transition peer-checked:border-med-teal peer-checked:bg-light-teal/30 peer-checked:font-semibold">
                                <?php echo htmlspecialchars($answer); ?>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </form>
        
        <?php else: ?>
            <!-- SCORE SCREEN -->
            <h1 class="text-5xl font-bold text-center mb-8 text-dark-teal">Game Over!</h1>
            
            <div class="text-center mb-8">
                <div class="text-6xl font-bold text-med-teal mb-4">
                    <?php echo $_SESSION['score']; ?>/10
                </div>
                <p class="text-2xl text-gray-600">
                    <?php 
                    $score = $_SESSION['score'];
                    if ($score >= 8) echo "Amazing! You're a PHP pro! 🔥";
                    elseif ($score >= 5) echo "Good job! Keep practicing! 💪";
                    else echo "Keep learning! You'll get there! 📚";
                    ?>
                </p>
            </div>
            
            <div class="text-center">
                <a href="?action=reset" class="bg-med-teal hover:bg-dark-teal text-white font-bold py-4 px-12 rounded-full text-xl transition inline-block">
                    Play Again
                </a>
            </div>
        
        <?php endif; ?>
        
    </div>
<script>
let timeLeft = 5;
let timerInterval;
let answered = false;

function startTimer() {
    const timerElement = document.getElementById('timer');
    const form = document.getElementById('answerForm');
    
    timerInterval = setInterval(() => {
        timeLeft--;
        timerElement.textContent = timeLeft;
        
        // Change color when time is running out
        if (timeLeft <= 2) {
            timerElement.classList.remove('text-med-teal');
            timerElement.classList.add('text-red-600');
        }
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            if (!answered) {
                form.submit();
            }
        }
    }, 1000);
}

// Start timer when page loads (only on question screen)
if (document.getElementById('timer')) {
    startTimer();
    
    // When user clicks an answer, submit immediately
    const answerInputs = document.querySelectorAll('input[name="answer"]');
    answerInputs.forEach(input => {
        input.addEventListener('change', () => {
            answered = true;
            clearInterval(timerInterval);
            document.getElementById('answerForm').submit();
        });
    });
}
</script>

</body>
</html>
</body>
</html>