<?php

namespace App\View\Components\Admin\Approvals;

use App\Models\UserChangeRequest;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ApprovalBadge extends Component
{
    public int $pendingCount;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->pendingCount = UserChangeRequest::where('status', 'pending')->count();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // return view('components.admin.approval-badge');
        return view('components.admin.approvals.approval-badge');
    }
}
