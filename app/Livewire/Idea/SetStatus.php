<?php

namespace App\Livewire\Idea;

use App\Models\Comment;
use App\Models\Idea;
use App\Models\Status;
use App\Traits\Livewire\WithDispatchNotify;
use Livewire\Component;
use WireUi\Traits\Actions;

class SetStatus extends Component
{
    use Actions,
        WithDispatchNotify;

    public $idea;

    public $comment;

    public $statuses;

    public $status;

    public $notifyAllVoters;

    protected $listeners = ['openSetStatus'];

    protected function rules()
    {
        $status = $this->status;
        $idea = $this->idea;
        $statuses = $this->statuses->pluck('slug')->toArray();

        return [
            'status' => ['required', 'string', function ($attribute, $value, $fail) use ($status, $idea, $statuses) {
                if (! in_array($status, $statuses)) {
                    $fail(__('error.invalidstatus'));
                }
                if ($idea->status === $status) {
                    $fail(__('error.statusthesame'));
                }
            }],
        ];
    }

    public function openSetStatus()
    {
        // Reset the error
        $this->resetErrorBag();
    }

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
        $this->status = $this->idea->status;
        $this->statuses = Status::all();
    }

    public function setStatus()
    {
        if (! auth()->check() || ! auth()->user()->can('manage', $this->idea)) {
            $this->notification()->success(
                $description = __('general.actionnotallowed')
            );

            return;
        }

        $this->validate();

        // TODO: for notification bell
        // if ($this->notifyAllVoters) {
        //     NotifyAllVoters::dispatch($this->idea);
        // }

        $this->idea->status = $this->status;
        $this->idea->save();

        Comment::create([
            'user_id' => auth()->id(),
            'idea_id' => $this->idea->id,
            'is_status_update' => $this->status,
            'content' => $this->comment ?? __('general.nocommentadded'),
        ]);

        $this->comment = '';
        $this->notification()->success(
            $description = __('general.ideastatusupdatedsuccessfully')
        );
        // closeSetStatus
        $this->dispatch('closeSetStatus');
        $this->dispatch('statusWasUpdated');
        $this->dispatch('refreshIdeaShow');
    }

    public function render()
    {
        return view('livewire.idea.set-status');
    }
}
