<?php
/**
 * This files sets up the translations strings for this theme
 * assuming the website uses the Polylang plugin
 */

// source : https://polylang.pro/doc/function-reference/#pll_register_string

add_action('init', function() {

  if (!function_exists('pll_register_string')) return;

  // define strings to be translated
  $strings = [
    'ui' => [['suivez-nous', 'Suivez-nous']],
    'forms' => ['Prénom', 'Nom', 'Date de naissance', 'Code postal'],
  ];
  pll_register_string('suivez-nous', 'Suivez-nous', 'ui', false);
  
  // actually register strings in polylang plugin
  foreach ($strings as $cat => $vals) {
    foreach ($vals as $val) {
        // pll_register_string( $sorting_name, $string_to_be_translated, $group, $multiline );
        if (is_array($val)) pll_register_string($val[0], $val[1], $cat, false);  
        else pll_register_string($val, $val, $cat, false);  
      }
    }
  });

?>