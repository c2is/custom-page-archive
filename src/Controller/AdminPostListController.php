<?php

  namespace CPA\Controller;

use CPA\Helper\PostTypeHelper;
use CPA\Helper\TaxonomyHelper;

final class AdminPostListController
{
    public function __construct()
    {
        add_filter('display_post_states', array($this, 'displayArchiveStates'), 10, 2);
    }

    public function displayArchiveStates($states, $post)
    {
        $postTypeArchivesIds = PostTypeHelper::getArchivesPostTypesPages();
        $taxonomyArchivesIds = TaxonomyHelper::getArchivesTaxonomiesPages();
        $pageArchivesIds = $postTypeArchivesIds + $taxonomyArchivesIds;
        if (array_key_exists($post->ID, $pageArchivesIds)) {
            $states[] = __('Page des', 'custom-page-archive') . ' ' . strtolower($pageArchivesIds[$post->ID]);
        }

        return $states;
    }
}
