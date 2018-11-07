<?php

$container['schoolclasses_model'] = function ($c) {
    $id = $c['loggedUser']['user']->id;
    $type = $c['loggedUser']['user']->type;
    $SchoolClasses = new UNI\Framework\SchoolClasses\Models\SchoolClasses($c);
    $SchoolClasses->user_id = $id;
    $SchoolClasses->type = $type;
    return $SchoolClasses;
};

$container['lessons_model'] = function ($c) {
    $id = $c['loggedUser']['user']->id;
    $type = $c['loggedUser']['user']->type;
    $lessons = new UNI\Framework\SchoolClasses\Models\Lessons($c);
    $lessons->user_id = $id;
    $lessons->type = $type;
    return $lessons;
};