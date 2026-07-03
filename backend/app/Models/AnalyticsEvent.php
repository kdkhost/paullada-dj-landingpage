<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['tipo', 'pagina', 'ip', 'user_agent'])]
class AnalyticsEvent extends Model
{
}
