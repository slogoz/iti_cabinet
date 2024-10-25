<?php

/** @var string $uri_current */

$lib_nav_sorting = array(
    'default' => array(
        'url' => site_url($uri_current),
        'text' => 'по дате прочтения',
    ),
    'updated' => array(
        'url' => site_url($uri_current . '?sort=updated'),
        'text' => 'по обновлению'
    ),
    'views' => array(
        'url' => site_url($uri_current . '?sort=views'),
        'text' => 'по просмотрам'
    ),
);

$lib_nav_filter = array(
    'default' => array(
        'url' => site_url('/library/read'),
        'text' => 'по дате прочтения',
    ),
    'updated' => array(
        'url' => site_url('/library/read?sort=updated'),
        'text' => 'по обновлению'
    ),
    'rating' => array(
        'url' => site_url('/library/read?sort=rating'),
        'text' => 'по рейтингу'
    ),
);

$lib_genre = get_library_genre();

$state = get_library_state_page();
if($state !== 'favorite') {
    $lib_nav_sorting['default']['text'] = 'по добавлению в библиотеку';
}

$lib_nav_view_types = array(
    'grid_large' => array(
        'type' => 'grid',
        'view' => iti_icon('th-large', [], true)
    ),
    'grid' => array(
        'type' => 'grid',
        'view' => iti_icon('th', [], true)
    ),
    'list' => array(
        'type' => 'list-mini',
        'view' => iti_icon('th-list', [], true)
    ),
    'list_mini' => array(
        'type' => 'list-mini',
        'view' => iti_icon('list', [], true)
    ),
);
unset($lib_nav_view_types['grid_large']);
unset($lib_nav_view_types['list']);

$view_type = 'grid';

if (!empty($_GET['view_type'])) {
    $view_type = $_GET['view_type'];
}

?>
<style>
    .lib-nav-sorting {
        display: flex;
    }

    .lib-nav-sorting__order-but a {
        color: #2a6171;
        text-decoration: none;
        border-radius: 4px;
        padding: 5px 10px;
        display: inline-block;
    }

    .lib-nav-sorting__order-but:hover a {
        color: #333;
        background: #e7e7e7;
    }

    .lib-nav-sorting__order-but.active a {
        color: #fff;
        background: #2a6171;
    }

    .lib-nav-sorting__caption {
        padding: 0 10px 0 0;
    }

    .lib-nav-filter {
        display: flex;
    }

    .lib-nav-filter-form {
        display: flex;
        align-items: stretch;
    }

    .lib-nav-filter__caption {
        padding: 6px 12px;
        font-size: 14px;
        font-weight: 400;
        line-height: 1;
        color: #555;
        text-align: center;
        background-color: #eee;
        border: 1px solid #ccc;
        border-radius: 4px 0 0 4px;
        display: flex;
        align-items: center;
        margin: 0;
    }

    .lib-nav-filter-form__select {
        border-radius: 0 4px 4px 0;
        border-left: none;
        width: 100%;
        max-width: 250px;
    }

    .lib-header-view {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .container-fluid .lib-header-view {
        padding-bottom: 10px;
    }

    .lib-header-view .header,
    .lib-header-view .header h2 {
        margin: 0;
    }

    @media (min-width: 1200px) {
        .lib-nav-filter {
            justify-content: flex-end;
        }
    }

    @media (max-width: 479px) {
        .lib-nav-sorting {
            flex-direction: column;
        }
    }

</style>
<div class="col-lg-12 col-xl-6">
    <div class="lib-nav-sorting">
        <div class="lib-nav-sorting__caption">Сортировать:</div>
        <?php foreach ($lib_nav_sorting as $name => $item) :

            $class = ' class="lib-nav-sorting__order-but';
            if (isset($_GET['sort']) && $_GET['sort'] == $name) {
                $class .= ' active';
            } elseif (empty($_GET['sort']) && $name == 'default') {
                $class .= ' active';
            }
            $class .= '"';

            $data = ' data-order="' . $name . '"';

            ?>
            <div role="presentation"<?php echo $class . $data; ?>>
                <a href="<?php echo $item['url']; ?>"><?php echo $item['text']; ?></a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="col-lg-12 col-xl-6">
    <div class="lib-nav-filter">
        <form action="" class="lib-nav-filter-form">
            <label for="genre" class="lib-nav-filter__caption">Фильтр по жанру</label>
            <select name="genre" class="form-control lib-nav-filter-form__select">
                <option></option>
                <?php foreach ($lib_genre as $genre) :
                    if(isset($_GET['genre']) && $_GET['genre'] == $genre['id']) {
                        echo "<option value='{$genre['id']}' selected>{$genre['name']} ({$genre['count']})</option>";
                    } else {
                        echo "<option value='{$genre['id']}'>{$genre['name']} ({$genre['count']})</option>";
                    }
                endforeach; ?>
            </select>
        </form>
    </div>
</div>
<div class="col-12 lib-header-view">
    <?php
    $state = get_library_state_page();
    $title = $state === 'library' ? 'Моя библиотека' : get_arr_book_states()[$state];

    iti_bl_header($title);
    ?>
    <style>
        .buttons-view-result {
            display: flex;

        }

        .buttons-view-result__but {
            padding: 5px;
        }

        .buttons-view-result__but svg {
            width: 16px;
            height: 16px;
            fill: #2a6171;
        }

        .buttons-view-result__but:hover svg {
            fill: #153139;
        }

        .buttons-view-result__but {
            cursor: pointer;
        }
    </style>
    <div class="buttons-view-result">
        <?php foreach ($lib_nav_view_types as $view_key => $view_item) : ?>
            <div class="buttons-view-result__but" data-view="<?php echo $view_item['type'] ?>">
                <?php echo $view_item['view']; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script>
    (function ($) {
        $(document).ready(function () {
            $('.lib-nav-sorting__order-but').on('click', function (e) {
                e.preventDefault();

                var sort = $(this).attr('data-order');
                var currentUrl = new URL(window.location.href);

                if (sort === 'default') {
                    sort = '';
                }

                // Обновляем адресную строку и переходим по новому адресу
                currentUrl.search = updateParamsUrl('sort', sort).toString();
                window.location.href = currentUrl.toString();
            });

            // Перехват изменения select и отправки формы
            $('.lib-nav-filter-form__select').on('change', function () {
                console.log('Select value changed, form will be submitted');

                var genreValue = $(this).val(); // Получаем выбранное значение жанра
                var currentUrl = new URL(window.location.href);

                // Обновляем адресную строку и переходим по новому адресу
                currentUrl.search = updateParamsUrl('genre', genreValue).toString();
                window.location.href = currentUrl.toString();
            });

            // Кнопки вида выкладки контента
            $('.buttons-view-result__but').on('click', function () {
                var typeView = $(this).attr('data-view');
                console.log(typeView);

                var currentUrl = new URL(window.location.href);

                // Обновляем адресную строку и переходим по новому адресу
                currentUrl.search = updateParamsUrl('view_type', typeView).toString();
                window.location.href = currentUrl.toString();
            });

            function updateParamsUrl(paramKey, paramValue) {

                // Получаем текущий URL
                var currentUrl = new URL(window.location.href);

                // Получаем параметры из URL
                var params = new URLSearchParams(currentUrl.search);

                // Добавляем или заменяем параметр
                params.set(paramKey, paramValue);

                return params;
            }
        });
    })(jQuery);
</script>