<?php

namespace App;

class DIC {

    private static $registry = [];
    private static $instances = [];

    public static function register(string $name, Callable $callable): void 
    {
        if(!in_array($name, self::$registry))
        {
            self::$registry[$name] = $callable;
        }
    }
    
    public static function get(string $className) 
    {
        if(isset(self::$registry[$className])) {

            if(!isset(self::$instances[$className])) {
                self::$instances[$className] = call_user_func(self::$registry[$className]);
            } 
            return self::$instances[$className];
               
        } else {
            // J'essaie d'instancier la class par moi-même
            $result = self::tryForceInstance($className);
            return $result;
        }
    }
    
    public static function tryForceInstance(string $className)
    {
        $class = new \ReflectionClass($className);

        if($class->isInstantiable()) {

            $constructor = $class->getConstructor(); 
            if($constructor) {
                return self::tryGetInstanceWithConstructor($constructor, $class);
    
            } else {
                return $class->newInstance();
            }
        }

        return null;
    }

    public static function tryGetInstanceWithConstructor($constructor, $class) 
    {
        $constructor_parameters = [];
        $parameters = $constructor->getParameters();

        foreach($parameters as $parameter) {

            if($parameter->getClass()) {
                $classNameParam = ($parameter->getClass()->getName());
                $constructor_parameters[] = self::get($classNameParam);
            } 
            else {
                try {
                    $constructor_parameters[] = $parameter->getDefaultValue();
                } catch (\Exception $e) {
                    dd("Pas de valeur par défault pour le parametre $parameter->name");
                }

            }
        }
        return $class->newInstanceArgs($constructor_parameters);
    }

}



