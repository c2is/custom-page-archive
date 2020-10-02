<?php

    namespace CPA\Controller;

    use CPA\Helper\LanguageHelper;
    use CPA\Helper\PostTypeHelper;

    final class QueryController
    {
        public function __construct()
        {
            add_action('parse_query', array($this, 'detectCustomArchivePage'), 1);
            add_action('the_post', array($this, 'setCustomArchivePagePost'), 1, 2);
            add_filter('template_include', array($this, 'addCustomTemplate'));
            add_filter('post_type_archive_link', array($this, 'changeArchiveLink'), 10, 2);
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
            if (is_admin() || is_front_page() || is_home()) {
                return $post;
            }

            if ($query->is_post_type_archive()) {
                $postType = $query->get('post_type');
                $prefix = LanguageHelper::getPrefix();
                $pageId = get_option($prefix . $postType);
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
            if (is_admin() || !$wp_query->is_main_query()) {
                return $wp_query;
            }

            $prefix = LanguageHelper::getPrefix();
            if ($wp_query->is_page() && $wp_query->get_queried_object_id()) {
                $postTypes = PostTypeHelper::getArchivesPostTypes();
                foreach ($postTypes as $postType => $label) {
                    if ($wp_query->get_queried_object_id() == get_option($prefix . $postType)) {
                        $wp_query->query = array(
                            'post_type' => $postType
                        );

                        $wp_query->is_page = false;
                        $wp_query->is_singular = false;
                        $wp_query->is_archive = true;
                        $wp_query->is_post_type_archive = true;
                        $wp_query->set('post_type', $postType);
                        $wp_query->set('pagename', '');
                        $this->_setLanguageQuery($wp_query);
                        break;
                    }
                }
            } elseif ($wp_query->is_post_type_archive && get_option($prefix . $wp_query->get('post_type'))) {
//                $postId = get_option($prefix . $wp_query->get('post_type'));
//            $wp_query->queried_object_id = intval($postId);
//            $wp_query->queried_object = get_post($postId);
                $this->_setLanguageQuery($wp_query);
            }

            return $wp_query;
        }

        public function changeArchiveLink($link, $postType)
        {
            $prefix = LanguageHelper::getPrefix();
            $pageId = get_option($prefix . $postType);
            if ($pageId) {
                return get_permalink($pageId);
            }

            return $link;
        }

        private function _setLanguageQuery(&$wp_query)
        {
            if (function_exists('pll_current_language')) {
                $wp_query->set('lang', pll_current_language());
                $wp_query->query['lang'] = pll_current_language();
                foreach ($wp_query->tax_query->queries as $key => $taxonomyQuery)
                {
                    if ($taxonomyQuery['taxonomy'] == 'language') {
                        $wp_query->tax_query->queries[$key]['terms'][0] = pll_current_language();
                    }
                }
            }
        }
    }
