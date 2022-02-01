<?php

namespace Tests\Unit;

use App\Models\Weekend;
use App\Utility\WeekendPdf;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeekendTest extends TestCase {

    use withFaker, RefreshDatabase;

    public function dataForExceptionForWeekendInBetweenCalculations()
    {
        return [
            ['09/08/2020', '2020/30/09'],
            ['2013/08/19', '2013/03/15'],
            ['2000/01/01 00:00:00', '2020/09/21 04:40:00'],
            ['2000/01/01 00:00:00', '2013/03/15'],
        ];
    }


    public function dataForCorrectDateForWeekendInBetweenCalculations()
    {
        return [
            ['2020-10-03', '2020-10-05', 0],
            ['2020-10-03', '2020-10-18', 1],
            ['2020-10-01', '2020-10-31', 4],
            ['2020-10-04', '2020-10-25', 2],
        ];
    }




    /**
     * @test
     *
     * @return void
     */
    public function instantiate_method_sets_parameters_as_expected()
    {
        $weekend = $this->getMockBuilder(Weekend::class)
            ->onlyMethods(['calculateWeekendsBetweenDates'])
            ->getMock();

        $weekend->expects($this->once())
            ->method('calculateWeekendsBetweenDates')
            ->with('2020-10-01', '2020-10-31')
            ->willReturn(4);


        $weekend->instantiate('2020-10-01', '2020-10-31');
        $this->assertSame( 4, $weekend->no_of_weekend);
        $this->assertSame( '2020-10-01', $weekend->start_date);
        $this->assertSame( '2020-10-31', $weekend->end_date);
    }

    /**
     * @test
     *
     * @return void
     */
    public function instantiate_method_throws_exception_gotten_from_calculate_weekend_function()
    {
        $weekend = $this->getMockBuilder(Weekend::class)
            ->onlyMethods(['calculateWeekendsBetweenDates'])
            ->getMock();

        $weekend->expects($this->once())
            ->method('calculateWeekendsBetweenDates')
            ->with('2020-10-01', '2020-10-31')
            ->willThrowException(new \InvalidArgumentException('Yea, I am a Test so you should see me.'));


        $this->expectException('InvalidArgumentException');
        $weekend->instantiate('2020-10-01', '2020-10-31');
    }

    /**
     * @test
     *
     * @dataProvider dataForExceptionForWeekendInBetweenCalculations
     * @param $start
     * @param $end
     * @return void
     */
    public function calculation_of_weekends_between_dates_throws_exception_because_of_wrong_date_format($start, $end)
    {
        $weekendCalc = new Weekend();
        $this->expectException('InvalidArgumentException');
        $weekendCalc->calculateWeekendsBetweenDates($start, $end);
    }

    /**
     * @test
     *
     * @dataProvider dataForCorrectDateForWeekendInBetweenCalculations
     * @param $start
     * @param $end
     * @param $result
     * @return void
     */
    public function calculation_of_weekends_between_dates_returns_correct_values($start, $end, $result)
    {
        $weekendCalc = new Weekend();
        $this->assertSame($result, $weekendCalc->calculateWeekendsBetweenDates($start, $end));
    }


    /**
     * @test
     *
     * @dataProvider dataForCorrectDateForWeekendInBetweenCalculations
     * @param $start
     * @param $end
     * @param $result
     * @return void
     */
    public function calculation_of_weekends_between_dates_saves_properties_properly($start, $end, $result)
    {
        $weekend = new Weekend();
        $this->assertSame($result, $weekend->calculateWeekendsBetweenDates($start, $end));
        $weekend->calculateWeekendsBetweenDates($start, $end);
        $this->assertInstanceOf(\DateTime::class,$weekend->startDateObject);
        $this->assertInstanceOf(\DateTime::class,$weekend->endDateObject);
    }


    /**
     * @test
     *
     * @return void
     */
    public function save_and_create_file_properly_returns_file_name_and_weekend_amount()
    {
        $weekend = $this->getMockBuilder(Weekend::class)
            ->onlyMethods(['save', 'storeWeekendResultAsFiles'])
            ->getMock();

        $weekend->expects($this->once())
            ->method('save');

        $weekend->expects($this->once())
            ->method('storeWeekendResultAsFiles')
            ->willReturn('somefilenames');

        $this->assertSame('somefilenames', $weekend->saveAndCreateFile());

    }


    /**
     * @test
     *
     * @return void
     */
    public function store_weekend_as_result_calls_necessary_functions()
    {
        $weekend = $this->getMockBuilder(Weekend::class)
            ->onlyMethods(['storeTxtFile', 'storePdfVersion', 'randomName', 'save'])
            ->getMock();

        $weekend->expects($this->once())
            ->method('randomName')
            ->willReturn('randomname');

        $weekend->expects($this->once())
            ->method('save');

        $weekend->expects($this->once())
            ->method('storeTxtFile')
            ->with('public/weekendRequests/randomname', 'local');

        $weekend->expects($this->once())
            ->method('storePdfVersion')
            ->with('public/weekendRequests/randomname');

        $this->assertSame('public/weekendRequests/randomname', $weekend->storeWeekendResultAsFiles());
    }

    /**
     * @test
     *
     * @return void
     */
    public function store_txt_file_successfully_stores_file()
    {
        $weekend = new Weekend();
        $weekend->no_of_weekend = 5;

        Storage::fake('local');
        $weekend->storeTxtFile("weekendRequests/somefiles", 'local');

        Storage::assertExists("weekendRequests/somefiles.txt");
        $this->assertStringContainsString("RAVEN WEEKEND APP", Storage::get("weekendRequests/somefiles.txt"));
        $this->assertStringContainsString("5", Storage::get("weekendRequests/somefiles.txt"));
        Storage::delete("weekendRequests/somefiles.txt");
    }

    /**
     * @test
     * @return void
     */
    public function store_pdf_files_works_as_expected()
    {
        $weekend = $this->getMockBuilder(Weekend::class)
            ->onlyMethods(['weekendPdf'])
            ->getMock();

        $weekendPdf = $this->getMockBuilder(WeekendPdf::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['save'])
            ->getMock();

        $weekendPdf->expects($this->once())
            ->method('save');

        $weekend->expects($this->once())
            ->method('weekendPdf')
            ->with('filepath')
            ->willReturn($weekendPdf);

        $weekend->storePdfVersion('filepath');
    }


    /**
     * @test
     * @return void
     */
    public function random_names_performs_as_expected()
    {
        $weekend = new Weekend();
        $weekend->id = 35;
        $this->assertStringContainsString('weekends', $weekend->randomName());
        $this->assertStringContainsString(35, $weekend->randomName());
    }


}
