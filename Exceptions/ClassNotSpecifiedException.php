<?php

namespace Modules\Crud\Exceptions;


class ClassNotSpecifiedException extends \LogicException
{
    protected $message = 'Не указан класс.';

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?: $this->message, $code, $previous);
    }
}