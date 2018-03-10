<?php

namespace Morph\Reports;

use Carbon\Carbon;

class EvaluationReport extends AbstractReport
{
    protected $questionCategories;

    public function __construct($collection, $questionCategories)
    {
        parent::__construct($collection);
        $this->questionCategories = $questionCategories;
    }

    public function generate()
    {
        $this->CoverSheet()->StepSheets();

        return $this;
    }

    protected function CoverSheet()
    {
        $activeSheet = $this->addSheet("Cover");
        $row = $this->startRow;
        $report = $this->getCollection();
        $evaluation = $report->evaluation;

        $activeSheet->getColumnDimension("A")->setWidth(10);
        $activeSheet->getColumnDimension("B")->setWidth(25);
        $activeSheet->getColumnDimension("C")->setWidth(10);
        $activeSheet->getColumnDimension("D")->setWidth(10);
        $activeSheet->getColumnDimension("E")->setWidth(10);
        $activeSheet->getColumnDimension("F")->setWidth(40);

        $Title = "SCI Evaluation Report Quality Assessment Tool";
        $Version = "v. February 2017";

        /* Set STC Logo */
        $activeSheet->mergeCells("A" . $row . ":B" . ($row + 1));
        $objDrawing = new \PHPExcel_Worksheet_Drawing();
        $objDrawing->setPath(__DIR__ . '/logo/stc-logo.png')
            ->setResizeProportional(true)
            ->setWidth(200)
            ->setCoordinates('A' . $row)
            ->setWorksheet($activeSheet);

        /* Cover title*/
        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("C" . $row, $Title);
        $activeSheet->getStyle("C" . $row)->getFont()->applyFromArray([
            "size" => 16,
            "bold" => true
        ]);
        $row += 1;
        /* Version */
        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("C" . $row, $Version);
        $activeSheet->getStyle("C" . $row)->getFont()->applyFromArray([
            "size" => 10,
            "italic" => true
        ]);
        $row += 2;

        /* Evaluation summary table */
        $IntroRow = $row;
        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Title of evaluation")
            ->setCellValue("C" . $row, ": " . $evaluation->title);
        $activeSheet->getStyle("B" . $row)->getFont()->setBold(true);
        $row += 1;
        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Country")
            ->setCellValue("C" . $row, ": ");
        $activeSheet->getStyle("B" . $row)->getFont()->setBold(true);
        $row += 1;
        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Theme")
            ->setCellValue("C" . $row, ": " . $evaluation->theme->title);
        $activeSheet->getStyle("B" . $row)->getFont()->setBold(true);
        $row += 1;

        $Subthemes = "";
        foreach($evaluation->subthemes as $subtheme)
            $Subthemes .= $subtheme->title .", ";

        $activeSheet->setCellValue("B" . $row, "Subtheme/s");
        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("C" . $row, ": " . rtrim($Subthemes, ", "));
        $activeSheet->getStyle("B" . $row)->getFont()->setBold(true);
        $row += 1;

        $FinishedAt = new Carbon($evaluation->finished_at);

        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Date submission")
            ->setCellValue("C" . $row, ": " . $FinishedAt->format('d M Y'));
        $activeSheet->getStyle("B" . $row)->getFont()->setBold(true);
        $row += 1;
        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $row += 1;
        $Log = $report->logs->first();
        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Your name")
            ->setCellValue("C" . $row, ": " . $Log->user->profile->name);
        $activeSheet->getStyle("B" . $row)->getFont()->setBold(true);
        $row += 1;
        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Designation")
            ->setCellValue("C" . $row, ": ");
        $activeSheet->getStyle("B" . $row)->getFont()->setBold(true);
        $row += 1;
        $ActivatedAt = new Carbon($evaluation->updated_at);

        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Date of request for scoring received")
            ->setCellValue("C" . $row, ": " . $ActivatedAt->format('d M Y'));
        $activeSheet->getStyle("B" . $row)->getFont()->setBold(true);
        $row += 1;

        $SubmitedAt = new Carbon($Log->updated_at);

        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Date of score submission")
            ->setCellValue("C" . $row, ": " . $SubmitedAt->format('d M Y'));
        $activeSheet->getStyle("B" . $row)->getFont()->setBold(true);
        $activeSheet->getStyle("B" . $IntroRow . ":F" . $row)->getBorders()->applyFromArray(
            [
                "allborders" => [
                    "style" => \PHPExcel_Style_Border::BORDER_THIN,
                    "color" => [
                        "rgb" => "DDDDDD"
                    ]
                ]
            ]
        );
        $activeSheet->getStyle("B" . $IntroRow . ":F" . $row)->getBorders()->applyFromArray(
            [
                "top" => [
                    "style" => \PHPExcel_Style_Border::BORDER_THICK,
                    "color" => [
                        "rgb" => "000000"
                    ]
                ],
                "right" => [
                    "style" => \PHPExcel_Style_Border::BORDER_THICK,
                    "color" => [
                        "rgb" => "000000"
                    ]
                ],
                "bottom" => [
                    "style" => \PHPExcel_Style_Border::BORDER_THICK,
                    "color" => [
                        "rgb" => "000000"
                    ]
                ],
                "left" => [
                    "style" => \PHPExcel_Style_Border::BORDER_THICK,
                    "color" => [
                        "rgb" => "000000"
                    ]
                ]
            ]
        );
        $row += 2;
        $TotalSheets = $this->questionCategories->count();
        /* SCORE instruction heading */
        $activeSheet->mergeCells("B" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, sprintf("SCORE (Please add your total score from the ".$TotalSheets." categories/sheets)"));
        $activeSheet->getStyle("B" . $row . ":F" . $row)->applyFromArray(
            [
                "font" => [
                    "bold" => true,
                    "color" => [
                        "rgb" => "990000"
                    ]
                ],
                "borders" => [
                    "bottom" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THICK,
                        "color" => [
                            "rgb" => "000000"
                        ]
                    ]
                ]
            ]
        );
        $row += 2;

