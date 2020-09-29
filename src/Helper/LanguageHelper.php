<?php

  namespace CPA\Helper;

  final class LanguageHelper
  {
    public static function getPrefix()
    {
      $prefix = 'cpa_';
      if (function_exists('pll_current_language')) {
        if (pll_current_language('slug') != pll_default_language('slug')) {
          $prefix .= pll_current_language('slug') . '_';
        }
      }
      elseif (defined('ICL_LANGUAGE_CODE')) {
        $defaultLang = apply_filters('wpml_default_language', null);
        if (ICL_LANGUAGE_CODE != $defaultLang) {
          $prefix .= ICL_LANGUAGE_CODE . '_';
        }
      }

      return $prefix;
    }

    public static function getPrefixBySlug($slug)
    {
      $prefix = 'cpa_';
      if (function_exists('pll_current_language')) {
        if ($slug != pll_default_language('slug')) {
          $prefix .= $slug . '_';
        }
      }
      elseif (defined('ICL_LANGUAGE_CODE')) {
        $defaultLang = apply_filters('wpml_default_language', null);
        if ($slug != $defaultLang) {
          $prefix .= $slug . '_';
        }
      }

      return $prefix;
    }
  }
