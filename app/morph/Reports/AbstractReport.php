<?php

namespace Morph\Reports;

use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class AbstractReport {

    protected $writer;
    protected $collection;
    public $startRow;

    public function __construct(Eloquent $collection)
    {
        $this->initWriter();
        $this->setCollection($collection);
    }

    public function getWriter()
    {
        return $this->writer;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function download($filename)
    {
        $objWriter = \PHPExcel_IOFactory::createWriter($this->getWriter(), "Excel5");

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".$filename);
        header("Expires: 0");
        header("Cache-Control: max-age=0, must-revalidate, post-check=0, pre-check=0");
        header("Pragma: public");

        return $objWriter->save('php://output');
    }

    abstract public function generate();

    protected function initWriter()
    {
        $this->writer = new \PHPExcel;
    }

    protected function setCollection(Eloquent $collection)
    {
        $this->collection = $collection;
    }

    protected function addSheet($title, $startRow = 1)
    {
        $this->startRow = $startRow;
        $number = $this->getWriter()->getSheetCount() - 1;

        $this->getWriter()->createSheet($number);
        return $this->getWriter()->setActiveSheetIndex($number)->setTitle($title);

    }

}
