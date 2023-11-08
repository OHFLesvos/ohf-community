<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Visitors\Visitor;
use App\Models\Visitors\VisitorCheckin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCheckinsPerDay()
    {
        $v1 = Visitor::factory()->create();
        $v1->checkins()->save(VisitorCheckin::createForDate('2023-11-07'));
        $v1->checkins()->save(VisitorCheckin::createForDate('2023-11-08'));

        $v2 = Visitor::factory()->create();
        $v2->checkins()->save(VisitorCheckin::createForDate('2023-11-07'));

        /** @var User $authUser */
        $authUser = $this->makeUserWithPermission('visitors.reports');

        $response = $this->actingAs($authUser)
            ->getJson('api/visitors/checkins/daily', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'checkin_date' => '2023-11-08',
                        'checkin_count' => 1,
                    ],
                    [
                        'checkin_date' => '2023-11-07',
                        'checkin_count' => 2,
                    ],
                ],
            ]);
    }

    public function testCheckinsPerMonth()
    {
        $v1 = Visitor::factory()->create();
        $v1->checkins()->save(VisitorCheckin::createForDate('2023-11-07'));
        $v1->checkins()->save(VisitorCheckin::createForDate('2023-11-08'));

        $v2 = Visitor::factory()->create();
        $v2->checkins()->save(VisitorCheckin::createForDate('2023-11-07'));
        $v2->checkins()->save(VisitorCheckin::createForDate('2023-10-23'));

        /** @var User $authUser */
        $authUser = $this->makeUserWithPermission('visitors.reports');

        $response = $this->actingAs($authUser)
            ->getJson('api/visitors/checkins/monthly', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'checkin_month' => '2023-11',
                        'checkin_count' => 3,
                    ],
                    [
                        'checkin_month' => '2023-10',
                        'checkin_count' => 1,
                    ],
                ],
            ]);
    }

    public function testCheckinsPerYear()
    {
        $v1 = Visitor::factory()->create();
        $v1->checkins()->save(VisitorCheckin::createForDate('2023-11-07'));
        $v1->checkins()->save(VisitorCheckin::createForDate('2023-11-08'));

        $v2 = Visitor::factory()->create();
        $v2->checkins()->save(VisitorCheckin::createForDate('2023-11-07'));

        $v3 = Visitor::factory()->create();
        $v3->checkins()->save(VisitorCheckin::createForDate('2022-11-07'));


        /** @var User $authUser */
        $authUser = $this->makeUserWithPermission('visitors.reports');

        $response = $this->actingAs($authUser)
            ->getJson('api/visitors/checkins/yearly', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'checkin_year' => '2023',
                        'checkin_count' => 3,
                    ],
                    [
                        'checkin_year' => '2022',
                        'checkin_count' => 1,
                    ],
                ],
            ]);
    }
}
