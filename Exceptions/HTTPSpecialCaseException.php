<?php

class HTTPSpecialCaseException extends MVCException {

    protected $httpCode;

    public function __construct(string|int $httpCode, string $msg = "")
    {
        parent::__construct($msg);
        $this->httpCode = $httpCode;
    }

    public function getHTTPCode(){
        return $this->httpCode;
    }

}
