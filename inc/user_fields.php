<?php

function show_gender_in_profile($user) {
    $gender = get_user_meta($user->ID, 'gender', true);
    ?>
    <h3>Дополнительная информация</h3>
    <table class="form-table">
        <tr>
            <th><label for="gender">Пол</label></th>
            <td>
                <select name="gender" id="gender">
                    <option value="male" <?php selected($gender, 'male'); ?>>мужской</option>
                    <option value="female" <?php selected($gender, 'female'); ?>>женский</option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'show_gender_in_profile');
add_action('edit_user_profile', 'show_gender_in_profile');

function save_gender_in_profile($user_id) {
    if (isset($_POST['gender'])) {
        update_user_meta($user_id, 'gender', sanitize_text_field($_POST['gender']));
    }
}
add_action('personal_options_update', 'save_gender_in_profile');
add_action('edit_user_profile_update', 'save_gender_in_profile');
