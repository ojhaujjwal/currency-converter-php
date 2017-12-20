<?php

namespace CurrencyConverter\Exception;

class UnsupportedCurrencyException extends InvalidArgumentException implements ExceptionInterface
{
    const UNSUPPORTED_CURRENCY_MSG = 'Undefined rate for "%s" currency.';
}
