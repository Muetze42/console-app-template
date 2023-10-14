<?php

namespace NormanHuth\ConsoleApp\Console\Commands\Example;

use Illuminate\Console\Command;

class HelloWorld extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hello:world';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a example hello world command';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->comment('Hello World');
    }
}
