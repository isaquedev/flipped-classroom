<?php

$container['questionnaires_model'] = function ($c) {
    $id = $c['loggedUser']['user']->id;
    $type = $c['loggedUser']['user']->type;
    $questionnaire = new UNI\Framework\Questionnaires\Models\Questionnaires($c);
    $questionnaire->user_id = $id;
    $questionnaire->type = $type;
    return $questionnaire;
};

$container['questions_model'] = function ($c) {
    $id = $c['loggedUser']['user']->id;
    $type = $c['loggedUser']['user']->type;
    $question = new UNI\Framework\Questionnaires\Models\Questions($c);
    $question->user_id = $id;
    $question->type = $type;
    return $question;
};