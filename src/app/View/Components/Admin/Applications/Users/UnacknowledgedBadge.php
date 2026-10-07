<?php

namespace App\View\Components\Admin\Applications\Users;

use App\Models\Admin\UserChange\UserChangeApplication;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class UnacknowledgedBadge extends Component
{
    public int $pendingCount;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->pendingCount = UserChangeApplication::query()
            ->whereNull('rejection_acknowledge_at')
            ->where('status', UserChangeApplication::STATUS_REJECTED)
            ->where('applied_by', Auth::user()->id)
            ->count();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.applications.users.unacknowledged-badge');
    }
}
