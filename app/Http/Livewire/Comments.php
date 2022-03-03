<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Comment;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Comments extends Component
{

    use WithPagination;
    use WithFileUploads;

    public $photo;
    public $iteration;
    
    // public $comments;

    public $newComment;

    // กำหนด Rule สำหรับ Validate
    protected $rules = [
        'newComment' => 'required|min:5|max:128',
        'photo' => 'image|max:2048'
    ];

    // กำหนดข้อความ Validate
    protected $messages = [
       'newComment.required' => 'This :attribute cannot be empty.',
       'newComment.min' => 'This :attribute must longer than :min character',
       'newComment.max' => 'This :attribute must less than :max character',
       'photo.image' => 'The :attribute must be image only',
       'photo.max' => 'The :attribute must less than :max bytes size'
    ];

    // Method for initailizing
    public function mount(){
        // $initialComments = Comment::latest()->get();
        // $this->comments = $initialComments;
    }

    // Realtime Validation
    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function addComment(){

        $this->validate();

        if($this->newComment == ""){
            return;
        }

        $createdComment = Comment::create(
            [
                'body' => $this->newComment,
                'user_id' => 1
            ]
        );

        // เช็คว่ามีการเลือกไฟล์ภาพเข้ามาหรือไม่
        if($this->photo){
            $this->photo->store('images/comments','public');
        }

        // $this->comments->prepend($createdComment);

        // Clear input field
        $this->newComment = "";
        $this->photo = null;
        $this->iteration++;

        // แสดงข้อความ flash message
        session()->flash('message_add', 'Comment successfully added.');
    }

    // ฟังก์ชันในการลบข้อมูล
    public function remove($commentId){
        // dd($commentId);
        $comment = Comment::find($commentId);
        $comment->delete();
        // $this->comments = $this->comments->except($commentId);
        session()->flash('message_delete', 'Comment successfully deleted.');
    }

    public function render()
    {
        return view('livewire.comments', [
            'comments' => Comment::latest()->paginate(5)
        ]);
    }
}
