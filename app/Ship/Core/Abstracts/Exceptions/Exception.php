<?php

namespace App\Ship\Core\Abstracts\Exceptions;

use App\Ship\Exceptions\Codes\ErrorCodeManager;
use Config;
use Exception as BaseException;
use Illuminate\Support\MessageBag;
use Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException as SymfonyHttpException;

abstract class Exception extends SymfonyHttpException
{
    /**
     * MessageBag errors.
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Default status code.
     *
     * @var int
     */
    public const DEFAULT_STATUS_CODE = Response::HTTP_INTERNAL_SERVER_ERROR;

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var null
     */
    private $customData = null;

    /**
     * Exception constructor.
     * @param null $message
     * @param null $errors
     * @param null $statusCode
     * @param int $code
     * @param BaseException|null $previous
     * @param array $headers
     */
    public function __construct(
        $message = null,
        $errors = null,
        $statusCode = null,
        $code = 0,
        BaseException $previous = null,
        $headers = []
    ) {
        // detect and set the running environment
        $this->environment = Config::get('app.env');

        $message = $this->prepareMessage($message);
        $error = $this->prepareError($errors);
        $statusCode = $this->prepareStatusCode($statusCode);

        $this->logTheError($statusCode, $message, $code);

        parent::__construct($statusCode, $message, $previous, $headers, $code);

        $this->customData = $this->addCustomData();

        $this->code = $this->evaluateErrorCode();
    }


    /**
     * @param null $message
     *
     * @return  null
     */
    private function prepareMessage($message = null)
    {
        return is_null($message) && property_exists($this, 'message') ? $this->message : $message;
    }

    /**
     * @param array|null $errors
     *
     * @return  MessageBag
     */
    private function prepareError($errors = null)
    {
        return is_null($errors) ? new MessageBag() : $this->prepareArrayError($errors);
    }

    /**
     * @param array $errors
     *
     * @return MessageBag
     */
    private function prepareArrayError(array $errors = [])
    {
        return new MessageBag($errors);
    }

    /**
     * @param integer $statusCode
     *
     * @return integer
     */
    private function prepareStatusCode($statusCode = null): int
    {
        return is_null($statusCode) ? $this->findStatusCode() : $statusCode;
    }

    /**
     * @return  integer
     */
    private function findStatusCode(): int
    {
        return property_exists($this, 'httpStatusCode') ? $this->httpStatusCode : self::DEFAULT_STATUS_CODE;
    }

    /**
     * @param integer $statusCode
     * @param string $message
     * @param integer $code
     */
    private function logTheError(int $statusCode, string $message, int $code): void
    {
        // if not testing environment, log the error message
        if ($this->environment != 'testing') {
            Log::error('[ERROR] ' .
                'Status Code: ' . $statusCode . ' | ' .
                'Message: ' . $message . ' | ' .
                'Errors: ' . $this->errors . ' | ' .
                'Code: ' . $code);
        }
    }

    /**
     * @return null
     */
    protected function addCustomData()
    {
        return null;
    }

    /**
     * Default value
     *
     * @return int
     */
    public function useErrorCode()
    {
        return $this->code;
    }

    /**
     * Overrides the code with the application error code (if set)
     *
     * @return int
     */
    private function evaluateErrorCode()
    {
        $code = $this->useErrorCode();

        return $code;
    }

    /**
     * Get the errors message bag.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Determine if message bag has any errors.
     *
     * @return bool
     */
    public function hasErrors()
    {
        return !$this->errors->isEmpty();
    }
}
