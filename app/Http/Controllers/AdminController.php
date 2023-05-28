<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\QnaExam;

use Illuminate\Support\Facades\Hash;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class AdminController extends Controller
{
    //adding the subject to the database
    public function addSubject(Request $request)
    {
        try{

            Subject::insert([
                'subject' =>$request->subject
            ]);
            return response()->json(['success'=>true,'msg'=>'Subject has been added successfully!']);
        }
        catch(\Exception $e){
            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
        }
    }

    //editing the subject
    public function editSubject(Request $request)
    {
        try{
           $subject = subject::find($request->id);
           $subject->subject = $request->subject;
           $subject->save();  
            
            return response()->json(['success'=>true,'msg'=>'Subject has been updated successfully!']);
        }
        catch(\Exception $e){
            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
        }
    }

        //deleting the subject
        public function deleteSubject(Request $request)
        {
            try{
              
                Subject::where('id',$request->id)->delete() ;
               return response()->json(['success'=>true,'msg'=>'Subject has been deleted successfully!']);
            }
            catch(\Exception $e){
                return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
            }
        }

    //exam dashboard load
    public function examDashboard()
    {
        $subjects = Subject::all();
        $exams = Exam::with('subjects')->get();
        return view('admin.exam-dashboard',['subjects'=>$subjects,'exams'=>$exams]);
    }

    //add exam
    public function addExam(Request $request)
    {
        try{
            Exam::insert([
                'exam_name' => $request->exam_name,
                'subject_id' => $request->subject_id,
                'date' => $request->date,
                'time' => $request->time,
                'attempt' => $request->attempt

            ]);
           return response()->json(['success'=>true,'msg'=>'Exam added successfully!']);
        }
        catch(\Exception $e){
            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
        };

    }

    //get the exam details
    public function getExamDetail($id)
    {
        try{
        $exam = Exam::where('id',$id)->get();
        return response()->json(['success'=>true,'data'=>$exam]);
    }
    catch(\Exception $e){
        return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
    };
}
    //updating the exam controller
    public function updateExam(Request $request)
    {
        try{
        $exam = Exam::find($request->exam_id);
        $exam->exam_name = $request->exam_name;
        $exam->subject_id = $request->subject_id;
        $exam->date = $request->date;
        $exam->time = $request->time;
        $exam->attempt = $request->attempt;
        $exam->save();

        return response()->json(['success'=>true,'msg'=>'Exam updated Succeffully']);
    }
        catch(\Exception $e){
        return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
    };
}

//delete Exam
public function deleteExam(Request $request){
    try{

        Exam::where('id',$request->exam_id)->delete();
        return response()->json(['success'=>true,'msg'=>'Exam deleted Succeffully']);
    }
        catch(\Exception $e){
        return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
    };
}

//question and answer
public function qnaDashboard(){

    $questions = Question::with('answers')->get();
    return view('admin.qnaDashboard',compact('questions'));

}

//add question and answer
    public function addQna(Request $request)
    {
        try{
            $questionId=Question::insertGetId([
            'question' => $request->question
            ]);
            foreach($request->answers as $answer){
                $is_correct = 0;
                if($request->is_correct == $answer){
                    $is_correct = 1;
                }
                Answer::insert([
                    'questions_id' =>$questionId,
                    'answer' =>$answer,
                    'is_correct' =>$is_correct
                ]);
            }
            return response()->json(['success'=>true,'msg'=>'Exam deleted Succeffully']);
        }
            catch(\Exception $e){
            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
        };
        
    }

    public function getQnaDetails(Request $request)
    {
        $qna = Question::where('id' ,$request->qid)->with('answers')->get();
        
        return response()->json(['data'=>$qna]);
    }

    public function deleteAns(Request $request){
        Answer::where('id',$request->id)->delete();
        return response()->json(['success'=>true,'msg'=>'Answer Deleted Successfully!']);
    }

    public function updateQna(Request $request)
    {
        try{
            Question::where('id',$request->question_id)->update([
                'question' =>$request->question
            ]);

            //old answer update
            if(isset($request->answers)){
                foreach($request->answers as $key => $value){

                    $is_correct = 0;
                    if($request->is_correct == $value){
                        $is_correct = 1;
                    }

                    Answer::where('id', $key)
                    ->update([
                        'questions_id' => $request->question_id,
                        'answer' => $value,
                        'is_correct' => $is_correct

                    ]);
                }
            }
            //new answers added
            if(isset($request->new_answers)){
                foreach($request->new_answers as $answer){

                    $is_correct = 0;
                    if($request->is_correct == $answer){
                        $is_correct = 1;
                    }

                    Answer::insert([
                        'questions_id' => $request->question_id,
                        'answer' => $answer,
                        'is_correct' => $is_correct
                    ]);
                
                }
            }
            return response()->json(['success'=>true,'msg'=>'Question and Answer Updated Successfully']);
 
        }      
        catch(\Exception $e){
            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
        }
    }

    public function deleteQna(Request $request)
    {
        Question::where('id',$request->id)->delete();
        Answer::where('questions_id',$request->id)->delete();

        return response()->json(['success'=>true,'msg'=>'Question and Answer deleted Successfully']);
    }

    //student dashboard
    public function studentsDashboard(){
        $students = User::where('is_admin',0)->get();
        return view('admin.studentsDashboard',compact('students'));
    }

    //add student
    public function addStudent(Request $request)
    {
        try{
            
            $password  = Str::random(8);

            User::insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password)
            ]);

             $url = URL::to('/');

             $data['url'] = $url;

             $data['name'] = $request->name;

             $data['email'] = $request->email;

             $data['password'] = $password;

             $data['title'] = "Student Registration on OES";

             Mail::send('registrationMail',['data'=>$data],function($message) use ($data){
                $message->to($data['email'])->subject($data['title']);

             });
             return response()->json(['success'=>true,'msg'=>'Student Added Successfully']);
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
        }
    }
    //update and edit the students biodata
    public function editStudent(Request $request)
    {
        try{
            
            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

             $url = URL::to('/');

             $data['url'] = $url;

             $data['name'] = $request->name;

             $data['email'] = $request->email;

            
             $data['title'] = " Updated Student Profile on the system";

             Mail::send('updateProfileMail',['data'=>$data],function($message) use ($data){
                $message->to($data['email'])->subject($data['title']);

             });
             return response()->json(['success'=>true,'msg'=>'Student updated Successfully']);
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
        }
    }     

    //delete student
    public function deleteStudent(Request $request){
        try
        {
            User::where('id',$request->id)->delete();
            return response()->json(['success'=>true,'msg'=>'Student deleted Successfully']);

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
        }
    }

    //get questions
    public function getQuestions(Request $request)
    {
        try{

            $questions = Question::all();
            if(count($questions) > 0){
                
                $data = [];
                $counter = 0;
                
                foreach($questions as $question)
                {
                    $qnaExam = QnaExam::where(['exam_id'=>$request->exam_id,'question_id'=>$question->id])->get();
                    
                    if(count($qnaExam) == 0){
                        $data[$counter]['id'] = $question->id;
                        $data[$counter]['questions'] = $question->question;
                        $counter++;
                    }
                }

                return response()->json(['success'=>true,'msg'=>'Questions data!','data'=>$data]);

            }else{
                return response()->json(['success'=>false,'msg'=>'Questions not found!']);
            }

        }catch(\Exception $e)
        {
            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
        }
    }

    public function addQuestions(Request $request){
        try{

            if(isset($request->questions_ids)){
                foreach($request->questions_ids as $qid){
                    QnaExam::insert([
                        'exam_id' => $request->exam_id,
                        'question_id' =>$qid

                    ]);
                }
            }
            return response()->json(['success'=>true,'msg'=>'Questions added Successfully']);


        }catch(\Exception $e)
        {
            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
        }
    }

}