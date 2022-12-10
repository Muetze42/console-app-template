<?php

namespace NormanHuth\ConsoleApp;

use Exception;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Console\Application;

class App
{
    /**
     * @var Container
     */
    protected Container $container;

    /**
     * @var Dispatcher
     */
    protected Dispatcher $events;

    /**
     * @var Application
     */
    protected Application $artisan;

    /**
     * @param int|string|null $version
     * @throws Exception
     */
    public function __construct(int|string $version = null)
    {
        $this->container = new Container;
        $this->events = new Dispatcher($this->container);
        $this->artisan = new Application($this->container, $this->events, $this->setVersion($version));

        $this->artisan->setName('Lura');

        $this->resolveCommands('Console'.DIRECTORY_SEPARATOR.'Commands');

        $this->artisan->run();
    }

    /**
     * @param int|string|null $version
     * @return string
     */
    protected function setVersion(int|string|null $version): string
    {
        if (!$version) {
            $content = file_get_contents(__DIR__.'/../composer.json');
            $data = json_decode($content, true);

            $version = data_get($data, 'version', 1);
        }

        return (string) $version;
    }

    /**
     * Register all the commands in the given directory.
     *
     * @param string $path
     * @return void
     */
    protected function resolveCommands(string $path): void
    {
        $path = trim($path, '/\\');
        $items = glob(__DIR__.DIRECTORY_SEPARATOR.$path.'/*.php');
        foreach ($items as $item) {
            $class = __NAMESPACE__.'\\'.$path.'\\'.pathinfo($item, PATHINFO_FILENAME);
            $this->artisan->resolve($class);
        }

        $directories = glob(__DIR__.DIRECTORY_SEPARATOR.$path.'/*', GLOB_ONLYDIR);
        foreach ($directories as $directory) {
            $this->resolveCommands($path.DIRECTORY_SEPARATOR.basename($directory));
        }
    }
}
