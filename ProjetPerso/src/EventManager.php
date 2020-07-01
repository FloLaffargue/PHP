<?php

namespace App;

use App\Listener;
use App\Model\Post;
use App\DeletePostEvent;

class EventManager {

    private $listeners = [];

    public function attach(string $eventName, callable $callback, int $priority)
    {
        $this->listeners[$eventName][] = new Listener($callback, $priority);
        $this->sortListeners($eventName);
    } 

    public function emit($event, $args = []) 
    {
        foreach($this->listeners[$event->getName()] as $listener) {
            $listener->handle($event, $args);
        }
    }

    private function sortListeners(string $eventName)
    {
        usort($this->listeners[$eventName], function($a, $b) {
            return $b->getPriority() - $a->getPriority();
        });
    }

}



