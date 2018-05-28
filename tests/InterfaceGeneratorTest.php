<?php

use PHPUnit\Framework\TestCase;
use InterfaceGenerator\InterfaceGenerator;
use InterfaceGenerator\InterfaceMethod;
use InterfaceGenerator\InterfaceMethodParameter;

class InterfaceGeneratorTest extends TestCase
{

    public function getInterfaceContentDataProvider()
    {

        $requestedAmountParameter = new InterfaceMethodParameter();
        $requestedAmountParameter
            ->setName('requestedAmount')
            ->setComment('запрошенная сумма')
            ->setFullType('Request\Money');

        $requestedPeriodParameter = new InterfaceMethodParameter();
        $requestedPeriodParameter
            ->setName('requestedPeriod')
            ->setComment('запрошенный период')
            ->setFullType('int');

        $getCreditParameterList = [
            $requestedAmountParameter,
            $requestedPeriodParameter
        ];

        $getCreditMethod = new InterfaceMethod();
        $getCreditMethod
            ->setName('getCredit')
            ->setComment('Взять кредит')
            ->setReturnFullType('Bank\Money')
            ->setReturnComment('деньги')
            ->setParameterList($getCreditParameterList);

        $investedAmount = new InterfaceMethodParameter();
        $investedAmount
            ->setName('investedAmount')
            ->setComment('вкладываемая сумма')
            ->setFullType('Investment\Money');

        $openDepositParameterList = [
            $investedAmount,
            $requestedPeriodParameter
        ];

        $openDepositMethod = new InterfaceMethod();
        $openDepositMethod
            ->setName('openDeposit')
            ->setComment('Открыть вклад')
            ->setReturnFullType('boolean')
            ->setReturnComment('true - банк принял деньги, false - нет')
            ->setParameterList($openDepositParameterList);

        return [
            [
                [$getCreditMethod, $openDepositMethod]
            ]
        ];
    }

    /**
     * @param InterfaceMethod[] $interfaceMethodList
     * @dataProvider getInterfaceContentDataProvider
     */
    public function testGetInterfaceContent($interfaceMethodList)
    {
        $interfaceGenerator = new InterfaceGenerator();
        $interfaceContent = $interfaceGenerator
            ->setNamespace('Bank')
            ->setInterfaceName('BankInterface')
            ->setInterfaceComment('Интерфейс банка')
            ->setInterfaceMethodList($interfaceMethodList)
            ->getInterfaceContent();

        $this->assertEquals(
            file_get_contents(__DIR__. DIRECTORY_SEPARATOR. 'expectedInterface.php'),
            $interfaceContent
        );
    }
}