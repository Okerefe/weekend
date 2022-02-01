<?php

namespace App\Http\Controllers;

use App\Models\Weekend;
use Illuminate\Http\Request;
use Mockery\Exception;
use Psr\Log\InvalidArgumentException;

class WeekendController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void|\Illuminate\Http\Response
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }



    /**
     * Calculate Method Handles Submission of the Date
     * For Calculations and Processes.
     *
     * @param  Request  $request
     * @return void|array|\Illuminate\Contracts\Support\Renderable
     */
    public function calculate(Request $request) {

        $request->validate([
            'start' => 'required|date',
            'end'   => 'required|date',
        ]);


        //Redundant Check for Exception that can be thrown due to Incorrect Date
        try {

            $weekend = new Weekend();
            $weekend->instantiate(
                $request->input('start'),
                $request->input('end')
            );
            $weekend->saveAndCreateFile();
            return[
                'no_of_weekends' => $weekend->no_of_weekend,
                'weekend_txt' => '/storage/' . $weekend->weekend_file_name . '.txt',
                'weekend_pdf' => '/storage/' . $weekend->weekend_file_name . '.pdf',
            ];

        } catch (\InvalidArgumentException $e) {
            abort(403, "Invalid Argument");
        }

    }
}
