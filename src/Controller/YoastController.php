<?php

    namespace CPA\Controller;

    final class YoastController
    {
        public function __construct()
        {
            add_filter('wpseo_canonical', array($this, 'setCanonicalUrl'), 10, 2);
        }

        public function setCanonicalUrl($canonical)
        {
            if (is_singular()) {
              $canonical = get_the_permalink();
            }

            return $canonical;
        }
    }
