<?php

  namespace CPA\Controller;

use CPA\Helper\LanguageHelper;
use CPA\Helper\PostTypeHelper;

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

        $prefix = LanguageHelper::getPrefix();
        $postTypes = PostTypeHelper::getArchivesPostTypes();
        $title = __('Menu Page Archive Settings', 'custom-page-archive');

        require_once(CPA_SRC_PATH . '/Templates/adminMenu.php');
    }

    public function registerSettings()
    {
        $postTypes = PostTypeHelper::getArchivesPostTypes();
        $prefix = LanguageHelper::getPrefix();
        foreach ($postTypes as $postType => $label) {
            register_setting(
                self::SETTING_GROUP,
                $prefix . $postType,
                array($this, 'sanitize')
            );
        }
        $this->_checkSetting();
    }

    public function sanitize($input)
    {
        return absint($input);
    }

    private function _checkSetting()
    {
        global $settings_errors;

        $registeredArchives = PostTypeHelper::getActiveCustomPostTypePageArchives();
        if (count($registeredArchives) != count(array_unique($registeredArchives))) {
            add_settings_error(self::SETTING_GROUP, 'same_page_error', __('Pages must not be the same.', 'custom-page-archive'), 'error');
        }
    }
}
