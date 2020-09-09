<?php

  namespace CPA\Controller;

use CPA\Helper\LanguageHelper;

final class PolylangController
{
    public function __construct()
    {
      add_filter('pll_the_language_link', array($this, 'setTranslationUrl'), 10, 2);
      add_filter('pll_translation_url', array($this, 'setTranslationUrl'), 10, 2);
    }

    public function setTranslationUrl($url, $slug)
    {
      if (is_singular())
      {
        $postType = get_post_type();
        $correctArchiveUrl = $this->_getPostTypeArchiveUrl($postType, $slug);
        $url = $correctArchiveUrl . basename($url);
      }
      else if (is_post_type_archive())
      {
        $postType = get_post_type();
        $url = $this->_getPostTypeArchiveUrl($postType, $slug);
      }

      return $url;
    }

    private function _getPostTypeArchiveUrl($postType, $slug)
    {
        $prefix = LanguageHelper::getPrefixBySlug($slug);
        $pageId = get_option($prefix . $postType);

        if ($pageId) {
          return get_permalink($pageId);
        }

        return null;
    }
}
