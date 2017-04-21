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
    private $success = true;

    /**
     * Should the request activate the notification button in case of an error?
     * usually used in controlled Exceptions
     * @var bool
     */
    private $notify = false;

    /**
     * The message that the user will see
     *
     * @var string
     */
    private $message = '';

    /**
     * Data to send
     *
     * @var array
     */
    private $data = [];

    /**
     * Type of bootstrap message to show in the frontend: success, info, warning, danger
     *
     * @var string
     */
    private $type = 'success';

    private $statusHeader = 200;

    /**
     * @param int $statusHeader
     */
    public function setStatusHeader($statusHeader)
    {
        $this->statusHeader = $statusHeader;
    }

    /**
     * @return int
     */
    public function getStatusHeader()
    {
        return $this->statusHeader;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param bool $notify
     */
    public function setNotify($notify)
    {
        $this->notify = $notify;
    }

    /**
     * @return bool
     */
    public function isNotify()
    {
        return $this->notify;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Format the error messages
     *
     * @param $message
     * @param \Exception $exception
     */
    public function setError($message, \Exception $exception){

        $CI = &get_instance();

        $data = [
            'type' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];

        $this->setStatusHeader(500);
        $this->setNotify(true);
        $this->setData($data);
        $this->setSuccess(false);
        $this->setType('danger');

        $this->message = $message;

    }

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->setType('success');
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
            'notify' => $this->isNotify(),
            'data' => $this->getData(),
            'type' => $this->getType(),
        ];
    }
}