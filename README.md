# DevHelpers - классы хелперы, которые часто бывают полезны в разработке

**Структура:**
* DebugHelper
  * ```DebugHelper::$mainKey``` - свойство, содержащее имя ключа для $_COOKIE и $_GET
  * ```DebugHelper::print_pre($o,$die,$show)``` - статичный метод, который выводит всю структуру массива и объекта, с информацией о файле и строке (подробности ниже)
  * ```DebugHelper::call($func,...$params)``` - статичный метод, который вызывает переданную функцию только у админа, передавая нужные параметры (подробности ниже)
  * ```DebugHelper::trace($message,$category='common')``` - статичный метод трессировки (ниже примеры использования)
* StringHelper
  * ```StringHelper::htmlspecialchars($val)``` - статичный метод, который делает htmlspecialchars() для строк и массивов
  * ```StringHelper::htmlspecialchars_decode($val)``` - статичный метод, который делает htmlspecialchars_decode() для строк и массивов
  * ```StringHelper::generateString($length,$chars)``` - статичный метод, который возвращает сгенерированную строку нужной длины
  * ```StringHelper::getDeclension($value,$words)``` - статичный метод, который возвращает окончания слов при слонении. _Например: 5 товаров, 1 товар, 3 товара_
  
* ArrayHelper
  * ``ArrayHelper::in_array($needle, $haystack)`` - highload method for search value in array
  * ``ArrayHelper::isMulti($arr)`` - check array on multiple array
  * `` ArrayHelper::sortValuesToArray($sourceArray,$orderArray)`` - Sort values array to order array
  * `` ArrayHelper::sortKeysToArray($sourceArray,$orderArray)`` - Sort keys source array to order array
  
## DebugHelper::print_pre($o,$die,$show);
* $o - данные, которые надо вывести
* $die - прерывать ли после вывода выполнение скрипта (по умолчанию false)
* $show - выводить всем [или только в определенных случаях] (по умолчанию true)

### Пример
```php
use \darkfriend\devhelpers\DebugHelper;
$data = [
  'key1' => 'value1',
  'key2' => 'value2',
  'key3' => [
    'subKey1' => 'subValue1',
    'subKey2' => 'subValue2',
  ],
];
DebugHelper::print_pre($data);
```

## DebugHelper::call($func,...$params)
* $func - функция, которую надо вывести
* $params - параметры которые надо передать

### Пример
```php
use \darkfriend\devhelpers\DebugHelper;
$data = [
  'key1' => 'value1',
  'key2' => 'value2',
  'key3' => [
    'subKey1' => 'subValue1',
    'subKey2' => 'subValue2',
  ],
];

// способ 1: используя $params
DebugHelper::call(function($data) {
  DebugHelper::print_pre($data);
},$data);

// способ 2: используя use
DebugHelper::call(function() use ($data) {
  DebugHelper::print_pre($data);
});
```
## DebugHelper::trace($message,$category)
* $message - сообщение
* $category - категория трассировки

### Дополнительные возможности

* поддержка режимов трассировния
    * TRACE_MODE_REPLACE - режим перезаписи лога
    * TRACE_MODE_APPEND - режим дополнение лога
    * TRACE_MODE_SESSION - режим trace-сессии
* поддержка trace-сессий - каждый запуск в отдельном 


### Example 1: простая трассировка
_Задача: Простая запись данных в лог_
```php
use \darkfriend\devhelpers\DebugHelper;
$array1 = [
  'key1' => 'value1',
  'key2' => 'value2'
];

// trace 1
DebugHelper::trace($array1);
// итог: запишет $array1 с категорией common.

$array1['key3'] = [
  'subKey1' => 'subValue1',
  'subKey2' => 'subValue2',
];

// trace 2
DebugHelper::trace($array1);
// итог: допишет в лог обновленный $array1 с категорией common
```

#### Example 1: FAQ

* _Где лежит файл?_ - путь ``$_SERVER['DOCUMENT_ROOT].'/trace.log'``
* _Что будет в логе?_ - будет 2 записи переменной $array1. По умолчанию идет запись лога сверху вниз
* _Какая категория будет?_ - по умолчанию категория "common"

### Example 2: каждый запуск в отдельный файл
_Задача: Мы сохраняем данные и хотим трассировать id-строки и сохраняемые данные_

```php
use \darkfriend\devhelpers\DebugHelper;

$id = 1; // идентификатор

// делаем инициализацию
// $id - ключ trace-session
// self::TRACE_MODE_SESSION - включаем режим trace-session
DebugHelper::traceInit($id, DebugHelper::TRACE_MODE_SESSION);

$array1 = [
  'key1' => 'value1',
  'key2' => 'value2',
  'key3' => 'value3'
];

DebugHelper::trace($array1);
// итог: запишет $array1 с категорией common.

$array1['key3'] = [
  'subKey1' => 'subValue1',
  'subKey2' => 'subValue2',
];

// trace 2
DebugHelper::trace($array1);
// итог: допишет в лог обновленный $array1 с категорией common
```

#### Example 2: FAQ

* _Где лежит файл?_ - путь ``$_SERVER['DOCUMENT_ROOT]."/{$id}-trace.log"``
* _Что будет в логе?_ - будет 2 записи переменной $array1. По умолчанию идет запись лога сверху вниз
* _Какая категория будет?_ - по умолчанию категория "common"
* _Как изменить путь до лога?_ - по умолчанию лог создается в корне, чтоб его изменить, нужно передать путь от корня в 3-ий параметр метода DebugHelper::traceInit(). Пример: ``DebugHelper::traceInit($id, self::TRACE_MODE_SESSION,'/logs')``
* _Могу ли я для одного trace сделать один файл, для другого - другой?_ - да, нужно в нужный момент вызвать метод ``DebugHelper::setHashSession($hash)``, где $hash - это любой ключ.