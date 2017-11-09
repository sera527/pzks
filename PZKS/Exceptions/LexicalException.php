<?php
/**
 * Created by PhpStorm.
 * User: sera527
 * Date: 26.10.17
 * Time: 21:51
 */

namespace PZKS\Exceptions;


class LexicalException extends PZKSException
{
    protected $errors;
    protected $string;

    public function __construct($message = "", array $errors = [], $string = "", $code = 0, Throwable $previous = null)
    {
        $this->errors = $errors;
        $this->string = $string;
        parent::__construct($message, $code, $previous);
    }

    static protected function charactersNumber($string)
    {
        $string = str_split($string);
        $table = "<table border=\"1\"><tr>";
        for ($i = 1; $i <= count($string); $i++) {
            $table .= "<td><center>$i</center></td>";
        }
        $table .= "</tr><tr>";
        foreach ($string as $char) {
            $table .= "<td><center>$char</center></td>";
        }
        $table .= "</tr></table>";
        return $table;
    }
}