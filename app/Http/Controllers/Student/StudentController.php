<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Auth;
class StudentController extends Controller
{
    public function profile()
    {
        $student = Student::with('activity')->get();
        // $student = Student::with('activity')->where('id',auth()->user()->id)->first();
        dd($student);
    }
}
