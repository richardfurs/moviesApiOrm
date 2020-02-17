<?php


namespace App\Api;


class Response
{
    /**
     * @var
     */
    protected $response;
    /**
     * @var
     */
    protected $error;

    /**
     * Response constructor.
     * @param $response
     */
    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * Set up error if exists
     *
     * @return bool
     */
    public function haveError() {
        $body = json_decode($this->response->getBody());
        if(isset($body->Error)) {
            $this->error = $body->Error;
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getError() {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getResponse() {
        return json_decode($this->response->getBody());
    }

}
