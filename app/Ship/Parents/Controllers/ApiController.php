<?php


namespace App\Ship\Parents\Controllers;


use App\Ship\Core\Abstracts\Controllers\ApiController as AbstractApiController;

/**
 * @OA\Info(
 *     title="HSE Buildings API Documentation",
 *     version="1.0",
 * )
 * @OA\Server(
 *     description="HSE Buildings Dev Server",
 *     url="https://dev.hse.fmake.ru/api/v1",
 * )
 * @OA\Server(
 *     description="HSE Buildings Local Server",
 *     url="http://hse.loc/api/v1",
 * )
 *
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     in="header",
 *     scheme="Bearer",
 *     bearerFormat="JWT",
 *     name="bearerAuth",
 * )
 *
 * Class ApiController
 *
 * @package App\Ship\Parents\Controllers
 */
abstract class ApiController extends AbstractApiController
{

}
