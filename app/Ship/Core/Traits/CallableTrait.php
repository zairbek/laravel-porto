<?php

namespace App\Ship\Core\Traits;

use App\Ship\Core\Abstracts\Transporters\Transporter;
use Illuminate\Http\Request;

/**
 * Class CallableTrait.
 *
 * @author  Mahmoud Zalt <mahmoud@zalt.me>
 */
trait CallableTrait
{

    /**
     * This function will be called from anywhere (controllers, Actions,..) by the Apiato facade.
     * The $class input will usually be an Action or Task.
     *
     * @param       $class
     * @param array $runMethodArguments
     * @param array $extraMethodsToCall
     *
     * @return  mixed
     * @throws \Dto\Exceptions\UnstorableValueException
     */
    public function call($class, $runMethodArguments = [])
    {
        $class = $this->resolveClass($class);

        // detects Requests arguments "usually sent by controllers", and cvoert them to Transporters.
        $runMethodArguments = $this->convertRequestsToTransporters($class, $runMethodArguments);

        return $class->run(...$runMethodArguments);
    }

    /**
     * Split containerName@someClass into container name and class name
     *
     * @param        $class
     * @param string $delimiter
     *
     * @return  array
     */
    private function parseClassName($class, $delimiter = '@')
    {
        return explode($delimiter, $class);
    }

    /**
     * If it's apiato Style caller like this: containerName@someClass
     *
     * @param        $class
     * @param string $separator
     *
     * @return  int
     */
    private function needsParsing($class, $separator = '@')
    {
        return preg_match('/' . $separator . '/', $class);
    }

    /**
     * For backward compatibility purposes only. Could be a temporal function.
     * In case a user passed a Request object to an Action that accepts a Transporter, this function
     * converts that Request to Transporter object.
     *
     * @param       $class
     * @param array $runMethodArguments
     *
     * @return  array
     * @throws \Dto\Exceptions\UnstorableValueException
     */
    private function convertRequestsToTransporters($class, array $runMethodArguments = [])
    {
        $requestPositions = [];

        // we first check, if one of the params is a REQUEST type
        foreach ($runMethodArguments as $argumentPosition => $argumentValue) {
            if ($argumentValue instanceof Request) {
                $requestPositions[] = $argumentPosition;
            }
        }

        // now check, if we have found any REQUEST params
        if (empty($requestPositions)) {
            // We have not found any REQUEST params, so we don't need to transform anything.
            // Just return the runArguments and we are ready...
            return $runMethodArguments;
        }

        // ok.. now we need to get the method signature from the run() method to be called on the $class
        // and check, if on the positions we found REQUESTs are TRANSPORTERs defined!
        // this is a bit more tricky than the stuff above - but we will manage this

        // get a reflector for the run() method
        $reflector = new \ReflectionMethod($class, 'run');
        $calleeParameters = $reflector->getParameters();

        // now specifically check only the positions we have found a REQUEST in the call() method
        foreach ($requestPositions as $requestPosition) {
            $parameter = $calleeParameters[$requestPosition];
            $parameterClass = $parameter->getClass();

            // check if the parameter has a class and this class actually does exist!
            if (!(($parameterClass != null) && (class_exists($parameterClass->name)))) {
                // no, some tests failed - we cannot convert - skip this entry
                continue;
            }

            // and now, we finally need to check, if the class of this param is a subclass of TRANSPORTER
            // Note that we cannot use instanceof here, but rather need to rely on is_subclass_of instead
            $parameterClassName = $parameterClass->name;
            if (! is_subclass_of($parameterClassName, Transporter::class)) {
                // the class is NOT a subclass of TRANSPORTER
                continue;
            }

            // so everything is ok
            // now we need to "switch" the REQUEST with the TRANSPORTER
            /** @var Request $request */
            $request = $runMethodArguments[$requestPosition];
            $transporterClass = $request->getTransporter();
            /** @var Transporter $transporter */
            // instantiate transporter and hydrate it with request
            $transporter = new $transporterClass($request->all());

            // and now "replace" the request with this transporter
            $runMethodArguments[$requestPosition] = $transporter;
        }

        return $runMethodArguments;
    }

}
