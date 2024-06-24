<?php

namespace App\Traits\Livewire;

trait WithDispatchNotify
{
    public function dispatchNotify($type, $message)
    {
        $this->dispatch('dispatchnotify', [
            'message' => $message,
            'type' => $type,
        ]);
    }

    public function dispatchNotifySuccess($message)
    {
        $this->dispatchNotify('success', $message);
    }

    public function dispatchNotifySuccessDelete($message)
    {
        $this->dispatchNotify('delete', $message);
    }

    public function dispatchNotifyWarning($message)
    {
        $this->dispatchNotify('warning', $message);
    }

    public function sessionNotify($type, $message)
    {
        session()->flash('notify', [
            'message' => $message,
            'type' => $type,
        ]);
    }

    public function sessionNotifySuccess($message)
    {
        $this->sessionNotify('success', $message);
    }

    public function sessionNotifySuccessDelete($message)
    {
        $this->sessionNotify('success', $message);
    }
}
