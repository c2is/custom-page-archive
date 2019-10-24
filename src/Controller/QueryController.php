<?php

  namespace CPA\Controller;

use CPA\Helper\PostTypeHelper;

final class QueryController
{
    public function __construct()
    {
        add_action('parse_query', array($this, 'detectCustomArchivePage'), 1);
        add_action('the_post', array($this, 'setCustomArchivePagePost'), 1, 2);
        add_filter('template_include', array($this, 'addCustomTemplate'));
    }

    public function addCustomTemplate($templatePath)
    {
        if (get_query_var('page_for_custom_post_archive')) {
            $postType = get_query_var('page_for_custom_post_archive');
            $templates = array(
            'archive-' . $postType . '.php',
            'archive.php'
            );
            $templatePath = get_query_template('archive', $templates);
        }

        return $templatePath;
    }

  /**
   * @param $post WP_Post
   * @param $query WP_Query
   * @return int[]|WP_Post[]
   */
    public function setCustomArchivePagePost($post, $query)
    {
        if (is_admin()) {
            return $post;
        }

        if ($query->is_post_type_archive()) {
            $postType = $query->get('post_type');
            $pageId = get_option('cpa_' . $postType);
            $post = get_post($pageId);
        }

        return $post;
    }

  /**
   * @param $wp_query \WP_Query
   * @return mixed
   */
    public function detectCustomArchivePage($wp_query)
    {
        if (is_admin()) {
            return $wp_query;
        }

        if ($wp_query->is_page()) {
            $postTypes = PostTypeHelper::getArchivesPostTypes();
            foreach ($postTypes as $postType => $label) {
                if ($wp_query->get_queried_object_id() == get_option('cpa_' . $postType)) {
                    $wp_query->query = array(
                      'post_type' => $postType
                    );

                    $wp_query->is_page = false;
                    $wp_query->is_singular = false;
                    $wp_query->is_archive = true;
                    $wp_query->is_post_type_archive = true;
                    $wp_query->set('post_type', $postType);
                    $wp_query->set('pagename', '');
                    break;
                }
            }
        } elseif (!$wp_query->get_queried_object_id() && $wp_query->is_post_type_archive && get_option('cpa_' . $wp_query->get('post_type'))) {
            $postId = get_option('cpa_' . $wp_query->get('post_type'));
            $wp_query->queried_object_id = intval($postId);
            $wp_query->queried_object = get_post($postId);
        }

        return $wp_query;
    }
}
