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
            $regex = "^$urlPath([^/]+)/?$";
            $query = 'index.php?post_type=' . $postType . '&name=$matches[1]';
            add_rewrite_rule(
                $regex,
                $query,
                $position
            );
        }
    }
}
