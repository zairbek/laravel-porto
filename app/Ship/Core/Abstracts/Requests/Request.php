<?php

namespace App\Ship\Core\Abstracts\Requests;

use App\Ship\Core\Abstracts\Transporters\Transporter;
use App\Ship\Core\Exceptions\UndefinedTransporterException;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;

abstract class Request extends LaravelFormRequest
{

    /**
     * Returns the Transporter (if correctly set)
     *
     * @return string
     * @throws UndefinedTransporterException
     */
    public function getTransporter(): string
    {
        if (is_null($this->transporter)) {
            throw new UndefinedTransporterException();
        }

        return $this->transporter;
    }

    /**
     * Transforms the Request into a specified Transporter class.
     *
     * @return Transporter
     */
    public function toTransporter()
    {
        $transporterClass = $this->getTransporter();

        /** @var Transporter $transporter */
        $transporter = new $transporterClass($this);
        $transporter->setInstance('request', $this);

        return $transporter;
    }
}
