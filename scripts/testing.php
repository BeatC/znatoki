<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');

session_start();

class QuestionNode {
    public $question_text;
    public $correct;
}

$current_question = (int)$_REQUEST['number'];
$answer = $_REQUEST['answer'];

// Запрос вопроса с номером 0 сбивает счетчик правильных ответов!
if (!isset($_SESSION['correct_answers']) || $current_question == 0) {
    $_SESSION['correct_answers'] = 0;
}

$questions[0] = new QuestionNode();
$questions[0]->question_text = 'В каком геометрическом теле может закипеть вода?';
$questions[0]->correct = 'в кубе';

$questions[1] = new QuestionNode();
$questions[1]->question_text = 'Что может путешествовать по свету, оставаясь в одном и том же углу?';
$questions[1]->correct = 'почтовая марка';

$questions[2] = new QuestionNode();
$questions[2]->question_text = 'Мужчина вел большой грузовик. Огни на машине не были зажжены. Луны тоже не было. Женщина стала переходить дорогу перед машиной. Как удалось водителю разглядеть ее?';
$questions[2]->correct = 'был яркий солнечный день';

$questions[3] = new QuestionNode();
$questions[3]->question_text = 'На какой вопрос нельзя ответить «да»?';
$questions[3]->correct = 'вы спите?';

$questions[4] = new QuestionNode();
$questions[4]->question_text = 'На какой вопрос нельзя ответить «нет»?';

$questions[4]->correct = "вы живы?";

if(trim(mb_strtolower($answer, 'UTF-8')) == trim(mb_strtolower($questions[$current_question-1]->correct, 'UTF-8'))){
    $_SESSION['correct_answers'] = $_SESSION['correct_answers'] + 1;
}

$number_of_questions = count($questions);

if ($current_question == $number_of_questions){
    if ($_SESSION['correct_answers'] == $number_of_questions) {

        $secret = strtoupper(substr(md5(time() . microtime()), 2, 5));
        echo json_encode(array('text' => "http://goo.gl/forms/4m4X3EFXFn", 'secret' => $secret));
        $_SESSION['correct_answers'] = 0;
        file_put_contents('98895385948578953.txt', date(DATE_RFC822) . " -> " . $secret . "\n", FILE_APPEND | LOCK_EX);

    } else {
        echo json_encode(array('text' => "http://goo.gl/forms/4m4X3EFXFn"));
        $_SESSION['correct_answers'] = 0;
    }

} else {
    $question = array('questionNumber' => $current_question, 'totalQuestions' => $number_of_questions, 'question' => $questions[$current_question]->question_text, "correct" => $_SESSION['correct_answers']);
    echo json_encode($question);
}