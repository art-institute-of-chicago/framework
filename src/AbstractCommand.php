<?php

namespace Aic\Hub\Foundation;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Illuminate\Console\Command;

abstract class AbstractCommand extends Command
{
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    abstract public function handle();

    /**
     * For each trait attached to the command, check if there's a method named
     * `init{TraitName}`. If so, run it! We can use this to e.g. add the same
     * set of options to multiple commands using a trait.
     */
    public function __construct()
    {
        parent::__construct(...func_get_args());

        foreach (class_uses_recursive($this) as $trait) {
            if (method_exists($this, $method = 'init' . class_basename($trait))) {
                $this->{$method}();
            }
        }
    }

    /**
     * Here, we've extended the inherited execute method, which allows us to log times
     * for each command call. You can use `handle` in child classes as normal.
     *
     * @link http://api.symfony.com/3.3/Symfony/Component/Console/Command/Command.html
     * @link https://github.com/laravel/framework/blob/5.4/src/Illuminate/Console/Command.php
     *
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $start = microtime(TRUE);

        // Call Illuminate\Console\Command::execute
        $result = parent::execute($input, $output);

        $finish = microtime(TRUE);
        $totaltime = $finish - $start;
        $this->warn("Execution Time: {$totaltime} sec");

        return $result;
    }
}
