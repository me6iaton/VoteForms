<?php

$settings = array();

$tmp = array(
  'core_path' => array(
    'xtype' => 'textfield',
    'value' => PKG_CORE_PATH,
    'area' => 'voteforms.main',
  ),
  'assets_url' => array(
    'xtype' => 'textfield',
    'value' => PKG_ASSETS_URL,
    'area' => 'voteforms.main',
  ),
);

foreach ($tmp as $k => $v) {
  /* @var modSystemSetting $setting */
  $setting = $modx->newObject('modSystemSetting');
  $setting->fromArray(array_merge(
    array(
      'key' => 'voteforms_' . $k,
      'namespace' => PKG_NAME_LOWER,
    ), $v
  ), '', true, true);

  $settings[] = $setting;
}

unset($tmp);
return $settings;
