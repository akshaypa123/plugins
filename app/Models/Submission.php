<?php

namespace Modules\Form\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $table = 'forms';
    protected $fillable = ['name','email','message'];
}