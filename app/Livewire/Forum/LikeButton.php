<?php
namespace App\Livewire\Forum;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class LikeButton extends Component
{
    public $model;
    public $likesCount;
    public $isLiked;

    public function mount($model)
    {
        $this->model = $model;
        $this->updateLikeStatus();
    }

    protected function updateLikeStatus()
    {
        $this->likesCount = $this->model->likes()->count();
        
        $this->isLiked = auth()->check() 
            ? $this->model->likes()->where('user_id', auth()->id())->exists()
            : false;
    }

    public function toggleLike()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        try {
            $existingLike = $this->model->likes()
                ->where('user_id', auth()->id())
                ->first();

            if ($existingLike) {
                $existingLike->delete();
            } else {
                $this->model->likes()->create([
                    'user_id' => auth()->id()
                ]);
            }

            $this->updateLikeStatus();

        } catch (\Exception $e) {
            Log::error('Like Toggle Error', [
                'error' => $e->getMessage(),
                'model_type' => get_class($this->model),
                'model_id' => $this->model->id,
                'user_id' => auth()->id()
            ]);

            session()->flash('error', 'Unable to process like/unlike');
        }
    }

    public function render()
    {
        return view('livewire.forum.like-button');
    }
}