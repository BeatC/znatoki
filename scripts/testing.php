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
$questions[0]->question_text = 'Согласно шутке из Интернета, "в 1812 году Наполеон был еще не тот". Какую букву мы пропустили в предыдущем предложении?';
$questions[0]->correct = 'р';

$questions[1] = new QuestionNode();
$questions[1]->question_text = 'Острословы утверждают, что ОНА собирает крошки еды со стола и складывает их в щели клавиатуры на зиму. Назовите ЕЕ двумя словами.';
$questions[1]->correct = 'Компьютерная мышь';

$questions[2] = new QuestionNode();
$questions[2]->question_text = 'В одном из проектов Google предлагается по ряду поисковых фраз опознать персонажа, который мог бы искать такое, будь у него Интернет. Назовите персонажа, которому приписали поиск следующих фраз: "как ухаживать за цветком", "ростки баобабов", "как прочищать вулканы.';
$questions[2]->correct = 'Маленький Принц';

$questions[3] = new QuestionNode();
$questions[3]->question_text = 'В древности эту игру называли "игрой в слепого". Как сейчас называется эта игра? Дайте абсолютно точный ответ.';
$questions[3]->correct = 'Жмурки';

$questions[4] = new QuestionNode();
$questions[4]->question_text = <<<HERE
Прочитайте отрывок из стихотворения Екатерины Агафоновой, в котором мы пропустили одно слово:<br/>
"А потом запахло медом и мятой,<br/>
Я в траву влетел по самые уши<br/>
И решил, что в новой жизни (пропуск)<br/>
Буду тем же, кем и был, только лучше".<br/><br/>
Догадавшись, какое слово пропущено, ответьте: от чьего имени ведется повествование в этом стихотворении?<br/>
HERE;

$questions[4]->correct = "От имени кота";

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