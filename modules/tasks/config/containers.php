<?php

$container['projects_model'] = function ($c) {
    $id = $c['loggedUser']['user']->id;
    $user_type = $c['loggedUser']['user']->user_type;
    $projects = new UNI\Framework\Tasks\Models\Projects($c);
    $projects->user_id = $id;
    $projects->user_type = $user_type;
    return $projects;
};

$container['sections_model'] = function ($c) {
    $id = $c['loggedUser']['user']->id;
    $user_type = $c['loggedUser']['user']->user_type;
    $sections = new UNI\Framework\Tasks\Models\Sections($c);
    $sections->user_id = $id;
    $sections->user_type = $user_type;
    return $sections;
};

$container['tasks_model'] = function ($c) {
    $id = $c['loggedUser']['user']->id;
    $tasks = new UNI\Framework\Tasks\Models\Tasks($c);
    $tasks->user_id = $id;

    return $tasks;
};

$container['sub_tasks_model'] = function ($c) {
    $id = $c['loggedUser']['user']->id;
    $subtasks = new UNI\Framework\Tasks\Models\SubTasks($c);
    $subtasks->user_id = $id;

    return $subtasks;
};