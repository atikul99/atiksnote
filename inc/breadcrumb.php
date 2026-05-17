<?php

function site_breadcrumb() {

    if (is_front_page()) {
		return;
	}

    $separator = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>';
    $home_title = 'Home';
    $output = '<nav class="site-breadcrumb">';
    $output .= '<a href="' . home_url() . '">' . $home_title . '</a>' . $separator;

    if (is_single()) {
        $categories = get_the_category();
        if ($categories) {
            $cat = $categories[0];
            $output .= get_category_parents($cat, true, $separator);
        }
        $output .= '<span>' . get_the_title() . '</span>';

    } elseif (is_page()) {
        global $post;
        if ($post->post_parent) {
            $parents = [];
            $parent_id = $post->post_parent;
            while ($parent_id) {
                $page = get_page($parent_id);
                $parents[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id = $page->post_parent;
            }
            $parents = array_reverse($parents);
            foreach ($parents as $parent) {
                $output .= $parent . $separator;
            }
        }
        $output .= '<span>' . get_the_title() . '</span>';

    } elseif (is_category()) {
        $output .= '<span>' . single_cat_title('', false) . '</span>';

    } elseif (is_tag()) {
        $output .= '<span>Tag: ' . single_tag_title('', false) . '</span>';

    } elseif (is_search()) {
        $output .= '<span>Search results for: ' . get_search_query() . '</span>';

    } elseif (is_404()) {
        $output .= '<span>404 Not Found</span>';
    }

    $output .= '</nav>';

    echo $output;
}