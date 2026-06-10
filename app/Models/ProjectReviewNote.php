<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectReviewNote extends Model
{
    use SoftDeletes;

    protected $table = 'project_review_notes';

    protected $fillable = [
        'project_id',
        'estimate_discipline_id',
        'note',
        'mark_type',
        'reviewer_id',
        'position_x',
        'position_y',
    ];

    const MARK_OK       = 'ok';
    const MARK_WARNING  = 'warning';
    const MARK_REJECTED = 'rejected';
    const MARK_QUESTION = 'question';
    const MARK_NOTE     = 'note';

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
