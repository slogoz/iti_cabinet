<?php

function library_template_button_user_menu()
{
    $user_id = get_current_user_id();
    $user = get_iti_user($user_id);

    ob_start();
    ?>
    <style>
        .header-user-menu {
            /*background-color: lightgray;*/
            font-family: Montserrat, Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 14px;
            line-height: 1.45;
            position: relative;
        }

        .header-user-menu > a {
            color: #2a6171;
            text-decoration: none;
        }

        .header-user-menu > a:hover {
            background: #2a6171;
            color: #fff;
        }

        .header-user-menu__dropdown-toggle {
            padding: 5px 10px;
            display: flex;
            align-items: center;
        }

        .header-user-menu__avatar {
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            overflow: hidden;
            user-select: none;
            background-color: lightgray;
            z-index: 1;
            position: relative;
            width: 40px;
            height: 40px;
        }

        .header-user-menu__avatar-image {
            max-width: 40px;
            position: absolute;
            top: 0;
            bottom: 0;
            margin: auto;
            z-index: 0;
        }

        .header-user-menu__user-name {
            text-overflow: ellipsis;
            max-width: 100px;
            overflow: hidden;
            display: block;
            white-space: nowrap;
        }

        .header-user-menu__user-name-box {
            display: flex;
            align-items: center;
            margin-left: 10px;
        }

        .header-user-menu__caret {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 2px;
            vertical-align: middle;
            border-top: 4px dashed;
            border-right: 4px solid transparent;
            border-left: 4px solid transparent;
        }

        .header-user-menu__dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            z-index: 1000;
            display: none;
            float: left;
            min-width: 160px;
            padding: 5px 0;
            margin: 2px 0 0;
            list-style: none;
            font-size: 14px;
            text-align: left;
            background-color: #fff;
            border: 1px solid #ccc;
            border: 1px solid rgba(0, 0, 0, .15);
            border-radius: 4px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
            background-clip: padding-box;
        }

        .header-user-menu__dropdown-menu a {
            display: block;
            padding: 3px 20px;
            clear: both;
            font-weight: 400;
            line-height: 1.428571429;
            color: #333;
            white-space: nowrap;
            text-decoration: none;
        }

        .header-user-menu__dropdown-menu a:hover {
            text-decoration: none;
            color: #262626;
            background-color: #f5f5f5;
        }


        @media (max-width: 767px) {
            .header-user-menu {
                float: right;
                position: relative;
                bottom: 65px;
            }
        }

    </style>
    <template id="buttonUserMenu">
        <div class="header-user-menu">
            <a href="#" role="button" class="header-user-menu__dropdown-toggle">
                <div class="header-user-menu__avatar-box">
                    <div class="header-user-menu__avatar">
                        <img src="<?php echo $user->image; ?>"
                             class="header-user-menu__avatar-image">
                    </div>
                </div>
                <div class="header-user-menu__user-name-box">
                    <span class="header-user-menu__user-name"><?php echo $user->firstname; ?></span> <span
                            class="header-user-menu__caret"></span>
                </div>
            </a>
            <div role="menu" class="header-user-menu__dropdown-menu">
                <div><a href="<?php echo site_url('profile'); ?>" title="Моя страница">Моя страница</a></div>
                <div><a href="<?php echo site_url('library'); ?>" title="Моя библиотека">Моя библиотека
                        (<?php echo library_get_count_state(); ?>)</a></div>
                <div><a href="<?php echo wp_logout_url(site_url()); ?>" title="Выйти">Выйти</a>
                </div>
            </div>
        </div>
    </template>
    <?php
    $template = ob_get_clean();

    echo $template;
}

function add_page_button_user_menu()
{
    ?>
    <script>
        (function ($) {
            $(document).ready(function (e) {
                let $orient = $('.header-search');
                let $template = $('#buttonUserMenu');

                if ($orient.length && $template.length) {
                    let $temp = $($template.prop('content')).clone();
                    $orient.after($temp);

                    let $toggle = $('.header-user-menu__dropdown-toggle');
                    let $dropMenu = $('.header-user-menu__dropdown-menu');

                    $(document).on('click', function (event) {
                        // Проверяем, был ли клик за пределами целевого элемента
                        if ((!$dropMenu.is(event.target) && $dropMenu.has(event.target).length === 0) &&
                            (!$toggle.is(event.target) && $toggle.has(event.target).length === 0)) {
                            // Скрываем целевой элемент
                            $dropMenu.hide();
                        }
                    });

                    $toggle.on('click', function (e) {
                        $dropMenu.toggle();
                    });


                    console.log($(this), 'loader');

                }
            });
        })(jQuery);
    </script>
    <?php
}

function library_template_button_user_login()
{
    ob_start();
    ?>
    <style>
        .control-but.control-but_user-login {
            background: transparent;
            border-color: transparent;
            color: #333;
            text-decoration: none;
            padding: 5px 10px;
        }

        .control-but.control-but_user-login svg {
            fill: #333
        }

        .control-but.control-but_user-login:hover {
            background: #2a6171;
            border-color: #23515e;
            color: #fff;
        }

        .control-but.control-but_user-login:hover svg {
            fill: #fff
        }

        @media (max-width: 767px) {
            .control-but.control-but_user-login {
                float: right;
                position: relative;
                bottom: 50px;
            }
        }
    </style>
    <template id="buttonUserLogin">
        <a href="<?php echo site_url('login'); ?>" class="control-but control-but_primary control-but_user-login">
            <?php iti_icon('sign-in'); ?>
            Войти
        </a>
    </template>
    <script>
        (function ($) {
            $(document).ready(function (e) {
                let $orient = $('.header-search');
                // $orient = $('.orient-element');
                let $template = $('#buttonUserLogin');

                if ($orient.length && $template.length) {
                    let $temp = $($template.prop('content')).clone();
                    $orient.after($temp);
                }
            });
        })(jQuery);
    </script>
    <?php
    $html = ob_get_clean();
    echo $html;
}