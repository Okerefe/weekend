<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WeekendTest extends TestCase
{

    use withFaker, RefreshDatabase;

    /**
     * @test
     *
     */
    public function dummyTest()
    {
        $this->assertSame('Comment Out Feature Testing','Comment Out Feature Testing');
    }

    //TODO Comment Out Feature Testing till PDF Creation can be Saved to the mock testing storage
    // Right Now if Feature Tests are runned. the main storage is populated.


    //public function dataForCorrectDateForWeekendInBetweenCalculations()
    //{
    //    return [
    //        ['2020-10-03', '2020-10-05', 0],
    //        ['2020-10-03', '2020-10-18', 1],
    //        ['2020-10-01', '2020-10-31', 4],
    //        ['2020-10-04', '2020-10-25', 2],
    //    ];
    //}
    //
    //public function dataForExceptionForWeekendInBetweenCalculations()
    //{
    //    return [
    //        ['2013/08/19', '2013/03/15'],
    //        ['2000/01/01 00:00:00', '2020/09/21 04:40:00'],
    //        ['2000/01/01 00:00:00', '2013/03/15'],
    //    ];
    //}


    ///**
    // * @test
    // *
    // * @dataProvider dataForCorrectDateForWeekendInBetweenCalculations
    // * @param $start
    // * @param $end
    // * @param $result
    // * @return void
    // */
    //public function weekend_end_point_returns_accurate_data($start, $end, $result)
    //{
    //    //Storage::fake('local');
    //    $this->post('/calculate', ['start' => $start, 'end' => $end])
    //        ->assertStatus(200)
    //        ->assertSee("no_of_weekends")
    //        ->assertSee($result);
    //}


    ///**
    // * @test
    // *
    // * @dataProvider dataForExceptionForWeekendInBetweenCalculations
    // * @param $start
    // * @param $end
    // * @return void
    // */
    //public function returns_error_if_data_other_than_standard_html_date_format_is_sent($start, $end)
    //{
    //    Storage::fake('local');
    //    $this->post('/calculate', ['start' => $start, 'end' => $end])
    //        ->assertStatus(403);
    //}
    //
    ///**
    // * @test
    // *
    // * @return void
    // */
    //public function weekend_info_is_successfully_saved_in_db()
    //{
    //
    //    Storage::fake('local');
    //    $response = $this->post('/calculate', ['start' => "2020-10-01", 'end' => "2020-10-31"])
    //        ->assertStatus(200);
    //
    //    //echo var_dump($response);
    //    $this->assertDatabaseHas('weekends', [
    //        'start_date' => '2020-10-01',
    //        'end_date' => "2020-10-31",
    //    ]);
    //}

}
