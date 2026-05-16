<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindTheBug - PHP Quiz Game</title>
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
        <?php echo $content; ?>
    </div>

    <script>
        (function () {
            const timerElement = document.getElementById('timer');
            if (!timerElement) {
                return;
            }

            const form = document.getElementById('answerForm');
            const totalSeconds = parseInt(timerElement.dataset.seconds, 10) || 5;
            let timeLeft = totalSeconds;
            let answered = false;
            let timerInterval;

            function tick() {
                timeLeft--;
                timerElement.textContent = timeLeft;

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
            }

            timerInterval = setInterval(tick, 1000);

            form.querySelectorAll('input[name="answer"]').forEach((input) => {
                input.addEventListener('change', () => {
                    answered = true;
                    clearInterval(timerInterval);
                    form.submit();
                });
            });
        })();
    </script>
</body>
</html>