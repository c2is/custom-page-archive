<?php

  namespace CPA\Controller;

  use CPA\Helper\PostTypeHelper;

final class PostController
{
    public function __construct()
    {
        add_filter('post_type_link', array($this, 'addCustomPageArchiveInLink'), 10, 2);
    }

    public function addCustomPageArchiveInLink($link, $post)
    {
        $postTypePages = PostTypeHelper::getActiveCustomPostTypePageArchives();
        if (!array_key_exists($post->post_type, $postTypePages)) {
            return $link;
        }

        $baseArchiveUri = get_post_type_archive_link($post->post_type);
        $newBaseArchiveUri = get_permalink($postTypePages[$post->post_type]);
        $link = str_replace($baseArchiveUri, $newBaseArchiveUri, $link);

        return $link;
    }
}
