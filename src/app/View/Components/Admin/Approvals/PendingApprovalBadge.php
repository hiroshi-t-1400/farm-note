<?php

namespace App\View\Components\Admin\Approvals;

use App\Models\Admin\UserChange\UserChangeApplication;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PendingApprovalBadge extends Component
{

    public int $pendingCount;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->pendingCount = UserChangeApplication::query()
            ->where('status', UserChangeApplication::STATUS_PENDING)
            ->count();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.approvals.pending-approval-badge');
    }
}
