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
            ->getJson('api/visitors/report/checkins?granularity=days&date_start=2023-11-01&date_end=2023-11-08', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'checkin_date_range' => '2023-11-08',
                        'checkin_count' => 1,
                    ],
                    [
                        'checkin_date_range' => '2023-11-07',
                        'checkin_count' => 2,
                    ],
                    [
                        'checkin_date_range' => '2023-11-06',
                        'checkin_count' => 0,
                    ],
                    [
                        'checkin_date_range' => '2023-11-05',
                        'checkin_count' => 0,
                    ],
                    [
                        'checkin_date_range' => '2023-11-04',
                        'checkin_count' => 0,
                    ],
                    [
                        'checkin_date_range' => '2023-11-03',
                        'checkin_count' => 0,
                    ],
                    [
                        'checkin_date_range' => '2023-11-02',
                        'checkin_count' => 0,
                    ],
                    [
                        'checkin_date_range' => '2023-11-01',
                        'checkin_count' => 0,
                    ],
                ],
            ]);
    }

    public function testCheckinsPerWeek()
    {
        $v1 = Visitor::factory()->create();
        $v1->checkins()->save(VisitorCheckin::createForDate('2023-11-07'));
        $v1->checkins()->save(VisitorCheckin::createForDate('2023-11-08'));

        $v2 = Visitor::factory()->create();
        $v2->checkins()->save(VisitorCheckin::createForDate('2023-11-07'));
        $v2->checkins()->save(VisitorCheckin::createForDate('2023-11-01'));

        /** @var User $authUser */
        $authUser = $this->makeUserWithPermission('visitors.reports');

        $response = $this->actingAs($authUser)
            ->getJson('api/visitors/report/checkins?granularity=weeks&date_start=2023-11-01&date_end=2023-11-08', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'checkin_date_range' => '2023-W45',
                        'checkin_count' => 3,
                    ],
                    [
                        'checkin_date_range' => '2023-W44',
                        'checkin_count' => 1,
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
            ->getJson('api/visitors/report/checkins?granularity=months&date_start=2023-10-01&date_end=2023-11-08', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'checkin_date_range' => '2023-11',
                        'checkin_count' => 3,
                    ],
                    [
                        'checkin_date_range' => '2023-10',
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
            ->getJson('api/visitors/report/checkins?granularity=years&date_start=2022-11-01&date_end=2023-11-08', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'checkin_date_range' => '2023',
                        'checkin_count' => 3,
                    ],
                    [
                        'checkin_date_range' => '2022',
                        'checkin_count' => 1,
                    ],
                ],
            ]);
    }

    public function testListCheckinPurposes()
    {
        $v1 = Visitor::factory()->create();
        $v1->checkins()->save(VisitorCheckin::createForDate('2023-11-07', 'aaa'));
        $v1->checkins()->save(VisitorCheckin::createForDate('2023-11-08', 'ccc'));

        $v2 = Visitor::factory()->create();
        $v2->checkins()->save(VisitorCheckin::createForDate('2023-11-07', 'aaa'));

        $v3 = Visitor::factory()->create();
        $v3->checkins()->save(VisitorCheckin::createForDate('2022-11-07', 'bbb'));

        /** @var User $authUser */
        $authUser = $this->makeUserWithPermission('visitors.reports');

        $response = $this->actingAs($authUser)
            ->getJson('api/visitors/report/listCheckinPurposes', []);

        $this->assertAuthenticated();
        $response->assertOk()
            ->assertJson([
                'aaa',
                'bbb',
                'ccc',
            ]);
    }
}
