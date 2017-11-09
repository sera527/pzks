<?php
/**
 * Created by PhpStorm.
 * User: sera527
 * Date: 26.10.17
 * Time: 23:48
 */

namespace PZKS;

use PZKS\Exceptions\NoticeLexicalException;
use PZKS\Exceptions\ErrorLexicalException;

class LexicalValidator
{
    private $expression;
    private $initialExpression;
    private $valid = true;

    public function __construct($expression)
    {
        $this->expression = $expression;
        $this->initialExpression = $expression;

        echo "<!DOCTYPE html>
<html lang=\"uk\">
<head>
    <meta charset=\"UTF-8\">
    <title>Syntax Validator</title><link rel=\"stylesheet\" href=\"css/bootstrap.css\">
</head>
<body>
    <div class=\"container\">
        <div class=\"alert alert-success\" role=\"alert\"><b>Початковий вираз:</b><br>$this->expression</div>";

        $this->removeOtherCharacters();
        $this->commasToDots();
        $this->removeUnexpectedCharacters();
        $this->searchEmptyBrackets();
        $this->addMultiplicationCharacters();
        $this->removeSimilarSigns();
        $this->searchCriticalErrors();
        $this->searchSeveralSigns();
        $this->searchNotDigitsBeforeAndAfterDots();
        $this->bracketsAnalyze();
        $this->searchSeveralDotsInOneNumber();
        echo "<div class=\"alert alert-info\" role=\"alert\"><b>Результат перевірки:</b><br><code>$this->initialExpression</code> => <code>$this->expression</code></div>";
        echo "<a href='index.php?expression=".urlencode($this->expression)."'>Знову перевірити кінцевий вираз</a>
<br>
<a href='index.php'>Ввести новий вираз</a>";
        if($this->valid) {
            echo "<p>У виразі не виявлено критичних помилок. Арифметичний вираз можна розпаралелити.</p>
<a href='parallelization.php?expression=".urlencode($this->expression)."' class='btn btn-primary center-block'>Розпаралелити вираз</a>";
        }
        echo "</div>
</body>
</html>";

    }

    private function removeOtherCharacters()
    {
        try {
            if (!preg_match('/^[A-Za-z0-9\-\/+*.,()]+$/', $this->expression)) {
                preg_match_all('/[^A-Za-z0-9\-\/+*.,()]/', $this->expression, $notices, PREG_OFFSET_CAPTURE);
                throw new NoticeLexicalException("символи, що не є буквами та цифрами та не належать до наступного списку: ( ) + - * / . ,", $notices, $this->expression);
            }
        } catch (NoticeLexicalException $e) {
            echo $e->__toString();
            $this->expression = preg_replace ("/[^A-Za-z0-9\-\/+*.,()]/","",$this->expression);
        }
    }

    private function commasToDots()
    {
        try {
            if (preg_match('/[,]/', $this->expression)) {
                preg_match_all('/[,]/', $this->expression, $notices, PREG_OFFSET_CAPTURE);
                throw new NoticeLexicalException("перетворення всіх ком у крапки для зручності", $notices, $this->expression);
            }
        } catch (NoticeLexicalException $e) {
            echo $e->__toString();
            $this->expression = str_replace(",",".", $this->expression);
        }
    }

    private function addMultiplicationCharacters()
    {
        try {
            if (
                preg_match('/([A-Za-z0-9])[(]/', $this->expression) ||
                preg_match('/[)]([A-Za-z0-9])/', $this->expression) ||
                preg_match('/[)][(]/', $this->expression)
            ) {
                preg_match_all('/([A-Za-z0-9])[(]/', $this->expression, $notices0, PREG_OFFSET_CAPTURE);
                preg_match_all('/[)]([A-Za-z0-9])/', $this->expression, $notices1, PREG_OFFSET_CAPTURE);
                preg_match_all('/[)][(]/', $this->expression, $notices2, PREG_OFFSET_CAPTURE);
                $notices = array_merge ($notices0[0], $notices1[0], $notices2[0]);
                usort($notices, "cmp");
                throw new ErrorLexicalException("відсутність арифметичного знаку там, де він передбачався", [$notices], $this->expression);
            }
        } catch (ErrorLexicalException $e) {
            $this->valid = false;
            echo $e->__toString();
        }
    }

