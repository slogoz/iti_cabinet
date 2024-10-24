<?php

/** @var string $uri_current */
$state = str_replace('library/', '', $uri_current);
if ($state == 'library') {
    $state = 'all';
}

$post_view = get_library_post_view($state);

?>
<style>
    .view-card {
        display: flex;
        flex-direction: column;
    }

    .view-card__img {
        max-width: 100%;
        width: auto;
        max-height: 280px;
        box-shadow: 0 15px 30px -18px rgba(0, 0, 0, .9);
        border-radius: 10px;
    }

    .view-card__link {
        text-decoration: none;
    }

    .view-card__title {
        margin: 5px 0 0;
        font-family: PT Sans, Tahoma, Helvetica, sans-serif;
        font-size: 17px;
        font-weight: 700;
        line-height: 17px;
        color: #000;
        max-width: 215px;
        transition: all .2s ease 0s;
        text-align: center;
    }

    .view-card__meta {
        text-align: center;
    }

    .view-card__author,
    .view-card__date {
        font-size: 13px;
        color: #555;
        transition: all .2s ease 0s;
    }

    .view-card__title:hover,
    .view-card__link_author:hover .view-card__author {
        color: #2a6171;
    }

    .view-card__info {
        text-align: center;
    }

    .view-card__views,
    .view-card__comments {
        position: relative;
        display: inline-block;
        padding-left: 1.7em;
    }

    .view-card__views:before,
    .view-card__comments:before {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        color: #027b9a;
    }

    .view-card__comments:before {
        content: "💬";
    }

    .view-card__views:before {
        content: "👀";
    }

    .view-card .view-card__img {
        margin: 0 auto 10px;
    }

    .view-card .iti-but {
        margin: 0 0 10px;
    }

    .view-card .view-card__title {
        margin: 0 0 5px;
    }

    .view-card .view-card__views,
    .view-card .view-card__comments {
        margin-right: 20px;
    }

    .panel.panel-list-mini {
        height: auto;
        margin: 0 0 15px;
    }

    .view-card_list-mini .view-card__img {
        max-width: 60px;
        max-height: 100px;
        border-radius: 4px;
        box-shadow: 0 4px 4px 0 rgba(37, 38, 40, .2);
        margin: 0 10px 0 0;
        display: block;
    }

    .view-card_list-mini {
        flex-direction: row;
    }

    .view-card_list-mini .iti-but {
        font-size: 12px;
        padding: 3px 5px;
        margin: 0 10px 0 0;
    }

    .view-card_list-mini .view-card__meta {
        text-align: left;
        margin: 0 0 10px;
    }

    .view-card_list-mini .view-card__info {
        text-align: left;
    }

    .view-card_list-mini .view-card__title {
        max-width: 100%;
        text-align: left;
        margin: 0;
    }

    .view-card__body {

    }

    @media (max-width: 480px) {
        .view-card__title {
            font-size: 15px;
        }

        .view-card__author,
        .view-card__date {
            font-size: 12px;
        }
    }
</style>
<div class="col-lg-12">
    <?php
    if ($post_view->have_posts()) : ?>
        <?php if ($view_type === 'grid') : ?>
            <div class="panel panel-default">
                <div class="panel-body row">
                    <?php while ($post_view->have_posts()) : $post_view->the_post();
                        // Вывод контента каждой записи
                        ?>
                        <div class="col-lg-2 col-sm-3 col-ms-4 col-xs-6 view-card">
                            <a href="<?php the_permalink(); ?>" class="view-card__link view-card__link_img">
                                <img src="https://topliba.com/covers/886311_200x300.jpg?t=1729705506"
                                     class="view-card__img">
                            </a>
                            <?php echo library_tag_but_state(); ?>
                            <a href="<?php the_permalink(); ?>" class="view-card__link view-card__link_title">
                                <div class="view-card__title"><?php the_title(); ?></div>
                            </a>
                            <div class="view-card__info">
                                <span class="view-card__comments">0</span>
                                <span class="view-card__views">11</span>
                            </div>
                            <div class="view-card__meta">
                                <a href="<?php the_permalink(); ?>" class="view-card__link view-card__link_author">
                                    <span class="view-card__author">Автор книги</span>
                                </a>
                                <span class="view-card__date"><?php echo date(', Y'); ?></span>
                            </div>
                        </div>
                    <?php
                    endwhile; ?>
                </div>
            </div>
        <?php elseif ($view_type === 'list') : ?>
        <?php elseif ($view_type === 'list-mini') : ?>
            <?php while ($post_view->have_posts()) : $post_view->the_post();
                // Вывод контента каждой записи
                ?>
                <div class="panel panel-default panel-list-mini row">
                    <div class="panel-body col-xs-12 view-card view-card_list-mini">
                        <a href="<?php the_permalink(); ?>" class="view-card__link view-card__link_img">
                            <img src="https://topliba.com/covers/886311_200x300.jpg?t=1729705506"
                                 class="view-card__img">
                        </a>
                        <div class="view-card__body">
                            <a href="<?php the_permalink(); ?>" class="view-card__link view-card__link_title">
                                <div class="view-card__title"><?php the_title(); ?></div>
                            </a>
                            <div class="view-card__meta">
                                <a href="<?php the_permalink(); ?>" class="view-card__link view-card__link_author">
                                    <span class="view-card__author">Автор книги</span>
                                </a>
                                <span class="view-card__date"><?php echo date(', Y'); ?></span>
                            </div>
                            <div class="view-card__info">
                                <?php echo library_tag_but_state(); ?>
                                <span class="view-card__comments">0</span>
                                <span class="view-card__views">11</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endwhile; ?>

        <?php endif; ?>
    <?php else : ?>

        Ничего не найдено.
    <?php endif; ?>
</div>