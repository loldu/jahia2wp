<?php

$importer_plugin_file = WP_PLUGIN_DIR .
                      '/wordpress-importer/wordpress-importer.php';
if (! is_file($importer_plugin_file)) {
    ?>

File <?php echo $importer_plugin_file; ?> Not found

Please install the wordpress-importer plugin, e.g.

  wp plugin install wordpress-importer

<?php
    die();
}

if (function_exists("wordpress_importer_init")) {
    ?>

Please run with wp-importer plugin skipped (add
--skip-plugins=wordpress-importer to your command line)

<?php
    die();
}

global $argv;
$filename = end($argv); reset($argv);
if (! is_file($filename)) {

    ?>
File not found: <?php echo $filename; ?>

Usage : wp eval [...] <filename>

<?php
    die();
}

// Must set WP_LOAD_IMPORTERS before loading the plugin:
define('WP_LOAD_IMPORTERS', true);
require("$importer_plugin_file");

global $wp_import;
wordpress_importer_init();

$wp_import->fetch_attachments = false;

function html2text ($html) {
    $html = preg_replace("|<br[ ]*[/]?>|", "\n", $html);
    $html = preg_replace("|<[/]?p[ ]*[/]?>|", "\n", $html);
    $html = preg_replace("|<[^>]*>|", "\n", $html);

    $html = str_replace("&#8220;", '"', $html);
    $html = str_replace("&#8221;", '"', $html);

    return $html;
}

ob_start(function ($buf, $phase) {
    static $accumulator = "";
    $accumulator .= $buf;
    $accumulator = html2text($accumulator);
    [$out, $accumulator] = explode("<", $accumulator);
    return $out;
}, 1);
try {
    $wp_import->import($filename);
} finally {
    ob_flush();
    ob_end_clean();
}
?>

****** IMPORT COMPLETE *******
