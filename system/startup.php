<?php
// Error Reporting
error_reporting(E_ALL);

// Version
define('VERSION', '2.3.0.3_rc');
define('COPONA_VERSION', 'dev');

// Composer Autoloader
if (is_file(DIR_SYSTEM . '../vendor/autoload.php')) {
    require_once(DIR_SYSTEM . '../vendor/autoload.php');
} else {
    die('Please, execute composer install');
}

//Init Config
$config = new Config(DIR_PUBLIC . '/config');

//default connection
$default_connection = $config->get('database.default_connection') ? $config->get('database.default_connection') : 'default';
$db_config = $config->get('database.' . $default_connection);

define('APPLICATION', basename(realpath('')) == 'admin' ? 'admin' : 'catalog');

//Get port
$server_port = '';
if (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] != 80) && $_SERVER['SERVER_PORT'] != 443) {
    $server_port = ':' . $_SERVER['SERVER_PORT'];
}

//define domain url constant
define('DOMAIN_NAME', isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] . $server_port : null);
$parse_url = parse_url($_SERVER['SCRIPT_NAME']);
define('BASE_URI', str_replace(['index.php', '//'], '', $parse_url['path']));
define('BASE_URL', DOMAIN_NAME . BASE_URI);
define('BASE_URL_CATALOG', (str_replace(['index.php', 'admin', '//'], '', BASE_URL)));

// HTTP
define('HTTP_SERVER', 'http://' . BASE_URL);
define('HTTP_CATALOG', 'http://' . BASE_URL_CATALOG);

// HTTPS
define('HTTPS_SERVER', 'https://' . BASE_URL);
define('HTTPS_CATALOG', 'https://' . BASE_URL_CATALOG);

// DIR
define('DIR_APPLICATION', DIR_PUBLIC . '/catalog/');
define('DIR_SYSTEM', DIR_PUBLIC . '/system/');
define('DIR_IMAGE', DIR_PUBLIC . '/image/');
define('DIR_LANGUAGE', DIR_PUBLIC . '/catalog/language/');
define('DIR_TEMPLATE', DIR_PUBLIC . '/catalog/view/theme/');
define('DIR_CONFIG', DIR_PUBLIC . '/system/config/');
define('DIR_CACHE', DIR_PUBLIC . '/system/storage/cache/');
define('DIR_DOWNLOAD', DIR_PUBLIC . '/system/storage/download/');
define('DIR_LOGS', DIR_PUBLIC . '/system/storage/logs/');
define('DIR_MODIFICATION', DIR_PUBLIC . '/system/storage/modification/');
define('DIR_UPLOAD', DIR_PUBLIC . '/system/storage/upload/');






// Debug helper
require_once(DIR_SYSTEM . 'helper/debug.php');

//Dotenv
$dotenv = new Dotenv\Dotenv(DIR_PUBLIC);
$dotenv->load();

//Errors handler
$whoops = new \Whoops\Run;
if (Whoops\Util\Misc::isAjaxRequest()) { //ajax
    $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler);
} else if (Whoops\Util\Misc::isCommandLine()) { //command line
    $whoops->pushHandler(new \Whoops\Handler\PlainTextHandler);
} else { //html
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
}
$whoops->register();

// Check Version
if (version_compare(phpversion(), '5.6.0', '<') == true) {
    exit('PHP5.6+ Required');
}

if (!ini_get('date.timezone')) {
    date_default_timezone_set('UTC');
}

// Windows IIS Compatibility
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    if (isset($_SERVER['SCRIPT_FILENAME'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    if (isset($_SERVER['PATH_TRANSLATED'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
}

if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);

    if (isset($_SERVER['QUERY_STRING'])) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}

if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}

// Check if SSL
if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
    $_SERVER['HTTPS'] = true;
} else if (!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') {
    $_SERVER['HTTPS'] = true;
} else if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
    $_SERVER['HTTPS'] = true;
} else {
    $_SERVER['HTTPS'] = false;
}

// Universal Host redirect to correct hostname
if (defined('HTTP_HOST') && defined('HTTPS_HOST') && $_SERVER['HTTP_HOST'] != parse_url(HTTPS_SERVER)['host'] && $_SERVER['HTTP_HOST'] != parse_url(HTTP_SERVER)['host']) {
    header("Location: " . ($_SERVER['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER) . ltrim('/', $_SERVER['REQUEST_URI']));
}

// Modification Override
function modification($filename)
{
    if (defined('DIR_CATALOG')) {
        $file = DIR_MODIFICATION . 'admin/' . substr($filename, strlen(DIR_APPLICATION));
    } elseif (defined('DIR_OPENCART')) {
        $file = DIR_MODIFICATION . 'install/' . substr($filename, strlen(DIR_APPLICATION));
    } else {
        $file = DIR_MODIFICATION . 'catalog/' . substr($filename, strlen(DIR_APPLICATION));
    }

    if (substr($filename, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
        $file = DIR_MODIFICATION . 'system/' . substr($filename, strlen(DIR_SYSTEM));
    }

    if (is_file($file)) {
        return $file;
    }

    return $filename;
}

function library($class)
{
    $file = DIR_SYSTEM . 'library/' . str_replace('\\', '/', strtolower($class)) . '.php';

    if (is_file($file)) {
        include_once(modification($file));

        return true;
    } else {
        return false;
    }
}

spl_autoload_register('library');
spl_autoload_extensions('.php');

// Engine
require_once(modification(DIR_SYSTEM . 'engine/action.php'));
require_once(modification(DIR_SYSTEM . 'engine/controller.php'));
require_once(modification(DIR_SYSTEM . 'engine/event.php'));
require_once(modification(DIR_SYSTEM . 'engine/hook.php'));
require_once(modification(DIR_SYSTEM . 'engine/front.php'));
require_once(modification(DIR_SYSTEM . 'engine/loader.php'));
require_once(modification(DIR_SYSTEM . 'engine/model.php'));
require_once(modification(DIR_SYSTEM . 'engine/registry.php'));
require_once(modification(DIR_SYSTEM . 'engine/proxy.php'));

// Helper
require_once(DIR_SYSTEM . 'helper/general.php');
require_once(DIR_SYSTEM . 'helper/text.php');
require_once(DIR_SYSTEM . 'helper/utf8.php');
require_once(DIR_SYSTEM . 'helper/json.php');

function start($application_config)
{
    require_once(DIR_SYSTEM . 'framework.php');
}
