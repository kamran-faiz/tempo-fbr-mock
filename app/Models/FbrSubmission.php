<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FbrSubmission extends Model
{
   protected $fillable = [
     'invoice_number',
     'company_id',
     'irn',
     'status'
    ];
   
}
