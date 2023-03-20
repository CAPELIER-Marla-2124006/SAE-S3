<?php
abstract class AController {

    protected string $urlFolder;
    protected array $urlParams, $getParams, $postParams;

    /**
     * AController constructor. (is empty because it's an abstract class)
     * @return void
     */
    public function __construct() {}

    /**
     * Initialize the controller by using the url, get and post parameters
     * @param array $urlParams url parameters
     * @param array $getParams get parameters
     * @param array $postParams post parameters
     * @return void
     */
    public function init(array $urlParams, array $getParams, array $postParams): void
	{
        $this->urlFolder = array_shift($urlParams);
        $this->urlParams = $urlParams;
        $this->getParams = $getParams;
        $this->postParams = $postParams;
    }

    /**
     * Process the request by using the url (used to call the right controller)
     * @return void
     */
    public abstract function process();

    /**
     * Call the controller to process the request
     * @param AController $ctr controller to call
     * @return void
     */
    public function callController(AController $ctr): void
	{
        $ctr->init($this->urlParams, $this->getParams, $this->postParams);
        $ctr->process();
    }
}
