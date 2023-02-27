<?php
class Entry {

    private array $urlParams, $getParams, $postParams;

    public function __construct(string $url, Array $getParams, Array $postParams){

        // process url into urlParams
        if ('/' == substr($url, -1, 1)) {
            $url = substr($url, 0, strlen($url) - 1);
        }

        if ('/' == substr($url, 0, 1)) {
            $url = substr($url, 1, strlen($url));
        }

        $this->urlParams = explode('/', $url);
        $this->getParams = $getParams;
        $this->postParams = $postParams;
    }

    public function execute(){
        $O_baseCtr = new RootController();
        $O_baseCtr->init($this->urlParams, $this->getParams, $this->postParams);

        $I_err_httpCode = null;
        $S_err_msg = null;

        try{
            $O_baseCtr->process();
        }catch(HTTPSpecialCaseException $O_except){
            $I_err_httpCode = $O_except->getHTTPCode();
            $S_err_msg = $O_except->getMessage();
        }catch(MVCException $O_except){
            $I_err_httpCode = 500;
            $S_err_msg = $O_except->getMessage();
        }


        if($I_err_httpCode !== null){
            // do not disable redirects
            if(http_response_code() !== 302) {
                http_response_code($I_err_httpCode);
            }

            // Make the user see the error in these cases
            if($I_err_httpCode === 500 || $I_err_httpCode === 400) {
                header_remove("Location");
            }

            View::show("error", array("CODE" => $I_err_httpCode, "MSG" => $S_err_msg));
        }
    }
}
