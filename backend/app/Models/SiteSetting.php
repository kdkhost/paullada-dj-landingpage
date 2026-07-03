<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['chave', 'valor', 'tipo'])]
class SiteSetting extends Model
{
}