    private function removeSimilarSigns()
    {
        try {
            if (
                preg_match('/[*]{2,}/', $this->expression)||
                preg_match('/[\/]{2,}/', $this->expression)||
                preg_match('/[\-]{2,}/', $this->expression)||
                preg_match('/[+]{2,}/', $this->expression)
            ) {
                preg_match_all('/[*]{2,}/', $this->expression, $similarSigns0, PREG_OFFSET_CAPTURE);
                preg_match_all('/[\/]{2,}/', $this->expression, $similarSigns1, PREG_OFFSET_CAPTURE);
                preg_match_all('/[\-]{2,}/', $this->expression, $similarSigns2, PREG_OFFSET_CAPTURE);
                preg_match_all('/[+]{2,}/', $this->expression, $similarSigns3, PREG_OFFSET_CAPTURE);
                $similarSigns = array_merge ($similarSigns0[0], $similarSigns1[0], $similarSigns2[0], $similarSigns3[0]);
                usort($similarSigns, "cmp");
                throw new NoticeLexicalException("декілька однакових знаків підряд", [$similarSigns], $this->expression);
            }
        } catch (NoticeLexicalException $e) {
            echo $e->__toString();
            $this->expression = preg_replace("/[*]{2,}/", "*", $this->expression);
            $this->expression = preg_replace("/[\/]{2,}/", "/", $this->expression);
            $this->expression = preg_replace("/[\-]{2,}/", "-", $this->expression);
            $this->expression = preg_replace("/[+]{2,}/", "+", $this->expression);
        }
    }

    private function removeUnexpectedCharacters()
    {
        try {
            if (
                preg_match('/[\/*\-+.]+[)]/', $this->expression)||
                preg_match('/[(][\/*+.]+/', $this->expression)||
                preg_match('/^[+*\/.)]+/', $this->expression)||
                preg_match('/[+*\/.(]+$/', $this->expression)
            ) {
                preg_match_all('/[\/*\-+.]+[)]/', $this->expression, $notices0, PREG_OFFSET_CAPTURE);
                preg_match_all('/[(][\/*+.]+/', $this->expression, $notices1, PREG_OFFSET_CAPTURE);
                preg_match_all('/^[+*\/.)]+/', $this->expression, $notices2, PREG_OFFSET_CAPTURE);
                preg_match_all('/[+*\/.(]+$/', $this->expression, $notices3, PREG_OFFSET_CAPTURE);
                $notices = array_merge ($notices0[0], $notices1[0], $notices2[0], $notices3[0]);
                usort($notices, "cmp");
                throw new NoticeLexicalException("неочікувані символи", [$notices], $this->expression);
            }
        } catch (NoticeLexicalException $e) {
            echo $e->__toString();
            $this->expression = preg_replace("/[\/*\-+.]+[)]/", ")", $this->expression);
            $this->expression = preg_replace("/[(][\/*+.]+/", "(", $this->expression);
            $this->expression = preg_replace("/^[+*\/.)]+/", "", $this->expression);
            $this->expression = preg_replace("/[+*\/.(]+$/", "", $this->expression);
        }
    }

    private function searchEmptyBrackets()
    {
        try {
            if (preg_match('/[(][)]/', $this->expression)) {
                preg_match_all('/[(][)]/', $this->expression, $notices, PREG_OFFSET_CAPTURE);
                throw new ErrorLexicalException("пусті дужки", $notices, $this->expression);
            }
        } catch (ErrorLexicalException $e) {
            $this->valid = false;
            echo $e->__toString();
        }
    }

