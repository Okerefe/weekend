<?php

namespace App\Models;

use App\Utility\WeekendPdf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;


class Weekend extends Model
{
    use HasFactory;

    public $startDateObject;

    public $endDateObject;


    /**
     * @var string Contains Directory Name where Weekend Calculation results are stored
     */
    const WEEKEND_STORAGE_DIRECTORY = "public/weekendRequests";

    /**
     * @var string Contains Directory Name where Weekend Calculation results are stored
     *              for public access.
     */
    const WEEKEND_PUBLIC_DIRECTORY = "weekendRequests";


    /**
     * Sets Up the Weekend Object with given date parameters
     *
     * It takes in the Start and End Date, Calculates the Weekend
     * And also sets the Model's Variables
     *
     * @param string $startDate
     * @param string $endDate
     */
    public function instantiate(string $startDate, string $endDate)
    {
        $this->no_of_weekend = $this->calculateWeekendsBetweenDates($startDate, $endDate);
        $this->start_date = $startDate;
        $this->end_date = $endDate;
    }

    /**
     * Takes in a Start Date and End Date, and Returns the number of weekends In between them
     *
     * It assumes that a Full Weekend is from 00:00HRS on Saturday to 00:00HRS on Monday
     * Format of Date to be passed in is: "Y-m-d"
     *
     * @param string $start      String Containing Start Date
     * @param string $end        String Containing End Date
     *
     * @throws \InvalidArgumentException
     * @return int
     */
    public function calculateWeekendsBetweenDates(string $start, string $end) : int
    {
        $this->startDateObject = \DateTime::createFromFormat('Y-m-d', $start);
        $this->endDateObject = \DateTime::createFromFormat('Y-m-d', $end);

        if ($this->startDateObject === false || $this->endDateObject === false) {
            throw new \InvalidArgumentException("Incorrect date string");
        }

        $daysInBetween = (int) $this->endDateObject->diff($this->startDateObject)->format("%a");
        $noOfWeekends = floor($daysInBetween/7);


        //Days Remaining after removing Full Weeks
        $overflowDays = ($daysInBetween % 7);

        //The number representation of the week day of start date
        $startDayNum = (int) $this->startDateObject->format("N");

        //If the start day is greater than Monday,eg. (it falls between Tues and Fri)
        //There is a Chance of another Weekend been added from the overflow days
        if($startDayNum > 1 ) {
            $addedDays = $startDayNum + $overflowDays;
            if($addedDays >= 8) {
                $noOfWeekends++;
            }
        }

        //If the Start day falls on Saturday or Sunday, We loose a Weekend
        //That's if we have up to one Weekend already
        if($noOfWeekends > 0) {
            if($startDayNum == 6 || $startDayNum == 7) {
                --$noOfWeekends;
            }
        }

        return (int) $noOfWeekends;
    }


    /**
     * Saves File to DB and Calls functions that saves it to file.
     *
     * @return string   File name of saved file.
     */
    public function saveAndCreateFile() : string
    {
        $this->save();
        return $this->storeWeekendResultAsFiles();
    }


    /**
     * Stores Result of "no of Weekends In between Dates" to File
     *
     * @param string $disk        Disk in which File would be stored
     *
     * @return string             File name of saved file.
     */
    public function storeWeekendResultAsFiles(string $disk = 'local') : string
    {
        //Obtain a Unique File Name
        $randomName = $this->randomName();

        $filePath = self::WEEKEND_STORAGE_DIRECTORY . "/" . $randomName;

        $this->weekend_file_name = self::WEEKEND_PUBLIC_DIRECTORY . '/' . $randomName;

        //Save Model again after Weekend File have been sent..
        $this->save();

        $this->storeTxtFile($filePath, $disk);
        $this->storePdfVersion($filePath);
        return $filePath;
    }

    /**
     * Stores txt file to Disk
     *
     * @param string $filePath      Filepath where file is to be stored
     * @param string $disk          Disk in which File would be stored
     *
     * @return void
     */
    public function storeTxtFile($filePath, $disk)
    {
        $content = "RAVEN WEEKEND APP\n No Of Between {$this->start_date} and {$this->end_date} Is: {$this->no_of_weekend}";
        Storage::disk($disk)->put($filePath . '.txt', $content);
    }


    /**
     * Getter Function returns Instance of WeekendPdf
     * Created to make code testable.
     *
     * @param string $filePath      Filepath where file is to be stored
     *
     * @return WeekendPdf           Instance of WeekendPdf
     */
    public function weekendPdf($filePath) : WeekendPdf
    {
        return new WeekendPdf($filePath, $this);
    }


    /**
     * Stores PDF version of Weekend Report.
     *
     * @param string $filePath      Filepath where file is to be stored
     *
     * @return void
     */
    public function storePdfVersion($filePath)
    {
        $this->weekendPdf($filePath)->save();
    }


    /**
     * Returns Random Name that would be used to save Weekend Calculation Results
     *
     * @return string
     */
    public function randomName() : string
    {
        return (string)('weekends_' . mt_rand(1000,5000) . uniqid() . '_' . $this->id);
    }

}
