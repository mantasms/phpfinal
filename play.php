<?php
require_once 'bootstrap.php';
if (!file_exists(STORAGE_FILE) || empty(file_to_array(STORAGE_FILE))) {
    header("Location: join.php");
    exit();
}
session_start();
$nick = $_SESSION['nick'] ?? false;
$team_idx = $_SESSION['team'] ?? false;
$form = [
    'fields' => [
        'nick' => [
            'label' => 'Kam pasuoji?',
            'type' => 'select',
            'validate' => [
                'validate_not_empty',
                'validate_kick'
            ],
            'options' => get_team_mates($nick, $team_idx)
        ]
    ],
    'buttons' => [
        'submit' => [
            'text' => 'Kick the ball(z)!'
        ]
    ],
    'callbacks' => [
        'success' => [
            'form_success'
        ],
        'fail' => []
    ]
];
function get_team_mates($nick, $team_id) {
    if (file_exists(STORAGE_FILE)) {
        $teams_array = file_to_array(STORAGE_FILE);
        if ($teams_array[$team_id]) {
            $nicks_array = array_column($teams_array[$_SESSION['team']]['players'], 'nick_name');
        }
        foreach ($nicks_array as $index => $value) {
            if ($value == $nick) {
                unset($nicks_array[$index]);
            }
        }
        asort($nicks_array);
        return $nicks_array;
    }
    return [];
}
function form_success($safe_input, $form) {
    $team_idx = $_SESSION['team'] ?? false;
    $nick = $_SESSION['nick'] ?? false;
    if (file_exists(STORAGE_FILE)) {
        $teams_array = file_to_array(STORAGE_FILE);
        $ball_holder = &$teams_array[$team_idx]['ball_handler'];
        $ball_to = $teams_array[$team_idx]['players'][$safe_input['nick']]['nick_name'];
        if ($ball_holder == null || $ball_holder == $nick) {
            $ball_holder = $ball_to;
            $player['score']++;
        }
        return array_to_file($teams_array, STORAGE_FILE);
    }
}
function check_player($team_idx, $nick) {
    if (file_exists(STORAGE_FILE)) {
        $teams_array = file_to_array(STORAGE_FILE);
        $player_team = $teams_array[$team_idx] ?? false;
        if ($player_team) {
            return in_array($nick, array_column(
                            $player_team['players'], 'nick_name')
            );
        }
    }
    return false;
}
function validate_kick($field_input, &$field, $form_input) {
    $nick = $_SESSION['nick'] ?? false;
    $team_idx = $_SESSION['team'] ?? false;
    $teams_array = file_to_array(STORAGE_FILE);
    $ball_holder = $teams_array[$team_idx]['ball_handler'];
    if ($ball_holder == null || $ball_holder == $nick) {
        return true;
    } else {
        $field['error_msg'] = strtr('Negali spirti, nes @ball_holder turi kamuoli',
                [
                    '@ball_holder' => $ball_holder
                ]);
    }
}
$show_form = false;
$valid_player = false;
$message = '';
if (!empty($_SESSION)) {
    if ($nick && $team_idx !== false) {
        $valid_player = check_player($team_idx, $nick);
    }
}
if ($valid_player) {
    $show_form = true;
    if (!empty($_POST)) {
        $safe_input = get_safe_input($form);
        $form_success = validate_form($safe_input, $form);
    }
} else {
    $message = 'Eik nx';
    //header("Location: /pz2aball/join.php");
    //exit();
}
?>
<html>
    <head>
        <link rel="stylesheet" href="css/style.css">
        <title>PZ2ABALL | Play</title>
    </head>
    <body>
        <!-- Navigation -->    
        <?php require 'objects/navigation.php'; ?>        

        <!-- Content -->       
        <h1>Išbėk į aikštelę</h1>
        <?php if ($show_form): ?>
            <!-- Form -->        
            <?php require 'objects/form.php'; ?>
        <?php else: ?>
            <h2>Zašibys!</h2>
            <h3><?php print $message; ?></h3>
        <?php endif; ?>
    </body>
</html>