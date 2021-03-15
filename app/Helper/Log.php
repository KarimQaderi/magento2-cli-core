<?php

namespace M2\CliCore\App\Helper;

use M2\CliCore\App\Traits\Singleton;

class Log
{
    use Singleton;

    /**
     * @param string $type [r => read,d => delete]
     *
     * @return mixed
     */
    public function command($type = 'r')
    {
        $path = Path::getInstance()->logCommand();

        if (!file_exists($path)) {
            return '';
        }

        if ($type == 'r') {
            return file_get_contents($path);
        }

        return file_put_contents($path, '');
    }
}
