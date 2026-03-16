<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AssignClassTeacherModel;
use App\Models\ClassModel;
use App\Models\ClassSubjectModel;
use App\Models\HomeworkModel;
use App\Models\HomeworkSubmitModel;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class HomeworkController extends Controller
{
    public function homeworkReport()
    {
        $data['getRecord'] = HomeworkSubmitModel::getHomeworkReport(); 
        $data['getClass'] = ClassModel::get();
        $data['header_title']= 'Home Work Report';
        return view('admin.homework.report',$data);
    }
    public function homework()
    {
        $data['getRecord'] = HomeworkModel::getRecord(); 
        $data['header_title']= 'Home Work';
        return view('admin.homework.list',$data);
    }
    public function add() {
        $data['getClass'] = ClassModel::getClass();
        $data['header_title'] = 'Add New Home Work';
        return view('admin.homework.add', $data);
    }

    public function ajax_get_subject(Request $request) {
        // Trim the input to prevent whitespace issues
        $class_id = trim($request->class_id);

        $getSubject = ClassSubjectModel::mySubject($class_id);
        $html = '<option value="">Select Subject</option>';

        foreach ($getSubject as $value) {
            $html .= '<option value="'.$value->subject_id.'">'.$value->subject_name.'</option>';
        }

        return response()->json([
            'success' => $html
        ]);
    }

    public function insert(Request $request) 
        {
        $homework = new HomeworkModel;
        $homework->class_id = trim($request->class_id);
        $homework->subject_id = trim($request->subject_id);
        $homework->homework_date = trim($request->homework_date);
        $homework->submission_date = trim($request->submission_date);
        $homework->description = trim($request->message);
        $homework->created_by = Auth::user()->id;

        if (!empty($request->file('document_file'))) {
            $file = $request->file('document_file');
            $ext = $file->getClientOriginalExtension();

            // 1. Fetch names for the filename
            $className = \App\Models\ClassModel::find($request->class_id)->name;
            $subjectName = \App\Models\Subject::find($request->subject_id)->name;

            // 2. Clean names (remove spaces and special characters)
            $cleanClassName = Str::slug($className);
            $cleanSubjectName = Str::slug($subjectName);
            $dateStr = date('d-m-Y', strtotime($request->homework_date));

            // 3. Create descriptive filename: subject-class-date-random.ext
            $filename = $cleanSubjectName . '-' . $cleanClassName . '-' . $dateStr . '-' . Str::random(5) . '.' . $ext;
            
            // 4. Move to public folder
            $file->move('upload/homework/', $filename);
            $homework->document_file = $filename;
        }

        $homework->save();
        return redirect('admin/homework/homework')->with('success', "Homework successfully created");
    }
    public function edit($id) 
        {
        $getRecord = HomeworkModel::getSingle($id);
        if (!empty($getRecord)) {
            $data['getRecord'] = $getRecord;
            $data['getClass'] = ClassModel::getClass();
            // Get subjects specifically for the already selected class
            $data['getSubject'] = ClassSubjectModel::mySubject($getRecord->class_id);
            
            $data['header_title'] = 'Edit Home Work';
            return view('admin.homework.edit', $data);
        } else {
            abort(404);
        }
    }

public function update(Request $request, $id) 
    {
        $homework = HomeworkModel::getSingle($id);
        $homework->class_id = trim($request->class_id);
        $homework->subject_id = trim($request->subject_id);
        $homework->homework_date = trim($request->homework_date);
        $homework->submission_date = trim($request->submission_date);
        $homework->description = trim($request->message);

        if (!empty($request->file('document_file'))) {
            // Delete old file if it exists
            if (!empty($homework->document_file) && file_exists('upload/homework/'.$homework->document_file)) {
                unlink('upload/homework/'.$homework->document_file);
            }

            $file = $request->file('document_file');
            $ext = $file->getClientOriginalExtension();
            
            $className = \App\Models\ClassModel::find($request->class_id)->name;
            $subjectName = \App\Models\Subject::find($request->subject_id)->name;
            
            $filename = Str::slug($subjectName).'-'.Str::slug($className).'-'.date('d-m-Y').'-'.Str::random(5).'.'.$ext;
            $file->move('upload/homework/', $filename);
            $homework->document_file = $filename;
        }

        $homework->save();
        return redirect('admin/homework/homework')->with('success', "Homework successfully updated");
    }
    public function delete($id)
        {
            $homework = HomeworkModel::getSingle($id);
            
            if (!empty($homework)) {
                if (!empty($homework->document_file) && file_exists('upload/homework/'.$homework->document_file)) {
                    unlink('upload/homework/'.$homework->document_file);
                }
                $homework->is_delete = 1;
                $homework->save();

                return redirect()->back()->with('success', "Homework successfully deleted");
            } else {
                abort(404);
            }
        }
        public function submitted($homework_id)
        {
            $homework = HomeworkModel::getSingle($homework_id);
            if(!empty($homework))
                {
                    $data['homework_id'] = $homework_id;
                    $data['getRecord'] = HomeworkSubmitModel::getRecord($homework_id); 
                    $data['header_title']= 'Submitted Work';
                    return view('admin.homework.submitted',$data);
                }
                else{

                }
        }

        // teacher side 
 
        
     public function HomeworkTeacher() {
        $class_ids = array();
        $getClass = AssignClassTeacherModel::getMyClassSubjectGroup(Auth::user()->id); 
        foreach($getClass as $class) {
            $class_ids[] = $class->class_id;
        }
        // Pass class IDs to the Model to filter results
        $data['getRecord'] = HomeworkModel::getRecordTeacher($class_ids);
        $data['header_title'] = 'Homework';
        return view('teacher.homework.list', $data);
    }
        public function addTeacher() {
        $data['getClass'] = AssignClassTeacherModel::getMyClassSubjectGroup(Auth::user()->id);
        $data['header_title'] = 'Add New Home Work';
        return view('teacher.homework.add', $data);
    }
     public function insertTeacher(Request $request) 
        {
        $homework = new HomeworkModel;
        $homework->class_id = trim($request->class_id);
        $homework->subject_id = trim($request->subject_id);
        $homework->homework_date = trim($request->homework_date);
        $homework->submission_date = trim($request->submission_date);
        $homework->description = trim($request->message);
        $homework->created_by = Auth::user()->id;

        if (!empty($request->file('document_file'))) {
            $file = $request->file('document_file');
            $ext = $file->getClientOriginalExtension();

            // 1. Fetch names for the filename
            $className = \App\Models\ClassModel::find($request->class_id)->name;
            $subjectName = \App\Models\Subject::find($request->subject_id)->name;

            // 2. Clean names (remove spaces and special characters)
            $cleanClassName = Str::slug($className);
            $cleanSubjectName = Str::slug($subjectName);
            $dateStr = date('d-m-Y', strtotime($request->homework_date));

            // 3. Create descriptive filename: subject-class-date-random.ext
            $filename = $cleanSubjectName . '-' . $cleanClassName . '-' . $dateStr . '-' . Str::random(5) . '.' . $ext;
            
            // 4. Move to public folder
            $file->move('upload/homework/', $filename);
            $homework->document_file = $filename;
        }

        $homework->save();
        return redirect('teacher/homework/homework')->with('success', "Homework successfully created");
    }
    public function editTeacher($id) 
        {
        $getRecord = HomeworkModel::getSingle($id);
        if (!empty($getRecord)) {
            $data['getRecord'] = $getRecord;
            $data['getClass'] = ClassModel::getClass();
            // Get subjects specifically for the already selected class
            $data['getSubject'] = ClassSubjectModel::mySubject($getRecord->class_id);
            
            $data['header_title'] = 'Edit Home Work';
            return view('teacher.homework.edit', $data);
        } else {
            abort(404);
        }
    }

    public function updateTeacher(Request $request, $id) 
    {
        $homework = HomeworkModel::getSingle($id);
        $homework->class_id = trim($request->class_id);
        $homework->subject_id = trim($request->subject_id);
        $homework->homework_date = trim($request->homework_date);
        $homework->submission_date = trim($request->submission_date);
        $homework->description = trim($request->message);

        if (!empty($request->file('document_file'))) {
            // Delete old file if it exists
            if (!empty($homework->document_file) && file_exists('upload/homework/'.$homework->document_file)) {
                unlink('upload/homework/'.$homework->document_file);
            }

            $file = $request->file('document_file');
            $ext = $file->getClientOriginalExtension();
            
            $className = \App\Models\ClassModel::find($request->class_id)->name;
            $subjectName = \App\Models\Subject::find($request->subject_id)->name;
            
            $filename = Str::slug($subjectName).'-'.Str::slug($className).'-'.date('d-m-Y').'-'.Str::random(5).'.'.$ext;
            $file->move('upload/homework/', $filename);
            $homework->document_file = $filename;
        }

        $homework->save();
        return redirect('teacher/homework/homework')->with('success', "Homework successfully updated");
    }
        public function deleteTeacher($id)
        {
            $homework = HomeworkModel::getSingle($id);
            
            if (!empty($homework)) {
                if (!empty($homework->document_file) && file_exists('upload/homework/'.$homework->document_file)) {
                    unlink('upload/homework/'.$homework->document_file);
                }
                $homework->is_delete = 1;
                $homework->save();

                return redirect()->back()->with('success', "Homework successfully deleted");
            } else {
                abort(404);
            }
        }
public function homeworkReportTeacher()
    {
        $class_ids = array();
        $getClass = AssignClassTeacherModel::getMyClassSubjectGroup(Auth::user()->id); 
        foreach($getClass as $class) {
            $class_ids[] = $class->class_id;
        }
        $data['getClass'] = AssignClassTeacherModel::getMyClassSubjectGroup(Auth::user()->id);
        $data['getRecord'] = HomeworkSubmitModel::getHomeworkReportTeacher($class_ids); 
        $data['header_title']= 'Home Work Report';
        return view('teacher.homework.submitted',$data);
    }
 // Student side 
         public function homeworkStudent()
    {
       $data['getRecord'] = HomeworkModel::getRecordStudent(Auth::user()->class_id,Auth::user()->id);
        $data['header_title']= 'My Home Work';
        return view('student.homework.list',$data);
    }
        public function submitHomework($homework_id)
    {
       $data['getRecord'] = HomeworkModel::getSingle($homework_id);
        $data['header_title']= 'Submit Home Work';
        return view('student.homework.submit',$data);
    }
 public function submitHomeworkInsert(Request $request, $homework_id) 
        {
            // 1. Fetch the original homework details first
            $getHomework = HomeworkModel::getSingle($homework_id);
            
            // Check if homework exists to avoid errors
            if (empty($getHomework)) {
                abort(404);
            }

            $homework = new HomeworkSubmitModel;
            $homework->homework_id = $homework_id;
            $homework->student_id = Auth::user()->id;
            $homework->description = trim($request->message);

            if (!empty($request->file('document_file'))) {
                $file = $request->file('document_file');
                $ext = $file->getClientOriginalExtension();
                
                // 2. Use the data from the original homework record for the filename
                // This prevents the "null" error because $getHomework already contains these names
                $className = $getHomework->class_name; 
                $subjectName = $getHomework->subject_name;
                
                $filename = Str::slug($subjectName).'-'.Str::slug($className).'-'.date('d-m-Y').'-'.Str::random(5).'.'.$ext;
                
                // 3. Move file to a specific 'submission' folder (Optional but recommended)
                $file->move('upload/homework/', $filename);
                $homework->document_file = $filename;
            }

            $homework->save();
            return redirect('student/my_homework')->with('success', "Homework successfully Submitted");
        }

        public function submittedHomeworkStudent()
        {
            $data['getRecord'] = HomeworkSubmitModel::getRecordStudent(Auth::user()->id);
            $data['header_title']= 'My Submitted Home Work';
            return view('student.homework.submitted_list',$data);
        }

      
        public function editHomework($homework_id)
    {
        $data['getHomework'] = HomeworkModel::getSingle($homework_id);
        // Fetch the student's current submission to show previous description/file
        $data['getSubmission'] = HomeworkSubmitModel::where('homework_id', $homework_id)
                                                    ->where('student_id', Auth::user()->id)
                                                    ->first();
        
        $data['header_title'] = 'Edit Submitted Home Work';
        return view('student.homework.edit_submit', $data);
    }
public function submit_homework_insert(Request $request, $homework_id)
    {
        $getHomework = HomeworkModel::getSingle($homework_id);
        if (empty($getHomework)) {
            abort(404);
        }

        // 1. Deadline Check: Security guard to prevent post-deadline hacking
        $deadline = Carbon::parse($getHomework->submission_date)->endOfDay();
        if (Carbon::now()->gt($deadline)) {
            return redirect()->back()->with('error', "The deadline has passed. You can no longer modify this submission.");
        }

        // 2. Find existing submission (Update) or create new one (Insert)
        $homework = HomeworkSubmitModel::where('homework_id', $homework_id)
                                        ->where('student_id', Auth::user()->id)
                                        ->first();
        
        if (empty($homework)) {
            $homework = new HomeworkSubmitModel;
            $homework->homework_id = $homework_id;
            $homework->student_id = Auth::user()->id;
        }

        $homework->description = trim($request->message);

        // 3. File Handling
        if (!empty($request->file('document_file'))) {
            // Cleanup: Delete old file if it exists to save server space
            if (!empty($homework->document_file) && file_exists('upload/homework/'.$homework->document_file)) {
                unlink('upload/homework/'.$homework->document_file);
            }

            $file = $request->file('document_file');
            $ext = $file->getClientOriginalExtension();
            
            // Generate a clean, descriptive filename
            $fileName = Str::slug(Auth::user()->name) . '-' . Str::slug($getHomework->subject_name) . '-' . Str::random(5) . '.' . $ext;
            $file->move('upload/homework/', $fileName);
            $homework->document_file = $fileName;
        }

        $homework->save();

        return redirect('student/my_submitted_homework')->with('success', "Homework successfully saved.");
    }

    // Parent Side
    public function homeworkParent($student_id)
    {
        $getStudent = User::getSingle($student_id);
        
        $data['getRecord'] = HomeworkModel::getRecordStudent($getStudent->class_id, $getStudent->id);
        
        $data['getStudent'] = $getStudent;
        $data['header_title'] = 'Student Homework: ' . $getStudent->name;
        
        return view('parent.homework.list', $data);
    }
    public function submittedHomeworkParent($student_id)
    {
        $getStudent = User::getSingle($student_id);
        $data['getRecord'] = HomeworkSubmitModel::getRecordStudent($getStudent->id);
        $data['header_title']= 'Home Work';
        $data['getStudent'] = $getStudent;
        return view('parent.homework.submitted_list',$data);
    }
    
}
