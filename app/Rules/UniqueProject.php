<?php

namespace App\Rules;

use App\Models\Project;
use Illuminate\Contracts\Validation\Rule;

class UniqueProject implements Rule
{

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->project_id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Retrieve the 'project_no' from the request input
        $project_no = request()->input('project_no');
        // Start by querying for projects with the same project_title and project_no
        $query = Project::where('project_title', $value)
            ->where('project_no', $project_no);
        // If $this->project_id is set, exclude the current project from the query
        if (isset($this->project_id)) {
            $query->where('id', '!=', $this->project_id);
        }
        // Count the number of matching projects
        $existing = $query->count();
        // If an existing project is found, return false to indicate validation failure
        return $existing == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Project Title and Project Name already registered';
    }
}
