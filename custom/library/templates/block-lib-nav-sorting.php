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
    'rating' => array(
        'url' => site_url($uri_current . '?sort=rating'),
        'text' => 'по рейтингу'
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

</style>
<div class="col-lg-12 col-xl-6">
    <div class="lib-nav-sorting">
        <div class="lib-nav-sorting__caption">Сортировать:</div>
        <?php foreach ($lib_nav_sorting as $name => $item) :

            $class = ' class="lib-nav-sorting__order-but';
            if (isset($_GET['sort']) && $_GET['sort'] == $name) {
                $class .= ' active';
            } elseif (!isset($_GET['sort']) && $name == 'default') {
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
                <option value="12682">Young adult (1)</option>
                <option value="12381">Боевое фэнтези (1)</option>
                <option value="12478">Бытовое фэнтези (1)</option>
                <option value="12189">Героическая фантастика (1)</option>
                <option value="12593">Героическое фэнтези (1)</option>
                <option value="12222">Детская литература: прочее (1)</option>
                <option value="12255">Домашние животные (1)</option>
                <option value="12592">Зарубежное фэнтези (1)</option>
                <option value="12133">Короткие любовные романы (4)</option>
                <option value="12085">Любовная фантастика (2)</option>
                <option value="12390">Любовное фэнтези (3)</option>
                <option value="12683">Молодежная проза (1)</option>
                <option value="12113">Научная Фантастика (1)</option>
                <option value="12196">О любви (1)</option>
                <option value="12647">Остросюжетные любовные романы (3)</option>
                <option value="12147" selected="selected">Попаданцы (2)</option>
                <option value="12479">Приключенческое фэнтези (2)</option>
                <option value="12135">Природа и животные (1)</option>
                <option value="12679">Романтическое фэнтези (2)</option>
                <option value="12327">Самиздат, сетевая литература (2)</option>
                <option value="12097">Сказка (1)</option>
                <option value="12748">Служебный роман (2)</option>
                <option value="12128">Современные любовные романы (10)</option>
                <option value="12142">Социальная фантастика (1)</option>
                <option value="12084">Ужасы (1)</option>
                <option value="12600">Эпическое фэнтези (1)</option>
                <option value="12170">Эротика (1)</option>
            </select>
        </form>
    </div>
</div>
<div class="col-12 lib-header-view">
    <?php
    iti_bl_header('Регистрация на сайте', array(
        'after_title' => '!'
    ));
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