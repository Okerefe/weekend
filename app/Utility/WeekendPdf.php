<?php
# -*- coding: utf-8 -*-
/*
 * Creates a PDF File that contains Weekend Report.
 *
 * (c) Umukoro Okerefe
 *
 */

namespace App\Utility;

use App\Models\Weekend;

class WeekendPdf extends \TCPDF {

    /**
     * @var Weekend Contains an instance of the Weekend Object.
     */
    public $weekend;


    /**
     * @var string Contains File Path where file should be changed.
     */
    public $filePath;



    public function __construct($filePath, $weekend)
    {

        $this->weekend = $weekend;
        $this->filePath = $filePath;
        parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    }

    /**
     * @inheritDoc
     */
    public function Header() {
        // Logo
        $image_file = __DIR__ . '/../../resources/img/logo.png';
        $this->Image($image_file, 10, 3, 25, '', 'PNG', '', 'T', false, 300, '', false, false, 1, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Ln();
        $this->Cell(0, 15, 'Raven Weekend PDF Report', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    /**
     * @inheritDoc
     */
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    /**
     * Saves the PDF file to Storage
     * After making some basic configurations.
     *
     * @return  void
     */
    public function save()
    {
        // set document information
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Umukoro Okerefe');
        $this->SetTitle('Raven Weekend App');
        $this->SetSubject('Raven Weekend App');
        $this->SetKeywords('Raven, PDF, Weekend');

        // set default header data
        $this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        $this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set font
        $this->SetFont('times', 'BI', 12);

        // add a page
        $this->AddPage();

        // set some text to print
        $html = "<br/><h1 style='color:green; margin: 0 auto; text-align:center; margin-top:10px;' >Raven Weekend App Official PDF Report</h1>
                    <p style='text-align:center; color: green;'>No. Of Weekends Between {$this->weekend->start_date} and {$this->weekend->end_date} is <span style='font-size:18px;color:green;'>{$this->weekend->no_of_weekend}</span> Weekends</p>";

        // print a block of html using WriteHTMLCell()
        $this->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        // ---------------------------------------------------------

        //Close and output PDF document
        $this->Output(__DIR__ . '/../../storage/app/'. $this->filePath . '.pdf', 'F');

    }
}
?>
