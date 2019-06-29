<?php 

/**
 * Implements hook_views_data().
 */
function custom_block_views_data() {
  $data['views']['text_css_content'] = [   // global group
    'field' => [
      'title' => t('Custom text field'),
      'help' => t('Unfiltered text or CSS markup.'),
      'id' => 'text_css_content',
      'click sortable' => FALSE,
    ],
  ];
  return $data;
}