        /* Instruction details*/
        $InstTableStartRow = $row;
        $activeSheet->mergeCells("B" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Instructions");
        $activeSheet->getStyle("B" . $row)->applyFromArray(
            [
                "fill" => [
                    "type" => \PHPExcel_Style_Fill::FILL_SOLID,
                    "color"=> [
                        "rgb" => "DDDDDD"
                    ]
                ],
                "font" => [
                    "bold" => true
                ]
            ]
        );
        $row += 1;
        $activeSheet->mergeCells("B" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Thank you for agreeing to review this evaluation report. This tool is designed to score the quality of an evaluation report. Please read carefully the evaluation report you are requested to score before using this tool.");
        $row += 1;
        $activeSheet->mergeCells("B" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "You are asked to rate the quality of this report based on 7 main categories in this tool: Format, Executive summary, Background, Rationale, Methodology, Findings and Conclusion. Note that the report might not have section headings that coincide exactly with these categories. You are asked to make your assessment based on the total report.");
        $row += 1;
        $activeSheet->mergeCells("B" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Under each category, there are a number of criteria. Please provide a score for each criteria (1=Not satisfactory, 2= Somewhat satisfactory, 3=Satisfactory ). You are welcome to add your comment or recommendation (column next to each score)");
        $row += 1;
        $activeSheet->mergeCells("B" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "For each category, the total score will be automatically computed, based on the weights assigned to each category. ");
        $row += 1;
        $activeSheet->mergeCells("B" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "After completing scoring the Conclusion category, please come back to this (Cover) sheet and add the 7 scores for the final score.");
        $row += 1;
        $activeSheet->mergeCells("B" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "The scoring will take at most 30 minutes of your time.");
        $activeSheet->getStyle("B" . $row)->getFont()->setBold(true);
        $row += 1;
        $activeSheet->mergeCells("B" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Please make sure you have completed scoring for all 7 categories (redundant if you are using an online tool)");
        $activeSheet->getStyle("B" . $InstTableStartRow . ":F" . $row)->getBorders()->applyFromArray(
            [
                "allborders" => [
                    "style" => \PHPExcel_Style_Border::BORDER_THIN,
                    "color" => [
                        "rgb" => "DDDDDD"
                    ]
                ]
            ]
        );
        $activeSheet->getStyle("B" . $InstTableStartRow . ":F" . $row)->getBorders()
            ->applyFromArray(
                [
                    "top" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "000000"
                        ]
                    ],
                    "right" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "000000"
                        ]
                    ],
                    "bottom" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "000000"
                        ]
                    ],
                    "left" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "000000"
                        ]
                    ]
                ]
            );
        $row += 2;

        /*Terminology Table*/
        $TerminTableStartRow = $row;
        $activeSheet->mergeCells("B" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Terminology");
        $activeSheet->getStyle("B" . $row)->applyFromArray(
            [
                "fill" => [
                    "type" => \PHPExcel_Style_Fill::FILL_SOLID,
                    "color"=> [
                        "rgb" => "DDDDDD"
                    ]
                ],
                "font" => [
                    "bold" => true
                ]
            ]
        );
        $row += 1;
        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Internal Validity")
            ->setCellValue("C" . $row, "When the variables or indicators are true measures of what is intended to be studied, and any association between dependent and independent variables are free of bias ");
        $activeSheet->getStyle("B" . $row)->getFont()->setItalic(true);
        $row += 1;
        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "External Validity")
            ->setCellValue("C" . $row, "When study results can be generalized and used in different contexts or for different populations");
        $activeSheet->getStyle("B" . $row)->getFont()->setItalic(true);
        $row += 1;
        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Reliability")
            ->setCellValue("C" . $row, "When the tools/methods used for data collection consistently produce same results");
        $activeSheet->getStyle("B" . $row)->getFont()->setItalic(true);
        $row += 1;
        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Triangulation")
            ->setCellValue("C" . $row, "When multiple sources of information/data are used to establish vailidity of study results");
        $activeSheet->getStyle("B" . $row)->getFont()->setItalic(true);
        $row += 1;
        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Attribution")
            ->setCellValue("C" . $row, "Causal relationship between variables");
        $activeSheet->getStyle("B" . $row)->getFont()->setItalic(true);
        $row += 1;
        $activeSheet->mergeCells("C" . $row . ":F" . $row);
        $activeSheet->setCellValue("B" . $row, "Contributing/cofounding factors")
            ->setCellValue("C" . $row, "Factors that might affect the variables and relationships of the study");
        $activeSheet->getStyle("B" . $row)->getFont()->setItalic(true);
        $activeSheet->getStyle("B" . $TerminTableStartRow . ":F" . $row)->getBorders()->applyFromArray(
            [
                "allborders" => [
                    "style" => \PHPExcel_Style_Border::BORDER_THIN,
                    "color" => [
                        "rgb" => "DDDDDD"
                    ]
                ]
            ]
        );
        $activeSheet->getStyle("B" . $TerminTableStartRow . ":F" . $row)->getBorders()
            ->applyFromArray(
                [
                    "top" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "000000"
                        ]
                    ],
                    "right" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "000000"
                        ]
                    ],
                    "bottom" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "000000"
                        ]
                    ],
                    "left" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "000000"
                        ]
                    ]
                ]
            );
        return $this;
    }

    protected function StepSheets()
    {
        $questionCategories = $this->questionCategories;

        if(empty($questionCategories))
            return false;

        foreach($questionCategories as $index => $category)
            $this->StepSheet($category);

        return $this;

    }

    protected function StepSheet($category)
    {
        $Title = $category->title;
        $activeSheet = $this->addSheet($Title);
        $Collection = $this->getCollection();
        $ScoringConfig = $Collection->questiontype->scoring_config;
        $SCArray = json_decode($ScoringConfig, true);
        $ScoringConfigText = "";
        $MaxScore = 0;
        $Scores = [];
        foreach ($SCArray as $key => $value) {
            $ScoringConfigText .= $value["score"] . " = " . $value["label"] . ", ";
            $Scores[] = $value["score"];
        }
        $MaxScore = max($Scores);

        $row = $this->startRow;

        $activeSheet->getColumnDimension("A")->setWidth(10);
        $activeSheet->getColumnDimension("B")->setWidth(10);
        $activeSheet->getColumnDimension("C")->setWidth(10);
        $activeSheet->getColumnDimension("D")->setWidth(50);
        $activeSheet->getColumnDimension("E")->setWidth(10);
        $activeSheet->getColumnDimension("F")->setWidth(10);
        $activeSheet->getColumnDimension("G")->setWidth(15);
        /* Set STC Logo */
        $activeSheet->mergeCells("A" . $row . ":C" . ($row + 1));
        $objDrawing = new \PHPExcel_Worksheet_Drawing();
        $objDrawing->setPath(__DIR__ . '/logo/stc-logo.png')
            ->setResizeProportional(true)
            ->setWidth(200)
            ->setCoordinates('A' . $row)
            ->setWorksheet($activeSheet);

        /* Cover title */
        $activeSheet->mergeCells("D" . $row . ":G" . ($row + 1));
        $activeSheet->setCellValue("D" . $row, $Title);
        $activeSheet->getStyle("D" . $row)->applyFromArray(
            [
                "font" => [
                    "bold" => true,
                    "size" => 16
                ],
                "alignment" => [
                    "vertical" => "center"
                ]
            ]
        );
        $row += 3;

        /* Instructions */
        $activeSheet->mergeCells("B" . $row . ":G" . $row);
        $activeSheet->setCellValue("B" . $row, "The following are assessment criteria for the " .$Title. " of the evaluation report.");
        $row += 1;
        $activeSheet->mergeCells("B" . $row . ":G" . $row);
        $activeSheet->setCellValue("B" . $row, "Please assess each criteria with score: " . rtrim($ScoringConfigText, ", "));
        $row += 2;

        /* Questions Table */
        $rowHead = $row;
        $activeSheet->mergeCells("C" . $row . ":D" . $row);
        $activeSheet->mergeCells("F" . $row . ":G" . $row);
        $activeSheet->setCellValue("B" . $row, "Number")
            ->setCellValue("C" . $row, "Criteria")
            ->setCellValue("E" . $row, "Score")
            ->setCellValue("F" . $row, "Comment (Optional)");

        $activeSheet->getStyle("B" . $row . ":G" . $row)->applyFromArray(
            [
                "font" => [
                    "bold" => true
                ],
                "fill" => [
                    "type" => \PHPExcel_Style_Fill::FILL_SOLID,
                    "color"=> [
                        "rgb" => "DDDDDD"
                    ]
                ],
                "borders" => [
                    "bottom" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "000000"
                        ]
                    ]
                ]
            ]
        );

        $row += 1;
        $increment = 1;

        $Questions = $category->questions->lists('id');
        $Log = $Collection->log;
        $LogId = $Log->id;

        $Answers = \Question::with(
            [
                'evaluation_report_answers' => function($query) use($Questions, $LogId)
                {
                    $query->whereIn('question_id', $Questions)->where("evaluation_report_log_id", $LogId);
                }
            ]
        )->whereHas('evaluation_report_answers', function($query) use($Questions, $LogId)
        {
            $query->whereIn('question_id', $Questions)->where("evaluation_report_log_id", $LogId);
        })->where('question_category_id', $category->id)->get();

        $BodyStart = $row;
        $IdealScore= 0;

        foreach($category->questions as $question)
        {
            $qId = $question->id;
            $CurrentAnswers = $Answers->filter(function($question) use ($qId) {

                return $question->id === $qId;
            });

            $Score = 0;
            $Comment = null;
            $A = $CurrentAnswers->first();
            if(!empty($A)) {
                $B = $A->evaluation_report_answers;
                if(!empty($B)) {
                    $TheAnswer = $B->first();
                    $Score = $TheAnswer->pivot->score;
                    $Comment = $TheAnswer->pivot->comment;
                }
            }
            $IdealScore += $MaxScore;
            $activeSheet->mergeCells("C" . $row . ":D" . $row);
            $activeSheet->mergeCells("F" . $row . ":G" . $row);
            $activeSheet->setCellValue("B" . $row, $increment)
                ->setCellValue("C" . $row, $question->title)
                ->setCellValue("E" . $row, $Score)
                ->setCellValue("F" . $row, $Comment);
            $activeSheet->getStyle("B" . $row)->getAlignment()->setHorizontal("left");
            $activeSheet->getStyle("B" . $row . ":G" . $row)->getBorders()->applyFromArray(
                [
                    "bottom" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "DDDDDD"
                        ]
                    ]
                ]
            );
            $activeSheet->getStyle("C" . $row . ":D" . $row)->getBorders()->applyFromArray(
                [
                    "left" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "DDDDDD"
                        ]
                    ],
                    "right" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "DDDDDD"
                        ]
                    ]
                ]
            );
            $activeSheet->getStyle("F" . $row)->getBorders()->applyFromArray(
                [
                    "left" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "DDDDDD"
                        ]
                    ]
                ]
            );
            $row += 1;
            $increment += 1;
        }

        $activeSheet->mergeCells("B" . $row . ":D" . $row);
        $activeSheet->mergeCells("F" . $row . ":G" . $row);
        $Formula = "SUM(E".$BodyStart.":E".($row - 1).")";
        $activeSheet->setCellValue("B" . $row, $Title . " Score")
            ->setCellValue("E" . $row, "=" . $Formula)
            ->setCellValue("F" . $row, null);
        $activeSheet->getStyle("B" . $row)->getAlignment()->setHorizontal("right");
        $activeSheet->getStyle("B" . $row . ":G" . $row)->applyFromArray(
            [
                "font" => [
                    "bold" => true,
                    "color"=> [
                        "rgb" => "880000"
                    ]
                ],
                "borders" => [
                    "top" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "000000"
                        ]
                    ]
                ]
            ]
        );
        $row++;
        $activeSheet->mergeCells("B" . $row . ":D" . $row);
        $activeSheet->mergeCells("F" . $row . ":G" . $row);
        $Formula = '$E' . ($row-1) . "/" . $IdealScore;
        $activeSheet->setCellValue("B" . $row, $Title . " Percentage")
            ->setCellValue("E" . $row, "=" . $Formula)
            ->setCellValue("F" . $row, null);
        $activeSheet->getStyle("B" . $row)->getAlignment()->setHorizontal("right");
        $activeSheet->getStyle("E" . $row)->getNumberFormat()->setFormatCode('0.00 %');
        $activeSheet->getStyle("B" . $row . ":G" . $row)->applyFromArray(
            [
                "font" => [
                    "bold" => true,
                    "color"=> [
                        "rgb" => "880000"
                    ]
                ],
                "borders" => [
                    "top" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "DDDDDD"
                        ]
                    ]
                ]
            ]
        );
        $activeSheet->getStyle("B" . $rowHead . ":G" . $row)->applyFromArray(
            [
                "borders" => [
                    "top" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "000000"
                        ]
                    ],
                    "right" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "000000"
                        ]
                    ],
                    "bottom" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "000000"
                        ]
                    ],
                    "left" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        "color" => [
                            "rgb" => "000000"
                        ]
                    ]
                ]
            ]
        );

        return $this;
    }
}
