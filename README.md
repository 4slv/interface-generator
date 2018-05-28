# interface-generator
Генератор кода интерфейса позволяет генерировать php-код интерфейса.

Пример использования:
```php
use InterfaceGenerator\InterfaceGenerator;
use InterfaceGenerator\InterfaceMethod;

$interfaceGenerator = new InterfaceGenerator();
$interfaceContent = $interfaceGenerator
    ->setNamespace('Bank')
    ->setInterfaceName('BankInterface')
    ->setInterfaceComment('Интерфейс банка')
    ->setInterfaceMethodList($interfaceMethodList)
    ->getInterfaceContent();
```
, где $interfaceMethodList - список объектов типа InterfaceMethod

$interfaceContent будет содержать код интерфейса:
```php
<?php
namespace Bank;

use Request\Money;

/** Интерфейс банка */
interface BankInterface
{
    /**
     * Взять кредит
     * @param Money $requestedAmount запрошенная сумма
     * @param int $requestedPeriod запрошенный период
     * @return Money деньги
     */
    function getCredit(Money $requestedAmount, int $requestedPeriod): Bank\Money;
    /**
     * Открыть вклад
     * @param Investment\Money $investedAmount вкладываемая сумма
     * @param int $requestedPeriod запрошенный период
     * @return boolean true - банк принял деньги, false - нет
     */
    function openDeposit(Investment\Money $investedAmount, int $requestedPeriod): boolean;
}
```

Больше подробностей можно найти в 
[тесте](https://github.com/4slv/interface-generator/blob/master/tests/InterfaceGeneratorTest.php)
