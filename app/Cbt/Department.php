<?php

namespace App\Cbt;

use App\Cbt\Course;
use App\Student\Student;
use App\Library\LibResource;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Department extends Model
{
    use LaravelVueDatatableTrait;

    protected $guarded = [];
    
    protected $dataTableColumns = [
        'id' => [
            'searchable' => true,
        ],
        'department' => [
            'searchable' => true,
        ],
        'department_code' => [
            'searchable' => true,
        ],
        'created_at' => [
            'searchable' => true,
        ]
    ];
    // department courses
    public function course()
    {
        return $this->hasMany(Course::class);
    }

    // department exams 
    public function exam()
    {
        return $this->belongsToMany(Exam::class, 'exam_department')->withTimestamps();;
    }

    // library boooks for department
    public function lib_res()
    {
        return $this->hasMany(LibResource::class);
    }

    public function student()
    {
        return $this->hasMany(Student::class);
    }
}
