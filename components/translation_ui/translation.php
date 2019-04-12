<?php 
require_once(CCN_LIBRARY_PLUGIN_DIR . '/lib.php'); use \ccn\lib as lib;

if (is_user_logged_in() && current_user_can('edit_posts')) {

// get info on user
$user = wp_get_current_user();
$roles = ( array ) $user->roles;

// init
lib\php_console_log('=== USER LOGGED IN ===', 'log', 'color:blue;font-weight:bold;');
lib\php_console_log('user roles :'.json_encode($roles));

wp_enqueue_style('wtp2019-translation-style');
wp_enqueue_script('wtp2019-translation-script');
?>

<button id="activate_translation" wtp-editable="false" onclick="toggle_translation()">
    <i class="fas fa-edit" wtp-editable="false"></i> <?php _e('Activer le mode édition', 'ccnbtc'); ?>
</button>

<?php } ?>