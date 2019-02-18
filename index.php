<?php
define('STORAGE_FILE', 'files/form_input.txt');

require_once 'functions/form.php';
require_once 'functions/file.php';

var_dump($_COOKIE);

function form_success($safe_input, $form) {
    if (file_exists(STORAGE_FILE)) {
        $existing_array = file_to_array(STORAGE_FILE);
        $existing_array[] = $safe_input;
    } else {
        $existing_array = [$safe_input];
    }
    return array_to_file($existing_array, STORAGE_FILE);
}

function form_fail($safe_input, $form) {
    // TO DO
}

$form = [
    'fields' => [
        'drink_eat' => [
            'label' => "Would you rather drink a pint of your enemy's pee while "
            . "they look you in the eye or eat a bowl of your own shit while "
            . "everyone you've ever dated watches?",
            'type' => 'text',
            'placeholder' => 'Answer here lad',
            'validate' => [
                'validate_not_empty'
            ],
        ],
        'eye' => [
            'label' => "Would you rather slice your eye in half with a razor "
            . "blade or swallow 10 needles?",
            'type' => 'text',
            'placeholder' => 'Answer here pal',
            'validate' => [
                'validate_not_empty'
            ],
        ],
        'shit' => [
            'label' => "Would you rather pee dry sand for the rest of your life "
            . "or poo a hard, big brick every year on your birthday?",
            'type' => 'text',
            'placeholder' => 'Answer here dude',
            'validate' => [
                'validate_not_empty'
            ],
        ],
        'ass' => [
            'label' => "Would you rather eat someone's ass right afther they "
            . "had diarrhea or eat your mom's used tampon?",
            'type' => 'text',
            'placeholder' => 'Answer here skank',
            'validate' => [
                'validate_not_empty'
            ],
        ],
        'your_number' => [
            'label' => "Now, pal, you need to write down your lucky number "
            . "which you use every time you forget when is your girlfriends "
            . "birthday",
            'type' => 'text',
            'placeholder' => '1-100',
            'validate' =>
            [
                'validate_not_empty',
                'validate_is_number'
            ],
        ]
    ],
    'buttons' => [
        'submit' => [
            'text' => 'Submit your mastershit'
        ]
    ],
    'callbacks' => [
        'success' => [
            'form_success'
        ],
        'fail' => [
            'form_fail'
        ]
    ],
];

$show_form = true;
if (!isset($_COOKIE['form'])) {
    if (!empty($_POST)) {
        $safe_input = get_safe_input($form);
        $form_success = validate_form($safe_input, $form);

        if ($form_success) {
            setcookie('form', 'submitted', time() + 3600, '/');
            $show_form = false;
        }
    }
} else {
    $show_form = false;
}

$stored_data = load_form_data();
?>
<html>
    <head>
        <title>02/14/2019</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <h1>Would you rather..?</h1>
        <?php if ($show_form): ?>
            <form method="POST">
                <!-- Input Fields -->
                <?php foreach ($form['fields'] as $field_id => $field): ?>
                    <label>
                        <p><?php print $field['label']; ?></p>
                        <input type="<?php print $field['type']; ?>" name="<?php print $field_id; ?>" placeholder="<?php print $field['placeholder']; ?>"/>
                        <?php if (isset($field['error_msg'])): ?>
                            <p class="error"><?php print $field['error_msg']; ?></p>
                        <?php endif; ?>
                    </label>
                <?php endforeach; ?>

                <!-- Buttons -->
                <?php foreach ($form['buttons'] as $button_id => $button): ?>
                    <button name="action" value="<?php print $button_id; ?>">
                        <?php print $button['text']; ?>
                    </button>
                <?php endforeach; ?>
            </form>
        <?php else: ?>
            <?php foreach ($stored_data as $user_data): ?>
                <?php foreach ($user_data as $fields): ?>       
                    <p><?php print $fields['title'] . ': ' . $fields['value']; ?></p>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </body>
</html>