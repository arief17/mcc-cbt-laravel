<?php

namespace App\Http\Controllers\Cbt;

use App\Cbt\Exam;
use App\Cbt\Course;
use App\Cbt\Question;
use App\Cbt\Department;
use App\Cbt\ExamAtempt;
use App\Student\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CbtController extends Controller
{
    public function __construct() 
    {
        $this->middleware('authcbt:cbt');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cbt.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function get_exams(Department $department)
    {
        $exam = $department->exam()->with('course', 'section')->get();
        
        return $exam;
        // return $exam->load('course');
    }

    public function get_course_questions(Student $student, Exam $exam, Course $course)
    {
        // empty array to store random questions
        $randomise_questions = [];
        // get all course sections and questions
        $data = $course->load('sections', 'questions');
       
        // check if student has attempted questions before on this exam
        $stu_attempt = $student->attempts()->where([
            'exam_id' => $exam->id, 
            'course_id' => $course->id
        ])->get();

            // loop through current exam sections
            foreach($exam->section as $exam_section) {
                
                // pick current exam section questions only
                $section_questions = collect($data->questions)->where('section_id', $exam_section->section_id);
                // get already attempted questions first
                $stu_attempted = collect($stu_attempt)->where('section_id', $exam_section->section_id);
                    // if there's attempted questions
                    if(count($stu_attempted) > 0 ) {
                        $from_stu_attempted = [];
                        foreach($stu_attempted as $attempt) {
                            // $match = $section_questions->where('id', $q_attempt->question_id);
                            foreach($section_questions as $key => $question) {
                                if($question->id === $attempt->question_id) {
                                    array_push($from_stu_attempted, $question);
                                    unset($section_questions[$key]);
                                }
                            }
                        //    
                        }
                        // return $from_stu_attempted;
                        // $col_stu_att = collect($from_stu_attempted)->collapse();
                        
                        $remain = $exam_section->no_questions - count($from_stu_attempted);
                        
                        // if attemped questios are less than number of required section questions 
                        if($remain > 0) {
                           
                            $random = collect([
                                $from_stu_attempted, 
                                $section_questions->random($remain)
                            ])->collapse();
                           
                        } else {
                            $random = $from_stu_attempted;
                        }
                    } else {
                        $random = $section_questions->random($exam_section->no_questions);
                    }

                // push it to the $randomise_questions variable
                array_push($randomise_questions, $random);
            }
            
        // return course sections and exam randomised questions
        return [
            'sections' => $data->sections,
            'questions' => collect($randomise_questions)->collapse()
        ];

    }

    public function get_attempted(Request $request)
    {
        // return $request->input('courses');
        $attempted = [];
        foreach($request->courses as $course) {
            $attempts = ExamAtempt::where([
                'student_id' => $request->student,
                'course_id' => $course['id'],
            ])->get(['question_id', 'stu_attempt']);

            array_push($attempted, $attempts);
        }

        return collect($attempted)->collapse();
    }

    public function attempt(Request $request)
    {   

        $data = [];
    
        foreach($request->input('attempts') as $row) {
            // find attempt
            $findMatch = ExamAtempt::where([
                'student_id' => $row['student_id'],
                'question_id' => $row['question_id'],
            ]);

            // find question and compare the answer with student attempt
            Question::find($row['question_id'])->answer === $row['stu_attempt'] ?
            $row['stu_mark'] = 1 : $row['stu_mark'] = 0;
        
            // if question attempted update it else create new attempt
            if(count($findMatch->get()) > 0) {
                $row['updated_at'] = Carbon::now();
                $findMatch->update($row);
            } else {
                $row['created_at'] = Carbon::now();
                $row['updated_at'] = Carbon::now();
                array_push($data, $row);
            }
        }
        
        try {
            DB::table('exam_atempts')->insert($data);
            return 'success';
        } catch (\Throwable $th) {
           return $th;
        }
    }
}