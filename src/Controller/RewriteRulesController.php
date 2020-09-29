<?php

namespace CPA\Controller;

use CPA\Helper\PostTypeHelper;

final class RewriteRulesController
{
    public function __construct()
    {
        add_action('init', array($this, 'setCustomArchivePageRewriteRules'), 9999, 1);
    }

    public function setCustomArchivePageRewriteRules()
    {
        $postTypePages = PostTypeHelper::getActiveCustomPostTypePageArchives();
        $position = 'top';
        foreach ($postTypePages as $postType => $postId) {
            $customArchivePageLink = get_permalink($postId);
            $urlPath = parse_url($customArchivePageLink, PHP_URL_PATH);
            $urlPath = ltrim($urlPath, '/');
            $urlPath = substr($urlPath, 0, -1);

            $regex = "^$urlPath/?$";
            $query = 'index.php?post_type=' . $postType;

            add_rewrite_rule(
                $regex,
                $query,
                $position
            );

            $regex = "^$urlPath/([^/]+)/?$";
            $query = 'index.php?post_type=' . $postType . '&name=$matches[1]&lang=en';

//            d($regex, $query);
            add_rewrite_rule(
                $regex,
                $query,
                $position
            );


            $regex = "^$urlPath/page/([0-9]{1,})/?$";
            $query = 'index.php?post_type=' . $postType . '&paged=$matches[1]';

            add_rewrite_rule(
                $regex,
                $query,
                $position
            );
        }
    }
}
