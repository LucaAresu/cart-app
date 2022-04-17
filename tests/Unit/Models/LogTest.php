<?php

namespace Tests\Unit\Models;

use App\Models\Log;

class LogTest extends \Tests\TestCase
{
    public function testGetActionAttribute()
    {
        $log = new Log();
        $log->action = Log::ACTION_DELETE;

        $this->assertEquals(
            $log->action,
            Log::ACTION_LABELS[Log::ACTION_DELETE]
        );
    }

}
