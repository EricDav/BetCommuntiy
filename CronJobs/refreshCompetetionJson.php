<?php
    // Get the contents
    $JsonCompetitionContent = file_get_contents('https://livescore-api.com/api-client/competitions/list.json?key=I6AUQWWnzLs6X5Jp&secret=EsdilZDQwoq6EpLnvmhmjeJSZcZXiImW');

    // Write the content to file
    file_put_contents(__DIR__  . '/../JsonData/competitions.json', $JsonCompetitionContent);
?>