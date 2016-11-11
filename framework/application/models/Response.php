<?php

namespace App;

/**
 * Created by PhpStorm.
 * User: Miguel
 * Date: 20-Oct-16
 * Time: 04:07 PM
 */
class Response implements \JsonSerializable
{
    private $success = false;
    private $message = '';
    private $data = [];

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * An array of custom data that can be sent back
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            'success' => $this->isSuccess(),
            'message' => $this->getMessage(),
            'data' => $this->getData(),
        ];
    }
}