<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailDataJamur extends Model
{
    protected $fillable = ['data_jamur_id','kriteria_id','sub_kriteria_id','nilai'];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function subKriteria()
    {
        return $this->belongsTo(SubKriteria::class, 'sub_kriteria_id');
    }
}
