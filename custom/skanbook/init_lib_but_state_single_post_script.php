<?php

add_action('wp_footer', 'lib_but_state_single_post_template', 100);
add_action('wp_head', 'lib_but_state_single_post_script', 100);

function lib_but_state_single_post_template()
{
    ?>
    <style>
        .lib-but-state-container {
            display: flex;
            padding: 10px 0 0;
            justify-content: center;
        }
        .lib-but-state-container > * {
            flex: 0 1 220px;
        }
    </style>
    <template id="lib_but_state_single_post_template">
        <div class="lib-but-state-container">
            <?php skanbook_tag_but_state('Добавить в библиотеку'); ?>
        </div>
    </template>
    <?php
}

function lib_but_state_single_post_script()
{
    ?>
    <script>
        (function ($) {
            $(document).ready(function (e) {
                let $orient = $('.single-post .pmovie__img.img-fit');
                let $template = $('#lib_but_state_single_post_template');

                if ($orient.length && $template.length) {
                    let $temp = $($template.prop('content')).clone();
                    $orient.after($temp);
                }
            });
        })(jQuery);
    </script>
    <?php
}