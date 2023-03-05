<?php
abstract class AController {

    protected string $urlFolder;
    protected array $urlParams, $getParams, $postParams;

    public function __construct() {}

    public function init(array $urlParams, array $getParams, array $postParams): void
	{
        $this->urlFolder = array_shift($urlParams);
        $this->urlParams = $urlParams;
        $this->getParams = $getParams;
        $this->postParams = $postParams;
    }

    public abstract function process();

    public function callController(AController $ctr): void
	{
        $ctr->init($this->urlParams, $this->getParams, $this->postParams);
        $ctr->process();
    }
}
