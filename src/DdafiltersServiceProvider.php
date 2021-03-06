<?php namespace EvolutionCMS\Ddafilters;

use EvolutionCMS\ServiceProvider;

class DdafiltersServiceProvider extends ServiceProvider
{
    /**
     * Если указать пустую строку, то сниппеты и чанки будут иметь привычное нам именование
     * Допустим, файл test создаст чанк/сниппет с именем test
     * Если же указан namespace то файл test создаст чанк/сниппет с именем ddafilters#test
     * При этом поддерживаются файлы в подпапках. Т.е. файл test из папки subdir создаст элемент с именем subdir/test
     */
    protected $namespace = 'ddafilters';
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->registerModule('Params', dirname(__DIR__).'/modules/module.php');
    }
}