<?php

namespace App\Illuminate\Database;
use File;
use Storage;

abstract class Seeder extends \Illuminate\Database\Seeder
{
    protected $initializations = [];
    function __construct()
    {
        if (Storage::disk('database')->exists('database.json')) {
            $this->initializations = json_decode(Storage::disk('database')->get('database.json'), JSON_UNESCAPED_UNICODE);
        }
    }

    public function getInitializaions($key = null)
    {
        if (empty($key))
            return $this->initializations;

        $return = [];
        $initialization_keys = array_filter(array_keys($this->initializations), function ($item) use ($key) {
            return str_starts_with($item, $key);
        });
        // var_dump($initialization_keys);
        foreach ($initialization_keys as $initialization_key) {
            $mergeData = parse_ini_string(str_replace("&", "\n", \Str::between($initialization_key, '[', ']')));

            // var_dump($mergeData);
            $return = array_merge(
                $return,
                array_map(
                    function ($item) use ($mergeData) {
                        return array_merge($item, $mergeData);
                    },
                    array_filter(
                        \Arr::get($this->initializations, $initialization_key, []),
                        function ($item) {
                            // var_dump([
                            //     empty($item['title']),
                            //     empty($item['name']),
                            // ]);
                            return !(empty($item['title'])) || !(empty($item['name']));
                        }
                    )
                )
            );

            // \App\Models\link::upsert(, ['slug'], ['slug', 'title', 'url', 'ico', 'description', 'keywords', 'type', 'status']);

        }
        return $return;
    }
    public function call($class, $silent = false, array $parameters = [])
    {
        $classes = \Arr::wrap($class);

        foreach ($classes as $class) {
            $seeder = $this->resolve($class);

            $name = get_class($seeder);

            if ($silent === false && isset($this->command)) {
                $this->command->getOutput()->writeln("<comment>Seeding:</comment> {$name}");
            }
            $startTime = microtime(true);

            $seeder->__invoke($parameters);

            $runTime = number_format((microtime(true) - $startTime) * 1000, 2);

            if ($silent === false && isset($this->command)) {
                $this->command->getOutput()->writeln("<info>Seeded:</info>  {$name} ({$runTime}ms)");
            }



        }

        return $this;
    }

    /**
     * Run the database seeds.
     *
     * @param  array  $parameters
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function __invoke(array $parameters = [])
    {
        if (!method_exists($this, 'run')) {
            throw new \InvalidArgumentException('Method [run] missing from ' . get_class($this));
        }
        \DB::enableQueryLog();
        \DB::flushQueryLog();


        $return = isset($this->container)
            ? $this->container->call([$this, 'run'], $parameters)
            : $this->run(...$parameters);

        $queryLogs = \DB::getQueryLog();
        if (sizeof($queryLogs) > 0) {
            Storage::disk('database')->put(basename(get_class($this)) . '.sql', '-- ' . get_class($this) . PHP_EOL);
            // dd($queryLogs);
            foreach ($queryLogs as $queryLog) {
                $queryLog['bindings'] = array_map(function ($item) {
                    return is_string($item) ? "'$item'" : $item;
                }, $queryLog['bindings']);
                $sql = \Str::replaceArray('?', $queryLog['bindings'], $queryLog['query']);
                Storage::disk('database')->append(basename(get_class($this)) . '.sql', $sql . PHP_EOL);
            }
        }
        \DB::disableQueryLog();
        return $return;
    }
}
