<?php

namespace M2\CliCore\App;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use M2\CliCore\App\Helper\InputIO;
use M2\CliCore\App\Helper\Helper;

/**
 * @property Helper helper
 */
class Index
{
    /** @var CliMenu */
    protected $cliMenu;

    public function __construct()
    {
        $this->helper = Helper::getInstance();

        $this->check();
    }

    protected function check()
    {
        if (PHP_SAPI !== 'cli') {
            $this->helper->alertExit('must be run as a CLI application');
        }

        $composerMagento = @file_get_contents(
            $this->helper->path()->magento('composer.json')
        );

        if (strpos($composerMagento, 'Magento') === false) {
            $this->helper->alertExit('megento not found', 'e');
        }
    }

    public function run()
    {
        $itemCallable = function (CliMenu $menu, $keyCommand, $command) {
            $this->cliMenu = $menu;

            $build = $this->buildCommand($keyCommand);

            if (!is_array($build)) {
                $this->helper->alertExit('There was a problem');
            }

            $this->helper->alert(implode("\n", $build));

            $ask = InputIO::getInstance($this->cliMenu->getTerminal())
                ->yesNo('Do you want to run this command?');

            if ($ask != 'y') {
                $this->helper->alertExit('command exit');
            }

            foreach ($build as $comm) {
                // sudo -u www-data
                Process::getInstance()->run($comm);
            }

            $this->helper->alertExit('OK');
        };

        [$menu, $customControlMapping] = $this->showMenu($itemCallable);

        // add short key
        foreach ($customControlMapping as $key => $callback) {
            $menu->addCustomControlMapping(strtolower($key), $callback);
        }

        $menu->open();
    }

    /**
     * @param \Closure $itemCallable
     *
     * @return array
     */
    protected function showMenu(\Closure $itemCallable) : array
    {
        $menu = (new CliMenuBuilder)
            ->setForegroundColour('black')
            ->setBackgroundColour('default')
            ->setTitle('Magento 2');

        $customControlMapping = [];

        foreach ($this->helper->config()->commandAll() as $keyCommand => $command) {
            $callback = function (CliMenu $menu) use ($itemCallable, $keyCommand, $command) {
                $itemCallable($menu, $keyCommand, $command);
            };

            $map = count($customControlMapping) + 1;

            if ($map >= 10) {
                $map = chr($map + 55);
            }

            $customControlMapping[$map] = $callback;
            $map = '[' . $map . '] ';

            $menu->addItem($map . $command['title'], $callback);
        }

        $menu = $menu->addLineBreak('-')
            ->setPadding(2, 4)
            ->build();

        return [$menu, $customControlMapping];
    }

    protected function buildCommand($code, $isDep = false)
    {
        $commands = $this->helper->config()->commandAll();

        if (empty($code) || !isset($commands[$code])) {
            $this->helper->alertExit('command not found');
        }

        $command = $commands[$code];

        $codes = [];

        if (isset($command['deps']) && is_array($command['deps'])) {
            foreach ($command['deps'] as $depCode) {
                $codes[] = $this->buildCommand($depCode, true);
            }
        }

        $askValues = $this->helper->config()->askDefault();

        if (isset($command['ask']) && is_array($command['ask'])) {
            $askValues = $this->getAsk($command['ask'], $askValues);
        }

        $code = '';
        if (isset($command['code'])) {
            $code = $command['code'];

            foreach ($askValues as $key => $val) {
                $code = str_replace('{' . $key . '}', $val, $code);
            }

            if ($isDep) {
                return $code;
            }
        }

        if ($isDep) {
            return '';
        }

        if (!empty($code)) {
            array_unshift($codes, $code);
        }

        return $codes;
    }

    /**
     * @param array $asks
     * @param array $askValues
     *
     * @return array
     */
    protected function getAsk(array $asks, array $askValues)
    {
        foreach ($asks as $key => $ask) {

            $result = $this->cliMenu->askText()
                ->setValidator(function () {
                    return true;
                })
                ->setPromptText($ask['title']);

            if (!empty($ask['default'])) {
                $result->setPromptText($ask['title'] . ' ' . '[default: ' . $ask['default'] . ']');
            }

            $askValue = $result->ask()->fetch();

            if (empty($askValue)) {
                $askValue = $ask['default'];
            }

            $askValues[$key] = $askValue;
        }

        return $askValues;
    }
}
