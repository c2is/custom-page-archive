<?php

    namespace CPA\Helper;

    final class PostTypeHelper
    {
        public static function getArchivesPostTypes()
        {
            $args = array(
                '_builtin'            => false
            );
            $postTypesObjects = get_post_types($args, 'objects');
            $postTypes = [];
            foreach ($postTypesObjects as $postType) {
                $postTypes[$postType->name] = $postType->label;
            }

            return $postTypes;
        }

        public static function getArchivesPostTypesPages()
        {
            $args = array(
                '_builtin'            => false
            );
            $postTypesObjects = get_post_types($args, 'objects');
            $postTypesPages = [];
            foreach ($postTypesObjects as $postType) {
                $value = get_option('cpa_' . $postType->name);
                if ($value) {
                    $postTypesPages[$value] = $postType->label;
                }
            }

            return $postTypesPages;
        }

        public static function getArchivePostTypeById($id)
        {
            $args = array(
                '_builtin'            => false
            );
            $postTypesObjects = get_post_types($args, 'objects');
            foreach ($postTypesObjects as $postType) {
                $value = get_option('cpa_' . $postType->name);
                if ($value == $id) {
                    return $postType->name;
                }
            }

            return null;
        }

        public static function getActiveCustomPostTypePageArchives()
        {
            $args = array(
                '_builtin'            => false
            );
            $postTypesObjects = get_post_types($args, 'objects');
            $customPostTypePages = [];
            foreach ($postTypesObjects as $postType) {
                $value = get_option('cpa_' . $postType->name);
                if ($value) {
                    $customPostTypePages[$postType->name] = $value;
                }
            }

            return $customPostTypePages;
        }
    }
