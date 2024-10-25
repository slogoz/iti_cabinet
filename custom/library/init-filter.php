<?php

function get_library_genre()
{
    $state = get_library_state_page();

    $query = get_library_post_view($state, true);

    $all_categories = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Получаем категории для текущего поста
            $categories = get_the_category();

            if (!empty($categories)) {
                foreach ($categories as $category) {
                    if(empty($all_categories[$category->term_id])) {
                        $all_categories[$category->term_id] = array(
                            'count' => 0,
                        );
                    }
                    $all_categories[$category->term_id]['id'] = $category->term_id;
                    $all_categories[$category->term_id]['name'] = $category->name;
                    $all_categories[$category->term_id]['count'] += 1;
                }
            }
        }
        wp_reset_postdata();
    }

    usort($all_categories, function ($a, $b) {
        return $a['name'] <=> $b['name'];
    });

    return $all_categories;
}