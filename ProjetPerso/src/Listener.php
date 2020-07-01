<?php 

namespace App;

class Listener {

    private $callback;
    private $priority;

    public function __construct(callable $callback, int $priority)
    {
        $this->callback = $callback;
        $this->priority = $priority;
    }

    public function handle($event)
    {
        call_user_func_array($this->callback, [$event]);
    }

    public function getPriority(): int 
    {
        return $this->priority;
    }
}