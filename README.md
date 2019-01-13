# DevHelpers - классы хелперы, которые часто бывают полезны в разработке

**Структура:**
* DebugHelper
  * ```DebugHelper::$mainKey``` - свойство, содержащее имя ключа для $_COOKIE и $_GET
  * ```DebugHelper::print_pre($o,$die,$show)``` - статичный метод, который выводит всю структуру массива и объекта, с информацией о файле и строке (подробности ниже)
  * ```DebugHelper::call($func,...$params)``` - статичный метод, который вызывает переданную функцию только у админа, передавая нужные параметры (подробности ниже)
* StringHelper
  * ```StringHelper::htmlspecialchars($val)``` - статичный метод, который делает htmlspecialchars() для строк и массивов
  * ```StringHelper::htmlspecialchars_decode($val)``` - статичный метод, который делает htmlspecialchars_decode() для строк и массивов
  * ```StringHelper::generateString($length,$chars)``` - статичный метод, который возвращает сгенерированную строку нужной длины
  * ```StringHelper::getDeclension($value,$words)``` - статичный метод, который возвращает окончания слов при слонении. _Например: 5 товаров, 1 товар, 3 товара_
  
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
