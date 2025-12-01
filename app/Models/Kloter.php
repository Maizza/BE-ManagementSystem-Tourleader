<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kloter extends Model
{
    use HasFactory;

    protected $table = 'kloters';
    protected $fillable = ['nama', 'tanggal'];

    public function tourleaders()
    {
        return $this->hasMany(Tourleader::class, 'kloter_id');
    }

    // ✅ alias 'name' -> pakai kolom 'nama' kalau ada
    protected $appends = ['name'];
    public function getNameAttribute(): string
    {
        return $this->attributes['nama']
            ?? $this->attributes['name']
            ?? ('Kloter #'.$this->attributes['id']);
    }
    public function checklistTasks() {
    return $this->belongsToMany(ChecklistTask::class, 'checklist_task_kloter', 'kloter_id', 'checklist_task_id');
}

}
