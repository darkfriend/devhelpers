<?php
/**
 * @author darkfriend <hi@darkfriend.ru>
 * @version 1.0.1
 */

namespace darkfriend\devhelpers;

class DebugHelper
{
  public static $mainKey = 'ADMIN';

  /**
   * Output formatted <pre>
   * @param array $o
   * @param bool $die stop application after output
   * @param bool $show all output or output only $_COOKIE['ADMIN']
   * @return void
   */
  public static function print_pre($o, $die = false, $show = true)
  {
    $bt = debug_backtrace();
    $bt = $bt[0];
    $dRoot = $_SERVER["DOCUMENT_ROOT"];
    $dRoot = str_replace("/", "\\", $dRoot);
    $bt["file"] = str_replace($dRoot, "", $bt["file"]);
    $dRoot = str_replace("\\", "/", $dRoot);
    $bt["file"] = str_replace($dRoot, "", $bt["file"]);
    if (!$show && !empty($_COOKIE[self::$mainKey])) $show = true;
    if (!$show) return;
    ?>
      <div style='font-size:9pt; color:#000; background:#fff; border:1px dashed #000;'>
          <div style='padding:3px 5px; background:#99CCFF; font-weight:bold;'>File: <?= $bt["file"] ?>
              [<?= $bt["line"] ?>]
          </div>
          <pre style='padding:10px;'><?php echo htmlentities(print_r($o, true)) ?></pre>
      </div>
    <?php
    if ($die) die();
  }

  /**
   * Call function for only developers
   * @param callable $func
   * @param mixed ...$params
   * @return void
   */
  public static function call(callable $func, ...$params)
  {
    $show = isset($_COOKIE[self::$mainKey]);
    if (!$show) $show = isset($_GET[self::$mainKey]);
    if ($show) $func($params);
  }
}