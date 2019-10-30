<?php

  namespace CPA\Controller;

  use CPA\Helper\PostTypeHelper;
  use CPA\Helper\TaxonomyHelper;

final class PostController
{
    public function __construct()
    {
        add_filter('post_type_link', array($this, 'addCustomPageArchivePostTypeInLink'), 10, 2);
        add_filter('term_link', array($this, 'addCustomPageArchiveTaxonomyInLink'), 10, 3);
    }

    public function addCustomPageArchivePostTypeInLink($link, $post)
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

    public function addCustomPageArchiveTaxonomyInLink($link, $term, $taxonomy)
    {
        $taxonomyPages = TaxonomyHelper::getActiveTaxonomyArchives();
        if (!array_key_exists($taxonomy, $taxonomyPages)) {
            return $link;
        }

        $baseArchiveUri = $this->_getTaxonomyLink($taxonomy);
        $newBaseArchiveUri = get_permalink($taxonomyPages[$taxonomy]);
        $link = str_replace($baseArchiveUri, $newBaseArchiveUri, $link);

        return $link;
    }

    private function _getTaxonomyLink($taxonomy)
    {
        $tax = get_taxonomy($taxonomy);
        $link = get_bloginfo('url') . '/' . $tax->rewrite['slug'];

        return $link;
    }
}
