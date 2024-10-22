<?php

add_action('it_cabinet_get_user', function ($user) {
    $user->firstname = get_user_meta($user->id, 'firstname', true);
    $user->location = get_user_meta($user->id, 'location', true);
    $user->hobby = get_user_meta($user->id, 'hobby', true);
    $user->favorite_genres = get_user_meta($user->id, 'favorite_genres', true);
    $user->favorite_authors = get_user_meta($user->id, 'favorite_authors', true);
    $user->favorite_books = get_user_meta($user->id, 'favorite_books', true);
    $user->favorite_quotes = get_user_meta($user->id, 'favorite_quotes', true);
    $user->about = get_user_meta($user->id, 'about', true);
});

add_action('iti_cabinet_update_user_profile', function ($user_id) {
    update_user_meta($user_id, 'firstname', $_POST['user_firstname']);
    update_user_meta($user_id, 'location', $_POST['user_location']);
    update_user_meta($user_id, 'hobby', $_POST['user_hobby']);
    update_user_meta($user_id, 'favorite_genres', $_POST['favorite_genres']);
    update_user_meta($user_id, 'favorite_authors', $_POST['favorite_authors']);
    update_user_meta($user_id, 'favorite_books', $_POST['favorite_books']);
    update_user_meta($user_id, 'favorite_quotes', $_POST['favorite_quotes']);
    update_user_meta($user_id, 'about', $_POST['user_about']);
});

add_filter('it_cabinet_user_about', function ($text, $user) {

    $about = get_user_meta($user->id, 'about', true);

    if ($about) {
        $text = $about;
    }

    return $text;
}, 10, 2);

add_filter('it_cabinet_user_data', function ($data, $user) {

    $data['location'] = array(
        'prop_name' => 'Местоположение:',
        'prop_value' => $user->location,
    );
    $data['hobby'] = array(
        'prop_name' => 'Хобби:',
        'prop_value' => $user->hobby,
    );
    $data['favorite_genres'] = array(
        'prop_name' => 'Любимые жанры:',
        'prop_value' => $user->favorite_genres,
    );
    $data['favorite_authors'] = array(
        'prop_name' => 'Любимые авторы:',
        'prop_value' => $user->favorite_authors,
    );
    $data['favorite_books'] = array(
        'prop_name' => 'Любимые книги:',
        'prop_value' => $user->favorite_books,
    );
    $data['favorite_quotes'] = array(
        'prop_name' => 'Любимые цитаты:<br>',
        'prop_value' => nl2br($user->favorite_quotes),
    );

    return $data;
}, 10, 2);

add_action('it_cabinet_fields_profile_edit', function ($user) {
    ?>
    <div class="form-group">
        <label for="user_location" class="control-label">Откуда Вы?</label>
        <input type="text" name="user_location" id="user_location" class="form-control"
               value="<?php echo $user->location; ?>">
    </div>
    <div class="form-group">
        <label for="user_hobby" class="control-label">Чем занимаетесь?</label>
        <input type="text" name="user_hobby" id="user_hobby" class="form-control"
               value="<?php echo $user->hobby; ?>">
    </div>
    <div class="form-group">
        <label for="favorite_genres" class="control-label">Любимые жанры</label>
        <input type="text" name="favorite_genres" id="favorite_genres" class="form-control"
               value="<?php echo $user->favorite_genres; ?>">
    </div>
    <div class="form-group">
        <label for="favorite_authors" class="control-label">Любимые авторы</label>
        <input type="text" name="favorite_authors" id="favorite_authors" class="form-control"
               value="<?php echo $user->favorite_authors; ?>">
    </div>
    <div class="form-group">
        <label for="favorite_books" class="control-label">Любимые книги</label>
        <input type="text" name="favorite_books" id="favorite_books" class="form-control"
               value="<?php echo $user->favorite_books; ?>">
    </div>
    <div class="form-group">
        <label for="favorite_quotes" class="control-label">Любимые цитаты</label>
        <textarea rows="5" name="favorite_quotes" id="favorite_quotes"
                  class="form-control"><?php echo $user->favorite_quotes; ?></textarea>
    </div>
    <?php
});
