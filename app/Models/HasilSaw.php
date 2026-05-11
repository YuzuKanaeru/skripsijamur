<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilSaw extends Model
{
    protected $fillable = ['data_jamur_id','penyakit_id','nilai_preferensi','ranking'];

    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class);
    }

    public function dataJamur()
    {
        return $this->belongsTo(DataJamur::class, 'data_jamur_id');
    }
}
