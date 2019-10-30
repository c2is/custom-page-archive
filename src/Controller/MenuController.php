<?php

  namespace CPA\Controller;

use CPA\Helper\PostTypeHelper;
use CPA\Helper\TaxonomyHelper;

final class MenuController
{
    const SETTING_GROUP = 'cpa-settings-group';

    public function __construct()
    {
        add_action('admin_menu', array($this, 'createMenu'));
        add_action('admin_init', array($this, 'registerSettings'));
    }

    public function createMenu()
    {
        $parentSlug = 'options-general.php';
        $pageTitle = __('Menu Page Archive Settings', 'custom-page-archive');
        $menuTitle = __('Menu Page Archive', 'custom-page-archive');
        $capability = 'manage_options';
        $menuSlug = 'custom-page-archive';

        add_submenu_page($parentSlug, $pageTitle, $menuTitle, $capability, $menuSlug, array($this, 'renderPage'));
    }

    public function renderPage()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('Sorry, you are not allowed to manage options for this site.'));
        }

        $postTypes = PostTypeHelper::getArchivesPostTypes();
        $taxonomies = TaxonomyHelper::getArchivesTaxonomies();
        $title = __('Menu Page Archive Settings', 'custom-page-archive');

        require_once(CPA_SRC_PATH . '/Templates/adminMenu.php');
    }

    public function registerSettings()
    {
        $postTypes = PostTypeHelper::getArchivesPostTypes();
        foreach ($postTypes as $postType => $label) {
            register_setting(
                self::SETTING_GROUP,
                'cpa_' . $postType,
                array($this, 'sanitize')
            );
        }

        $taxonomies = TaxonomyHelper::getArchivesTaxonomies();
        foreach ($taxonomies as $taxonomy => $label) {
            register_setting(
                self::SETTING_GROUP,
                'cpat_' . $taxonomy,
                array($this, 'sanitize')
            );
        }
        $this->_checkSettings();
        flush_rewrite_rules();
    }

    public function sanitize($input)
    {
        return absint($input);
    }

    private function _checkSettings()
    {
        $registeredPostTypeArchives = PostTypeHelper::getActiveCustomPostTypePageArchives();
        if (count($registeredPostTypeArchives) != count(array_unique($registeredPostTypeArchives))) {
            add_settings_error(self::SETTING_GROUP, 'same_page_error', __('Pages of post type must not be the same.', 'custom-page-archive'), 'error');
        }

        $registeredTaxonomyArchives = TaxonomyHelper::getArchivesTaxonomiesPages();
        if (count($registeredTaxonomyArchives) != count(array_unique($registeredTaxonomyArchives))) {
            add_settings_error(self::SETTING_GROUP, 'same_page_error', __('Pages of taxonomy must not be the same.', 'custom-page-archive'), 'error');
        }
    }
}
