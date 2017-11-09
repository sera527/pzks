<?php
/**
 * Created by PhpStorm.
 * User: sera527
 * Date: 26.10.17
 * Time: 21:53
 */

namespace PZKS\Exceptions;


class NoticeLexicalException extends LexicalException
{
    public function __toString()
    {
        $message = "<div class=\"alert alert-warning\" role=\"alert\">У виразі знайдено помилку <b>".$this->message."</b>, яку було виправлено автоматично.";
        if($this->errors) {
            $message .= "<br>Список помилок у виразі ".static::charactersNumber($this->string)."<br>";
            foreach ($this->errors[0] as $error) {
                $a = (strlen($error[0]) > 1) ? "-".($error[1]+strlen($error[0])) : "";
                $message .= "<code>".static::spaceTransform($error[0])."</code> позиція ".($error[1]+1)."".$a."<br>";
            }
        }
        $message .= "</div>";
        return $message;
    }

    static protected function spaceTransform($char)
    {
        if ($char == " ") {
            return "пробіл";
        }
        return $char;
    }
}