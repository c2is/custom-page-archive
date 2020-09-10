<?php

    namespace CPA\Controller;

    use CPA\Helper\PostTypeHelper;

    final class PostController
    {
        public function __construct()
        {
            add_filter('post_type_link', array($this, 'addCustomPageArchiveInLink'), 10, 2);
            add_filter('edit_post_link', array($this, 'addCustomPageArchiveInEditLink'), 10, 2);
        }

        public function addCustomPageArchiveInLink($link, $post)
        {
            $postTypePages = PostTypeHelper::getActiveCustomPostTypePageArchives();
            if (!array_key_exists($post->post_type, $postTypePages)) {
                return $link;
            }

            $newBaseArchiveUri = get_permalink($postTypePages[$post->post_type]);
            $link = $newBaseArchiveUri . basename($link);

            $lastChar = substr($link, -1);
            if ($lastChar != '/') {
                $link .= '/';
            }

            return $link;
        }

        public function addCustomPageArchiveInEditLink($link, $postId)
        {
            $post = get_post($postId);
            $postTypePages = PostTypeHelper::getActiveCustomPostTypePageArchives();
            if (!array_key_exists($post->post_type, $postTypePages)) {
                return $link;
            }

            $newBaseArchiveUri = get_permalink($postTypePages[$post->post_type]);
            $link = $newBaseArchiveUri . basename($link);

            $lastChar = substr($link, -1);
            if ($lastChar != '/') {
                $link .= '/';
            }

            return $link;
        }
    }
