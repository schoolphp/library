<?php
namespace FW\Pagination;

use \Core;
use http\Exception;

/**
 * Class Paginator
 * @package FW\Pagination
 */
class Paginator
{
    public static $onpage = 10;
    public static $pages = 1;
    public static $curpage = 1;
    public static $start = 0;
    public static $rows = 0;
    public static $options = [
        'begin' => true,
        'end' => true,
        'before' => true,
        'next' => true,
        'trash' => true,
        'move' => 3,
    ];

    /**
     * @param string $query
     * @return \mysqli_result
     */
    public static function q(string $query)
    {
        self::$start = (self::$curpage-1)*self::$onpage;
        if (self::$start < 0) {
            self::$start = 0;
        }

        $res = q(preg_replace('#^\s*SELECT#usU', 'SELECT SQL_CALC_FOUND_ROWS', $query).' LIMIT '.self::$start.','.self::$onpage);
        $res_count = q("SELECT FOUND_ROWS()");
        $row_count = $res_count->fetch_row();
        $res_count->close();

        self::$rows = $row_count;
        self::$pages = ceil($row_count[0]/self::$onpage);

        if (self::$curpage < 1 || (self::$curpage > 1 && self::$curpage > self::$pages)) {
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
            self::$curpage = 1;
        }
        return $res;
    }

    /**
     * Example: \FW\Pagination\Paginator::nav('/admin/tutor/view/{page}', true, __DIR__.'/modules/admin/tutor/view/languages/'.\Core::$LANG.'.php');
     * @param string $url
     * @param bool $add_get
     * @param string $language_url
     * @return string
     */
    public static function nav(string $url, bool $add_get = false, string $language_url = ''): string
    {
        if (is_bool($url) || empty($url)) {
            echo 'Deprecated';
            exit;
        }

        if ($add_get) {
            $tmp = explode('?', $_SERVER['REQUEST_URI'], 2);
            if (isset($tmp[1])) {
                $url .= '?'.$tmp[1];
            }
        }

        if (!empty($language_url) && file_exists($language_url)) {
            $text = include $language_url;
        } elseif (file_exists(__DIR__.'/language/'.Core::$LANGUAGE['lang'].'.php')) {
            $text = include __DIR__.'/language/'.Core::$LANGUAGE['lang'].'.php';
        } else {
            $text = include __DIR__.'/language/ru.php';
        }

        $nav = '<ul class="pagination">';

        if (self::$curpage-self::$options['move'] > 1 && self::$options['begin']) {
            if (self::$options['begin']) {
                $nav .= '<li class="page-item"><a class="page-link" href="'.str_replace('{page}', '1', $url).'">'.$text['begin'].'</a></li>';
            }

            if (self::$options['before']) {
                $nav .= '<li class="page-item"><a class="page-link" href="'.str_replace('{page}', (self::$curpage-self::$options['move'] - 1), $url).'">'.$text['before'].'</a></li>';
            }

            if (self::$options['trash']) {
                $nav .= '<li class="page-item"><span class="page-link">...</span></li>';
            }
        }

        for ($i = self::$curpage-self::$options['move']; $i <= self::$curpage+self::$options['move']; ++$i) {
            if ($i == self::$curpage) {
                $nav .= '<li class="page-item active"><span class="page-link">'.self::$curpage.'</span></li>';
            } elseif ($i > 0 && $i <= self::$pages) {
                $nav .= '<li class="page-item"><a class="page-link" href="'.str_replace('{page}', $i, $url).'">'.$i.'</a></li>';
            }
        }

        if (self::$curpage - 1 > 0 && empty(Core::$META['prev'])) {
            Core::$META['prev'] = str_replace('{page}', (self::$curpage - 1), $url);
        }
        if (self::$curpage + 1 <= self::$pages && empty(Core::$META['next'])) {
            Core::$META['next'] = str_replace('{page}', (self::$curpage + 1), $url);
        }

        if (self::$curpage+self::$options['move'] < self::$pages) {
            if (self::$options['trash']) {
                $nav .= '<li class="page-item"><span class="page-link">...</span></li>';
            }

            if (self::$options['next']) {
                $nav .= '<li class="page-item"><a class="page-link" href="'.str_replace('{page}', (self::$curpage+self::$options['move'] + 1), $url).'">'.$text['next'].'</a></li>';
            }

            if (self::$options['end']) {
                $nav .= '<li class="page-item"><a class="page-link" href="'.str_replace('{page}', self::$pages, $url).'">'.$text['end'].'</a></li>';
            }
        }

        $nav .= '</ul>';
        return $nav;
    }
}
