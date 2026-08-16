<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'contact_name', 'phone', 'email', 'address'])]
class Supplier extends Model {}
