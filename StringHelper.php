<?php
/**
 * This helper methods for string
 * @author darkfriend <hi@darkfriend.ru>
 * @version 1.1.0
 */

namespace darkfriend\devhelpers;


class StringHelper
{
  /**
   * Get encoded string
   * @param mixed $val
   * @return array|string
   */
  public static function htmlspecialchars($val)
  {
    if (is_array($val)) {
      $arrReturn = array();
      foreach ($val as $key => $value) {
        $arrReturn[$key] = self::htmlspecialchars($value);
      }
      return $arrReturn;
    } else {
      if (!empty($val)) {
        return htmlspecialchars($val, ENT_QUOTES | ENT_HTML5 | ENT_DISALLOWED | ENT_SUBSTITUTE, 'UTF-8');
      }
    }
    return '';
  }

  /**
   * Get decoded string
   * @param mixed $val
   * @return array|string
   */
  public static function htmlspecialchars_decode($val)
  {
    if (is_array($val)) {
      $arrReturn = array();
      foreach ($val as $key => $value) {
        $arrReturn[$key] = self::htmlspecialchars_decode($value);
      }
      return $arrReturn;
    } else {
      if ($val) {
        return htmlspecialchars_decode($val, ENT_QUOTES | ENT_DISALLOWED | ENT_SUBSTITUTE);
      }
    }
    return '';
  }

  /**
   * Get generated string
   * @param int $length
   * @param string $chars
   * @return string
   */
  public static function generateString($length = 8, $chars = '0123456789ABDEFGHKNQRSTYZ')
  {
    $numChars = strlen($chars);
    $string = '';
    for ($i = 0; $i < $length; $i++) {
      $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }
    return $string;
  }

  /**
   * Возврат окончания слова при склонении.
   * Функция возвращает окончание слова, в зависимости от примененного к ней числа
   * Например: 5 товаров, 1 товар, 3 товара
   *
   * @param int $value число
   * @param array $words массив возможных окончаний
   * @return string
   */
  public static function getDeclension($value = 1, $words = array('', 'а', 'ов'))
  {
    $array = array(2, 0, 1, 1, 1, 2);
    return $words[($value % 100 > 4 && $value % 100 < 20) ? 2 : $array[($value % 10 < 5) ? $value % 10 : 5]];
  }
}