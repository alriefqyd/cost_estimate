{{-- Mini progress stepper for a discipline's estimate pipeline: Draft -> In Review -> Approved/Rejected.
     Expects $stage = ['step' => 1|2|3, 'state' => 'draft'|'review'|'approved'|'rejected', 'label' => string]
     Optional $reviewer = assigned discipline reviewer's full name (appended as "… by <name>" once in review). --}}
@php
    $step  = $stage['step'];
    $state = $stage['state'];
    $isRejected = $state === 'rejected';
    $reviewer = $reviewer ?? null;

    // Draft stays as-is; once the estimate is published the assigned reviewer owns the item.
    $labelText = $stage['label'];
    if ($reviewer && in_array($state, ['review', 'approved', 'rejected'], true)) {
        $labelText .= ' by ' . $reviewer;
    }
@endphp
<div class="disc-progress-row">
    <span class="disc-progress" title="{{ $labelText }}">
        <span class="disc-progress-dot {{ $step >= 1 ? 'is-done' : '' }} {{ $step == 1 ? 'is-current' : '' }}"></span>
        <span class="disc-progress-bar {{ $step >= 2 ? 'is-done' : '' }}"></span>
        <span class="disc-progress-dot {{ $step >= 2 ? 'is-done' : '' }} {{ $step == 2 ? 'is-current' : '' }}"></span>
        <span class="disc-progress-bar {{ $step >= 3 ? ($isRejected ? 'is-rejected' : 'is-done') : '' }}"></span>
        <span class="disc-progress-dot disc-progress-dot-final {{ $step >= 3 ? ($isRejected ? 'is-rejected' : 'is-done') : '' }}"></span>
    </span>
    <span class="disc-progress-label disc-progress-label-{{ $state }}">{{ $labelText }}</span>
</div>
