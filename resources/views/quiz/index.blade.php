<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .timer {
            position: absolute;
            top: 10px;
            right: 25px;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center my-4">
            <h1 class="display-4">Quiz - {{ $category }}</h1>
        </div>
        <div class="text-left">
            <div class="timer">30</div>
        </div>
        <div id="quiz" class="quiz-container">
            <div id="question-container" class="mb-4">
            </div>
            <div class="text-center">
                <button id="next-question" class="btn btn-primary">Next Question</button>
            </div>
        </div>

        <div class="text-center mt-4">
            <button id="submit" class="btn btn-success d-none">Submit</button>
        </div>

        <div id="results" class="mt-5 d-none">
            <h2 class="display-4">Results</h2>
            <div id="results-container"></div>

            <div class="text-center mt-4">
                <button id="reset" class="btn btn-warning">Reset</button>
                <button id="back" class="btn btn-secondary">Back</button>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const questions = @json($formattedQuestions);
        let currentQuestionIndex = 0;
        let timerInterval;
        let timerElement = document.querySelector('.timer');
        let userAnswers = [];
        let correctAnswersCount = 0;

        function showQuestion(index) {
            const questionContainer = document.getElementById('question-container');
            questionContainer.innerHTML = '';

            const question = questions[index];
            const questionElement = document.createElement('h2');
            questionElement.textContent = question.question;
            questionContainer.appendChild(questionElement);

            const answersContainer = document.createElement('div');
            answersContainer.classList.add('answers', 'mt-2');
            question.answers.forEach((answer, i) => {
                const button = document.createElement('button');
                button.classList.add('btn', 'btn-outline-secondary', 'm-1');
                button.textContent = answer;
                button.addEventListener('click', () => saveAnswer(index, i, answer));
                answersContainer.appendChild(button);
            });
            questionContainer.appendChild(answersContainer);

            startTimer();
        }

        function startTimer() {
            let timeLeft = 30;
            timerInterval = setInterval(() => {
                timeLeft--;
                timerElement.textContent = timeLeft;
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    nextQuestion();
                }
            }, 1000);
        }

        function saveAnswer(questionIndex, answerIndex, selectedAnswer) {
            userAnswers[questionIndex] = {
                selected: selectedAnswer,
                isCorrect: selectedAnswer === questions[questionIndex].correctAnswer
            };
            if (userAnswers[questionIndex].isCorrect) {
                correctAnswersCount++;
            }
        }

        function nextQuestion() {
            clearInterval(timerInterval);
            currentQuestionIndex++;
            if (currentQuestionIndex < questions.length) {
                showQuestion(currentQuestionIndex);
            } else {
                document.getElementById('quiz').classList.add('d-none');
                document.getElementById('submit').classList.remove('d-none');
                timerElement.classList.add('d-none');
            }
        }

        function showResults() {
            clearInterval(timerInterval);
            timerElement.classList.add('d-none');

            const resultsContainer = document.getElementById('results-container');
            resultsContainer.innerHTML = '';

            questions.forEach((question, index) => {
                const resultElement = document.createElement('div');
                resultElement.classList.add('result');

                const questionElement = document.createElement('h4');
                questionElement.textContent = question.question;
                resultElement.appendChild(questionElement);

                const answerElement = document.createElement('p');
                const userAnswer = userAnswers[index]?.selected || "No answer";
                const isCorrect = userAnswers[index]?.isCorrect ? "Correct" : "Incorrect";
                answerElement.textContent = `Your answer: ${userAnswer} (${isCorrect})`;
                resultElement.appendChild(answerElement);

                resultsContainer.appendChild(resultElement);
            });

            const scoreElement = document.createElement('h3');
            scoreElement.textContent = `You got ${correctAnswersCount} out of ${questions.length} correct.`;
            resultsContainer.appendChild(scoreElement);
        }

        document.getElementById('next-question').addEventListener('click', nextQuestion);
        document.getElementById('submit').addEventListener('click', () => {
            showResults();
            document.getElementById('results').classList.remove('d-none');
        });

        document.getElementById('reset').addEventListener('click', () => {
            currentQuestionIndex = 0;
            correctAnswersCount = 0;
            userAnswers = [];
            document.getElementById('results').classList.add('d-none');
            document.getElementById('quiz').classList.remove('d-none');
            document.getElementById('submit').classList.add('d-none');
            timerElement.classList.remove('d-none');
            showQuestion(currentQuestionIndex);
        });

        document.getElementById('back').addEventListener('click', () => {
            window.location.href = '/dashboard';
        });

        showQuestion(currentQuestionIndex);
    });
    </script>
</body>
</html> 

