<?php
/**
 * Created by PhpStorm.
 * User: sera527
 * Date: 26.10.17
 * Time: 21:53
 */

namespace PZKS\Exceptions;


class ErrorLexicalException extends LexicalException
{
    public function __toString()
    {
        $message = "<div class=\"alert alert-danger\" role=\"alert\">У виразі знайдено помилку <b>".$this->message."</b>, яку потрібно виправити вручну.";
        if($this->errors) {
            $message .= "<br>Список помилок у виразі ".static::charactersNumber($this->string)."<br>";
            foreach ($this->errors[0] as $error) {
                $a = (strlen($error[0]) > 1) ? "-".($error[1]+strlen($error[0])) : "";
                $message .= "<code>".$error[0]."</code> позиція ".($error[1]+1)."".$a."<br>";
            }
        }
        $message .= "</div>";
        return $message;
    }
}