    private function searchCriticalErrors()
    {
        try {
            if (preg_match('/[0-9][A-Za-z]/', $this->expression)) {
                preg_match_all('/[0-9][A-Za-z]/', $this->expression, $errors, PREG_OFFSET_CAPTURE);
                throw new ErrorLexicalException("змінна не може починатися з цифри", $errors, $this->expression);
            }
        } catch (ErrorLexicalException $e) {
            $this->valid = false;
            echo $e->__toString();
        }
    }

    private function searchSeveralSigns()
    {
        try {
            if (preg_match('/[\/*\-+]{2,}/', $this->expression)) {
                preg_match_all('/[\/*\-+]{2,}/', $this->expression, $errors, PREG_OFFSET_CAPTURE);
                throw new ErrorLexicalException("2 або більше різних арифметичних знаки підряд", $errors, $this->expression);
            }
        } catch (ErrorLexicalException $e) {
            $this->valid = false;
            echo $e->__toString();
        }
    }

    private function searchNotDigitsBeforeAndAfterDots()
    {
        try {
            if (preg_match('/[^0-9][.]/', $this->expression) || preg_match('/[.][^0-9]/', $this->expression)) {
                preg_match_all('/[^0-9][.]/', $this->expression, $errors1, PREG_OFFSET_CAPTURE);
                preg_match_all('/[.][^0-9]/', $this->expression, $errors2, PREG_OFFSET_CAPTURE);
                $errors[0] = array_merge($errors1[0], $errors2[0]);
                throw new ErrorLexicalException("не цифра до та/або після крапки", $errors, $this->expression);
            }
        } catch (ErrorLexicalException $e) {
            $this->valid = false;
            echo $e->__toString();
        }
    }

    private function searchSeveralDotsInOneNumber()
    {
        try {
            if (preg_match('/[0-9.]+/', $this->expression)) {
                preg_match_all('/[0-9.]+/', $this->expression, $notices, PREG_OFFSET_CAPTURE);
                $errors = [];
                foreach ($notices[0] as $notice) {
                    preg_match_all('/[.]/', $notice[0], $dots, PREG_OFFSET_CAPTURE);
                    if (count($dots[0]) > 1) {
                        array_push($errors, $notice);
                    }
                }
                if (count($errors) > 0) {
                    throw new ErrorLexicalException("декілька крапок в числі", [$errors], $this->expression);
                }
            }
        } catch (ErrorLexicalException $e) {
            $this->valid = false;
            echo $e->__toString();
        }
    }

    private function bracketsAnalyze()
    {
        try {
            preg_match_all('/[()]/', $this->expression, $brackets, PREG_OFFSET_CAPTURE);

            $brackets = $brackets[0];
            usort($brackets, "cmp");
            do {
                $count1 = count($brackets);
                for ($i = 0; $i < count($brackets) - 1; $i++) {
                    if ($brackets[$i][0] == '(') {
                        if ($brackets[$i + 1][0] == ')') {
                            unset($brackets[$i]);
                            unset($brackets[$i + 1]);
                        }
                    }
                }
                usort($brackets, "cmp");
                $count2 = count($brackets);
            } while ($count1 > $count2);
            if (count($brackets) > 0) {
                throw new ErrorLexicalException("дужка без пари", [$brackets], $this->expression);
            }
        } catch (ErrorLexicalException $e) {
            $this->valid = false;
            echo $e->__toString();
            $this->singleNumberInBrackets();
        }
    }

    private function singleNumberInBrackets()
    {
        try {
            if (preg_match('/[(][A-Za-z0-9]+[)]/', $this->expression)) {
                preg_match_all('/[(][A-Za-z0-9]+[)]/', $this->expression, $errors, PREG_OFFSET_CAPTURE);
                throw new NoticeLexicalException("одне невід'ємне число в дужках", $errors, $this->expression);
            }
        } catch (NoticeLexicalException $e) {
            echo $e->__toString();
            $this->expression = preg_replace ("/[(]([A-Za-z0-9]+)[)]/","$1",$this->expression);
        }
    }
}