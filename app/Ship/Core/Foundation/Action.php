<?php


namespace App\Ship\Core\Foundation;


use App;
use App\Ship\Core\Exceptions\ClassDoesNotExistException;
use App\Ship\Core\Exceptions\MissingContainerException;
use App\Ship\Core\Traits\CallableTrait;
use Log;

class Action
{
    use CallableTrait;

    /**
     * Get instance from a class string
     *
     * @param $class
     *
     * @return  mixed
     */
    private function resolveClass($class)
    {
        // in case passing apiato style names such as containerName@classType
        if ($this->needsParsing($class)) {

            [$containerName, $className] = $this->parseClassName($class);

            \Action::verifyContainerExist($containerName);

            $class = $classFullName = \Action::buildClassFullName($containerName, $className);

            \Action::verifyClassExist($classFullName);
        } else {
            Log::debug('It is recommended to use the apiato caller style (containerName@className) for ' . $class);
        }

        return App::make($class);
    }

    /**
     * @param $containerName
     *
     * @throws MissingContainerException
     */
    public function verifyContainerExist($containerName)
    {
        if (!is_dir(app_path('Containers/' . $containerName))) {
            throw new MissingContainerException("Container ($containerName) is not installed.");
        }
    }

    /**
     * Build namespace for a class in Container.
     *
     * @param $containerName
     * @param $className
     *
     * @return  string
     */
    public function buildClassFullName($containerName, $className)
    {
        return 'App\Containers\\' . $containerName . '\\Actions\\' . $className;
    }

    /**
     * @param $className
     *
     * @throws ClassDoesNotExistException
     */
    public function verifyClassExist($className)
    {
        if (!class_exists($className)) {
            throw new ClassDoesNotExistException("Class ($className) is not installed.");
        }
    }

}
