<?php

namespace App\Livewire;

use App\Models\Expense;
use Livewire\Component;

class ShowPosts extends Component
{
    public function delete($postId)
    {
        $post = Expense::find($postId);

        // Authorization...

        $post->delete();

        sleep(1);
    }

    public function render()
    {
        $posts = Expense::latest()->take(5)->get();

        return view('livewire.show-posts', [
            'posts' => $posts,
        ]);
    }
}
