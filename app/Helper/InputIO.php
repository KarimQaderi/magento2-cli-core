<?php

namespace M2\CliCore\App\Helper;

use M2\CliCore\App\Traits\Singleton;
use PhpSchool\Terminal\NonCanonicalReader;
use PhpSchool\Terminal\InputCharacter;

/**
 * @property Helper helper
 */
class InputIO
{
    use Singleton;

    protected $terminal;

    public function __construct($terminal)
    {
        $this->terminal = $terminal;
        $this->helper = Helper::getInstance();
    }

    public function yesNo($ask)
    {
        echo $this->helper->getColorTextEcho($ask, 'w', false) . ' ';
        echo $this->helper->getColorTextEcho('[Y,n]', 's');

        $reader = new NonCanonicalReader($this->terminal);

        while ($char = $reader->readCharacter()) {
            if ($char->isNotControl()) {
                return $char->get();
            }

            if ($char->isHandledControl()) {
                switch ($char->getControl()) {
                    case InputCharacter::ENTER:
                        return 'y';
                }
            }
        }

        return '';
    }

    public function ask($ask)
    {
        $this->helper->alert($ask);

        $this->helper->alert('Y,n');

        $reader = new NonCanonicalReader($this->terminal);

        $inputValue = '';

        while ($char = $reader->readCharacter()) {
            if ($char->isNotControl()) {
                $inputValue .= $char->get();
                continue;
            }

            if ($char->isHandledControl()) {
                switch ($char->getControl()) {
                    case InputCharacter::ESC:
                        return $inputValue;
                    case InputCharacter::ENTER:
                        return $inputValue;

                    case InputCharacter::BACKSPACE:
                        $inputValue = substr($inputValue, 0, -1);
                        continue 2;
                }
            }
        }

        return '';
    }
}
