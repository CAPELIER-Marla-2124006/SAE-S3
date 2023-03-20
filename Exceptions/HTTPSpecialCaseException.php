<?php

class HTTPSpecialCaseException extends MVCException {

    protected string|int $httpCode;

/**
 * HTTPSpecialCaseException constructor.
 * @param string|int $httpCode http code of the exception
 * @param string $msg message of the exception ("" by default)
 */
    public function __construct(string|int $httpCode, string $msg = "")
    {
        parent::__construct($msg);
        $this->httpCode = $httpCode;
    }

/**
 * Get the http code of the exception
 * @return string|int http code of the exception
 */
    public function getHTTPCode(): int|string
	{
        return $this->httpCode;
    }

}
