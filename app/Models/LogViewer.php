<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

/**
 * Class LogViewer.
 */
class LogViewer
{
    /**
     * @var string file
     */
    private static $file;

    /**
     * Map debug levels to Bootstrap classes.
     *
     * @var array
     */
    private static $levels_classes = [
        'debug'     => 'info',
        'info'      => 'info',
        'notice'    => 'info',
        'warning'   => 'warning',
        'error'     => 'danger',
        'critical'  => 'danger',
        'alert'     => 'danger',
        'emergency' => 'danger',
        'processed' => 'info',
    ];

    /**
     * Map debug levels to icon classes.
     *
     * @var array
     */
    private static $levels_imgs = [
        'debug'     => 'info',
        'info'      => 'info',
        'notice'    => 'info',
        'warning'   => 'warning',
        'error'     => 'warning',
        'critical'  => 'warning',
        'alert'     => 'warning',
        'emergency' => 'warning',
        'processed' => 'info',
    ];

    /**
     * Log levels that are used.
     *
     * @var array
     */
    private static $log_levels = [
        'emergency',
        'alert',
        'critical',
        'error',
        'warning',
        'notice',
        'info',
        'debug',
        'processed',
    ];

    /**
     * Arbitrary max file size.
     */
    const MAX_FILE_SIZE = 52428800;

    /**
     * @param string $file
     *
     * @throws \Exception
     */
    public static function setFile($file)
    {
        if (File::exists(storage_path('logs/'.$file))) {
            static::$file = $file;
        }
    }

    /**
     * @param string $file
     *
     * @throws
     *
     * @return string
     */
    public static function pathToLogFile($file)
    {
        if (File::exists(storage_path('logs/'.$file))) { // try the absolute path
            return storage_path('logs/'.$file);
        }
        throw new \Exception('No such log file');
    }

    /**
     * @return string
     */
    public static function getFileName()
    {
        return basename(File::get(storage_path('logs/'.static::$file)));
    }

    /**
     * @return array
     */
    public static function all()
    {
        $log = [];

        if (!static::$file) {
            $log_file = static::getFiles();
            if (!count($log_file)) {
                return [];
            }
            static::$file = $log_file[0];
        }

        if (File::size(storage_path('logs/'.static::$file)) > static::MAX_FILE_SIZE) {
            return [];
        }
        $file = File::get(storage_path('logs/'.static::$file));

        $pattern = '/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*/';

        preg_match_all($pattern, $file, $headings);

        if (!is_array($headings)) {
            return $log;
        }

        $stack_trace = preg_split($pattern, $file);

        if ($stack_trace[0] < 1) {
            array_shift($stack_trace);
        }

        foreach ($headings as $h) {
            for ($i = 0, $j = count($h); $i < $j; $i++) {
                foreach (static::$log_levels as $level) {
                    if (strpos(strtolower($h[$i]), '.'.$level) || strpos(strtolower($h[$i]), $level.':')) {
                        $pattern = '/^\[(?P<date>(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}))\](?:.*?(?P<context>(\w+))\.|.*?)'.$level.': (?P<text>.*?)(?P<in_file> in .*?:[0-9]+)?$/i';
                        preg_match($pattern, $h[$i], $current);
                        if (!isset($current['text'])) {
                            continue;
                        }

                        $log[] = [
                            'context'     => $current['context'],
                            'level'       => $level,
                            'level_class' => static::$levels_classes[$level],
                            'level_img'   => static::$levels_imgs[$level],
                            'date'        => $current['date'],
                            'text'        => $current['text'],
                            'in_file'     => isset($current['in_file']) ? $current['in_file'] : null,
                            'stack'       => preg_replace("/^\n*/", '', $stack_trace[$i]),
                        ];
                    }
                }
            }
        }

        return array_reverse($log);
    }

    /**
     * @return array
     */
    public static function getFiles()
    {
        $files = self::getTree();
        sort($files);
        if (is_array($files)) {
            foreach ($files as $k => $file) {
                if (File::exists(storage_path('logs/'.$file))) {
                    $files[$k] = [
                        'file_name'     => $file,
                        'file_size'     => File::size(storage_path('logs/'.$file)),
                        'last_modified' => File::lastModified(storage_path('logs/'.$file)),
                    ];
                }
            }
        }
        return array_values($files);
    }

    private static function getTree($path = '', $branch= [])
    {
        foreach (File::files(storage_path('logs/'.$path)) as $file) {
            if ($file->getExtension() == 'log') {
                $branch[] = ($path ? ($path.'/') : '').$file->getRelativePathname();
            }
        }
        foreach (File::directories(storage_path('logs/'.$path)) as $directory) {
            $dir = Arr::last(explode('storage/logs/', $directory));
            $branch = self::getTree($dir, $branch);
        }
        return $branch;
    }
}
