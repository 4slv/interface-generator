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