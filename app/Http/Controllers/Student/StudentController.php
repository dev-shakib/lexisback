<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Word;
use Auth;
use Storage;
class StudentController extends Controller
{
    public function profile()
    {
        // $student = Student::with('configs')->get();
        $student = Student::with('configs')->where('id',auth()->user()->id)->first();
    //     dd(response()->json([
    //         'success'=>true,
    //         'data' => $student
    //    ]));
       return response()->json([
            'success'=>true,
            'data' => $student
       ]);
    }
    public function activity($id){
        $student = Student::with('configs')->where('id',auth()->user()->id)->first();
        switch($id){
            case '1':
                $word = Word::select('name','imageFile','audioFile')->where('user_id',$student->configs->user_id)->get();

                $datas = [];
                $i=0;
                foreach($word as $w)
                {
                    $datas[$i]['wordName'] = $w['name'];
                    $datas[$i]['imageFile'] = Storage::url('storage/app/public/word/image/'.$w['imageFile']);
                    $datas[$i]['audioFile'] = Storage::url('storage/app/public/word/audio/'.$w['audioFile']);
                    $WordApp = str_split($w['name']);
                   shuffle($WordApp);
                   $datas[$i]['suff'] =  $WordApp;
                   $i++;
                }
                return response()->json([
                    'success'=>true,
                    'data' => $datas
                ]);
            case 2:
                $word = Word::select('name','imageFile','audioFile')->where('user_id',$student->configs->user_id)->get();
                $datas = [];
                $i=0;
                foreach($word as $w)
                {
                    $datas[$i]['wordName'] = $w['name'];
                    $datas[$i]['imageFile'] = Storage::url('storage/app/public/word/image/'.$w['imageFile']);
                    $datas[$i]['audioFile'] = Storage::url('storage/app/public/word/audio/'.$w['audioFile']);
                   $i++;
                }
                return response()->json([
                    'success'=>true,
                    'data' => $datas
                ]);
                break;
            case 3:
                $word = Word::select('name','imageFile','audioFile')->where('user_id',$student->configs->user_id)->get();
                $datas = [];
                $i=0;
                foreach($word as $w)
                {
                    $datas[$i]['wordName'] = $w['name'];
                    $datas[$i]['imageFile'] = Storage::url('storage/app/public/word/image/'.$w['imageFile']);
                    $datas[$i]['audioFile'] = Storage::url('storage/app/public/word/audio/'.$w['audioFile']);
                    $WordApp = str_split($w['name']);
                    $datas[$i]['sliceWord'] = array_slice($WordApp,  2, -1, true);
                   $i++;
                }
                return response()->json([
                    'success'=>true,
                    'data' => $datas
                ]);
                break;
            case 4:
                $word = Word::select('name','imageFile','audioFile')->where('user_id',$student->configs->user_id)->get();
                $datas = [];
                $i=0;
                foreach($word as $w)
                {
                    $datas[$i]['wordName'] = $w['name'];
                    $datas[$i]['imageFile'] = Storage::url('storage/app/public/word/image/'.$w['imageFile']);
                    $datas[$i]['audioFile'] = Storage::url('storage/app/public/word/audio/'.$w['audioFile']);
                    $WordApp = str_split($w['name']);
                    $datas[$i]['sliceWord'] = array_slice($WordApp,  2, -1, true);
                    shuffle($WordApp);
                   $datas[$i]['suff'] =  $WordApp;
                   $i++;
                }
                return response()->json([
                    'success'=>true,
                    'data' => $datas
                ]);
                break;
        }
    }
}
