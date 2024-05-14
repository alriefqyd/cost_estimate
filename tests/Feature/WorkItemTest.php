<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkItem;
use App\Models\WorkItemType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;


class WorkItemTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_generate_work_item_code()
    {

        $workItemType = WorkItemType::create([
            'code' => '9090',
            'description' => 'testing work item type'
        ]);

        $codeWorkItemGenerate = '';
        // Create an array of codes from 1 to 6
        $codes = range(1, 6);

        // Shuffle the array to randomize the ordering
        shuffle($codes);

        // Create work items with randomized codes
        foreach ($codes as $i) {
            if($i == 5 || $i == 3) continue;
            $codeWorkItemGenerate = '9090.0' . $i;
            WorkItem::create([
                'work_item_type_id' => $workItemType->id,
                'code' => $codeWorkItemGenerate
            ]);
        }

        $user = User::create([
            'user_name' => 'alriefqyd',
            'email_verified_at' => '2024-05-13 11:03:38',
            'password' => Hash::make('password'),
            'profile->id' => 1
        ]);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])->json('GET', '/getNumChildType/'. $workItemType->id);
        // Assert the response
        $response->assertStatus(200)
            ->assertJson(['status' => 200]);

        $data = $response->json('data');
        $this->assertEquals('9090.03', $data);

    }
}
