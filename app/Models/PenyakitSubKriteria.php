<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyakitSubKriteria extends Model
{
    protected $fillable = ['penyakit_id','kriteria_id','sub_kriteria_id'];

    public function subKriteria()
    {
        return $this->belongsTo(SubKriteria::class, 'sub_kriteria_id');
    }

    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class);
    }
}
