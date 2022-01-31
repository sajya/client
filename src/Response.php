<?php

namespace Sajya\Client;

class Response
{
    /**
     * @var \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    protected $response;

    protected $id;
    protected $result;
    protected $error;

    /**
     * @param mixed                                                                 $id
     * @param mixed                                                                 $result
     * @param mixed                                                                 $error
     * @param \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response $response
     */
    public function __construct($id, $result, $error, $response)
    {
        $this->id = $id;
        $this->result = $result;
        $this->error = $error;
        $this->response = $response;
    }

    /**
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function result()
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * @return string|null
     */
    public function id(): ?string
    {
        return $this->id;
    }
}
