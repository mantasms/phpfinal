<?php
define('STORAGE_FILE', 'files/form_input.txt');

require_once 'functions/form.php';
require_once 'functions/file.php';

function form_success($safe_input, $form) {
    // TO DO
}

function form_fail($safe_input, $form) {
    // TO DO
}

$form = [
    'fields' => [
        'team_name' => [
            'label' => 'Create team',
            'type' => 'text',
            'placeholder' => 'Team name',
            'validate' => [
                'validate_not_empty'
            ]
        ],
    ],
    'buttons' => [
        'submit' => [
            'text' => 'Create'
        ]
    ],
    'callbacks' => [
        'success' => [
            'form_success'
        ],
        'fail' => [
            'form_fail'
        ]
    ]
];

$show_form = true;

if (!empty($_POST)) {
    $safe_input = get_safe_input($form);
    $form_success = validate_form($safe_input, $form);

    if ($form_success) {
        $show_form = false;
    }
}
?>
<html>
    <head>
        <link rel="stylesheet" href="css/style.css">
        <title>Call Friday</title>
    </head>
    <body>
        <h1>Call Friday</h1>
        <form method="POST">
            <?php if ($show_form): ?>
                <form method="POST">
                    <?php foreach ($form['fields'] as $field_id => $field): ?>
                        <span><?php print $field['label']; ?></span>
                        <label>
                            <input type="<?php print $field['type']; ?>" 
                                   name="<?php print $field_id; ?>" 
                                   placeholder="<?php print $field['placeholder']; ?>"/>
                        </label>
                    <?php endforeach; ?>
                    <?php foreach ($form['buttons'] as $button_id => $button): ?>
                        <button name="action" value="<?php print $button_id; ?>">
                            <?php print $button['text']; ?>
                        </button>
                    <?php endforeach; ?>
                </form>
            <?php endif; ?>
        </form>
    </body>
</html>