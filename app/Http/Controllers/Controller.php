<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;


#[OA\Info(
    title: 'MarocExplore API',
    version: '1.0.0',
    description: 'API for managing tourist itineraries in Morocco'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    name: 'Authorization',
    in: 'header',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]

abstract class Controller
{
    //
}
