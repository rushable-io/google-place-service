<?php

namespace Rushable\GooglePlace\Exceptions;

use Exception;

class RequestDeniedException extends Exception
{
    protected $status;
    protected $service;

    public function __construct($message = "", $status = "", $service = "")
    {
        $this->status = $status;
        $this->service = $service;
        parent::__construct("$status: $service - $message");
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getService(): string
    {
        return $this->service;
    }
}
