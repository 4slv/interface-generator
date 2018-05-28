# interface-generator
Генератор кода интерфейса позволяет генерировать php-код интерфейса.

Например:
```php
$interfaceGenerator = new InterfaceGenerator();
$interfaceContent = $interfaceGenerator
    ->setNamespace('Bank')
    ->setInterfaceName('BankInterface')
    ->setInterfaceComment('Интерфейс банка')
    ->setInterfaceMethodList($interfaceMethodList)
    ->getInterfaceContent();
```
