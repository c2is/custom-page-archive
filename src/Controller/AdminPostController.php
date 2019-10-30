<?php

  namespace CPA\Controller;

use CPA\Helper\PostTypeHelper;
use CPA\Helper\TaxonomyHelper;

final class AdminPostController
{
    const FLUSH_RULE_OPTION = 'flush_cpa_rewrite_rules';

    public function __construct()
    {
        add_filter('save_post', array($this, 'setupFlushRewriteRules'), 10, 1);
        add_action('init', array($this, 'flushRewriteRules'));
    }

    public function setupFlushRewriteRules($postId)
    {
        $isPageArchive = PostTypeHelper::getArchivePostTypeById($postId);
        if ($isPageArchive) {
            $isPageArchive = TaxonomyHelper::getArchiveTaxonomyById($postId);
        }
        if (!$isPageArchive) {
            return;
        }

        update_option(self::FLUSH_RULE_OPTION, 'true');
    }

    public function flushRewriteRules()
    {
        if ((bool)get_option(self::FLUSH_RULE_OPTION)) {
            flush_rewrite_rules();
            update_option(self::FLUSH_RULE_OPTION, 'false');
        }
    }
}
