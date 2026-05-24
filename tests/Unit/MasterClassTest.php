<?php

namespace Tests\Unit;

use App\Models\MasterClass;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class MasterClassTest extends TestCase
{
    public function test_schedule_label_formats_date_and_time(): void
    {
        $masterClass = new MasterClass([
            'class_date' => '2026-05-30',
            'start_time' => '09:00:00',
            'end_time' => '11:00:00',
        ]);

        $this->assertSame('30.05.2026, 09:00-11:00', $masterClass->schedule_label);
    }

    public function test_started_status_depends_on_start_time(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-24 12:00:00'));

        $past = new MasterClass([
            'class_date' => '2026-05-24',
            'start_time' => '09:00:00',
            'end_time' => '11:00:00',
        ]);

        $future = new MasterClass([
            'class_date' => '2026-05-25',
            'start_time' => '09:00:00',
            'end_time' => '11:00:00',
        ]);

        $this->assertTrue($past->hasStarted());
        $this->assertFalse($future->hasStarted());

        Carbon::setTestNow();
    }
}
