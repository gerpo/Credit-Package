<?php

namespace DmsCredits\Tests;

use Carbon\Carbon;

class CreditStatisticsControllerTest extends TestCase
{
    /** @test */
    public function admin_can_fetch_statistical_data(): void
    {
        $this->signInAdmin();
        createCode(500);

        $expected = $this->expectedResponse();

        $this->get(route('credits.statistics.index'))->assertExactJson($expected);
    }

    private function expectedResponse($overwrites = []): array
    {
        return array_merge([
            'start_timestamp' => Carbon::today()->subYear(),
            'end_timestamp'   => Carbon::today()->addDay(),
            'type'            => 'monthly',
            'period_data'     => [
                Carbon::today()->format('m.y') => [
                    'created_codes' => 1,
                    'created_value' => 500,
                    'used_codes'    => 0,
                    'used_value'    => 0,
                    'month'         => Carbon::today()->format('m.y'),
                ],
            ],
            'total_data' => [
                'created_codes' => 1,
                'created_value' => 500,
                'used_codes'    => 0,
                'used_value'    => 0,
            ],
            'creator_data' => [
                [
                    'created_codes' => 1,
                    'created_value' => 500,
                    'used_codes'    => 0,
                    'used_value'    => 0,
                    'created_by'    => 2,
                    'creator'       => [
                        'id'        => 2,
                        'firstname' => null,
                        'lastname'  => null,
                        'username'  => null,
                    ],
                ],
            ],
        ], $overwrites);
    }

    /** @test */
    public function authorized_user_can_fetch_statistical_data(): void
    {
        $this->signIn(null, 'view_code_statistics');
        createCode(500);

        $expected = $this->expectedResponse();

        $this->get(route('credits.statistics.index'))->assertExactJson($expected);
    }

    /** @test */
    public function unauthorized_user_cannot_fetch_statistical_data(): void
    {
        $this->signIn();
        createCode();

        $this->get(route('credits.statistics.index'))->assertStatus(403);
    }
}
