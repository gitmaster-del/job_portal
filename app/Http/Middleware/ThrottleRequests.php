<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests as Middleware;

class ThrottleRequests extends Middleware
{
    // Limits number of requests to avoid abuse
}
