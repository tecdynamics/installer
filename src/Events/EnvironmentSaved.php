<?php

namespace Tec\Installer\Events;

use Tec\Base\Events\Event;
use Illuminate\Http\Request;

class EnvironmentSaved extends Event
{
    public function __construct(public Request $request)
    {
    }
}
