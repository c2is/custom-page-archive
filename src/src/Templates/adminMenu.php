<div class="wrap">
  <h1><?php echo esc_html($title); ?></h1>

  <form method="post" action="options.php">
    <?php settings_fields('cpa-settings-group'); ?>
    <?php do_settings_sections('cpa-settings-group'); ?>
    <table class="form-table" role="presentation">
      <tr>
        <ul>
            <?php foreach ($postTypes as $postType => $label) : ?>
            <li>
              <label for="page_on_front">
                <?php
                  printf(
                      $label . ' : %s',
                      wp_dropdown_pages(
                          array(
                          'name' => $prefix . $postType,
                          'echo' => 0,
                          'show_option_none' => __('&mdash; Select &mdash;'),
                          'option_none_value' => '0',
                          'selected' => get_option($prefix . $postType)
                          )
                      )
                  );
                ?>
              </label>
            </li>
            <?php endforeach; ?>
        </ul>
      </tr>
    </table>

    <?php submit_button(); ?>
  </form>
</div>
