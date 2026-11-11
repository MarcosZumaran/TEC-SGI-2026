<?php

namespace App\Models;

use JeffersonGoncalves\ServiceDesk\Models\BusinessHoursTimeSlot;

class TecBusinessHoursTimeSlot extends BusinessHoursTimeSlot
{
    protected $casts = [
        'day_of_week' => 'integer',
    ];
}
