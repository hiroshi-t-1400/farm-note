<?php

namespace App\View\Components\Ui;

use App\Models\Admin\UserChange\UserChangeApplication;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class HamburgerBadge extends Component
{
    public bool $hasTask;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->hasTask =
            UserChangeApplication::query()
                ->where('status', UserChangeApplication::STATUS_PENDING)
                ->exists()
            || UserChangeApplication::query()
                ->whereNull('rejection_acknowledge_at')
                ->where('status', UserChangeApplication::STATUS_REJECTED)
                ->where('applied_by', Auth::user()->id)
                ->exists();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.hamburger-badge');
    }
}
