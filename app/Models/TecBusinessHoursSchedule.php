<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use JeffersonGoncalves\ServiceDesk\Models\BusinessHoursSchedule;

class TecBusinessHoursSchedule extends BusinessHoursSchedule
{
    /**
     * @return HasMany<TecBusinessHoursTimeSlot, $this>
     */
    public function timeSlots(): HasMany
    {
        return $this->hasMany(TecBusinessHoursTimeSlot::class, 'schedule_id');
    }
}
