<?php

class HTTPSpecialCaseException extends MVCException {

    protected string|int $httpCode;

    public function __construct(string|int $httpCode, string $msg = "")
    {
        parent::__construct($msg);
        $this->httpCode = $httpCode;
    }

    public function getHTTPCode(): int|string
	{
        return $this->httpCode;
    }

}
