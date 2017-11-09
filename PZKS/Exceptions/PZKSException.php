<?php
/**
 * Created by PhpStorm.
 * User: sera527
 * Date: 26.10.17
 * Time: 21:47
 */

namespace PZKS\Exceptions;

use Exception;
use Throwable;

class PZKSException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}