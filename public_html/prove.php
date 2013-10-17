<?php

if ($GLOBALS['_SERVER'] != NULL && array_key_exists('SCRIPT_NAME', $GLOBALS['_SERVER'])) {
    $script_name = $GLOBALS['_SERVER']['SCRIPT_NAME'];
}

$GLOBALS['_SERVER']['SCRIPT_NAME'] = '-';

require_once join(DIRECTORY_SEPARATOR, Array(__DIR__, '..', 'local', 'phpunit.phar'));

if (!empty($script_name)) {
    $GLOBALS['_SERVER']['SCRIPT_NAME'] = $script_name;
}

define('TEST_DIR', join(DIRECTORY_SEPARATOR, Array(__DIR__, '..', 't')));
define('TEST_EXT', 't');

header("Content-type: text/plain; charset=utf-8");

$tests = getTestList(TEST_DIR, TEST_EXT);

foreach ($tests as $test) {
    print "$test\n";

    require_once $test;

    $class = getTestClass($test);
    $suite = new PHPUnit_Framework_TestSuite($class);

    $printer = new PHPUnit_Util_Log_TAP();
    $result = PHPUnit_TextUI_TestRUnner::run($suite, Array('printer' => $printer));

    if ($result->failureCount() > 0) {
        header("HTTP/1.1 500");
    }

    print "\n";
}

function getTestList($dir, $ext) {
    $tests = Array();

    if (! $handle = opendir($dir) ) {
        return $tests;
    }

    while (false !== ($entry = readdir($handle))) {
        if ($entry == '.' or $entry == '..') {
            continue;
        } else {
            $entry = $dir . DIRECTORY_SEPARATOR . $entry;

            if (is_dir($entry)) {
                $tests += getTestList($entry, $ext);
            } else {
                $info = pathinfo($entry);
                if (array_key_exists('extension', $info) && $info['extension'] == $ext) {
                    $tests[] = $entry;
                }
            }

        }
    }

    return $tests;
}

function getTestClass($path) {
    $info = pathinfo($path);
    $filename = $info['filename'];

    # hoge_fuga_piyo.t => HogeFugaPiyoTest
    $class = implode('', array_map('ucfirst', explode('_', $filename))) . 'Test';

    return $class;
}
