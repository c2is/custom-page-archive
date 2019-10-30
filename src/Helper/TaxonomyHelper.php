<?php

  namespace CPA\Helper;

final class TaxonomyHelper
{
    public static function getArchivesTaxonomies()
    {
        $args = array(
          'public'    => true,
          '_builtin'  => false
        );
        $taxonomiesObjects = get_taxonomies($args, 'objects');
        $taxonomies = [];
        foreach ($taxonomiesObjects as $taxonomy) {
            $taxonomies[$taxonomy->name] = $taxonomy->label;
        }

        return $taxonomies;
    }

    public static function getArchivesTaxonomiesPages()
    {
        $args = array(
          'public'    => true,
          '_builtin'  => false
        );
        $taxonomiesObjects = get_taxonomies($args, 'objects');
        $taxonomyPages = [];
        foreach ($taxonomiesObjects as $taxonomy) {
            $value = get_option('cpat_' . $taxonomy->name);
            if ($value) {
                $taxonomyPages[$value] = $taxonomy->label;
            }
        }

        return $taxonomyPages;
    }

    public static function getArchiveTaxonomyById($id)
    {
        $args = array(
          'public'    => true,
          '_builtin'  => false
        );
        $taxonomiesObjects = get_taxonomies($args, 'objects');
        foreach ($taxonomiesObjects as $taxonomy) {
            $value = get_option('cpat_' . $taxonomy->name);
            if ($value == $id) {
                return $taxonomy->name;
            }
        }

        return null;
    }

    public static function getActiveTaxonomyArchives()
    {
        $args = array(
          'public'    => true,
          '_builtin'  => false
        );
        $taxonomiesObjects = get_taxonomies($args, 'objects');
        $customTaxonomyPages = [];
        foreach ($taxonomiesObjects as $taxonomy) {
            $value = get_option('cpat_' . $taxonomy->name);
            if ($value) {
                $customTaxonomyPages[$taxonomy->name] = $value;
            }
        }

        return $customTaxonomyPages;
    }
}
