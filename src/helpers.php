<?php

declare(strict_types=1);
/**
 * This file is part of Hapi.
 *
 * @link     https://www.nasus.top
 * @document https://wiki.nasus.top
 * @contact  xupengfei@xupengfei.net
 * @license  https://github.com/nasustop/hapi-helpers/blob/master/LICENSE
 */
if (! function_exists('value')) {
    /**
     * Return the default value of the given value.
     */
    function value(mixed $value, ...$args)
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}

if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     */
    function env($key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return value($default);
        }
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
            return substr($value, 1, -1);
        }
        return $value;
    }
}

if (! function_exists('config')) {
    /**
     * config.
     */
    function config(string $key, $default = null)
    {
        if (! \Hyperf\Context\ApplicationContext::hasContainer()) {
            throw new \RuntimeException('The application context lacks the container.');
        }

        $container = \Hyperf\Context\ApplicationContext::getContainer();

        if (! $container->has(\Hyperf\Contract\ConfigInterface::class)) {
            throw new \RuntimeException('ConfigInterface is missing in container.');
        }

        return $container->get(\Hyperf\Contract\ConfigInterface::class)->get($key, $default);
    }
}

if (! function_exists('make')) {
    /**
     * 获取容器对象.
     */
    function make(string $name, array $parameters = [])
    {
        if (\Hyperf\Context\ApplicationContext::hasContainer()) {
            $container = \Hyperf\Context\ApplicationContext::getContainer();
            if (method_exists($container, 'make')) {
                return $container->make($name, $parameters);
            }
        }
        $parameters = array_values($parameters);
        return new $name(...$parameters);
    }
}
