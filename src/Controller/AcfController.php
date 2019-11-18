<?php

  namespace CPA\Controller;

use CPA\Helper\PostTypeHelper;

final class AcfController
{
    public function __construct()
    {
        add_filter('acf/location/rule_values/page_type', array($this, 'setArchivePageChoices'));
        add_filter('acf/location/rule_match/page_type', array($this, 'matchArchivePageChoice'), 10, 2);
    }

    public function setArchivePageChoices($choices)
    {
        $pageArchivesIds = PostTypeHelper::getArchivesPostTypesPages();
        foreach ($pageArchivesIds as $pageArchiveId => $pageArchive) {
            $postType = PostTypeHelper::getArchivePostTypeById($pageArchiveId);
            $choices['cpa_' . $postType] = __('Page des', 'custom-page-archive') . ' ' . strtolower($pageArchive);
        }

        return $choices;
    }

    public function matchArchivePageChoice($match, $rule)
    {
        if (strpos($rule['value'], 'cpa_') !== false) {
            $currentPostId = get_the_ID();
            $selectedPostId = get_option($rule['value']);

            if ($rule['operator'] == "==") {
                $match = ($currentPostId == $selectedPostId);
            } elseif ($rule['operator'] == "!=") {
                $match = ($currentPostId != $selectedPostId);
            }
        }

        return $match;
    }
}
