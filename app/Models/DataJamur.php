<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataJamur extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','tanggal'];

    public function detailDataJamur()
    {
        return $this->hasMany(DetailDataJamur::class);
    }

    public function hasilSaws()
    {
        return $this->hasMany(HasilSaw::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
