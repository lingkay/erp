<?php

namespace Gist\ChartBundle\Model;

use DateTime;
use DateInterval;

class DateAggregate
{
    const TYPE_DAILY            = 'daily';
    const TYPE_MONTHLY          = 'monthly';
    const TYPE_WEEKLY           = 'weekly';

    protected $precision;
    protected $chart;

    public function __construct(Chart $chart, $precision = 2)
    {
        $this->precision = $precision;
        $this->chart = $chart;
    }

    public function setPrecision($precision)
    {
        $this->precision = $precision;
        return $this;
    }

    public function processSeries(ChartSeries $series, $type, $data, DateTime $date_from, DateTime $date_to, $method_date, $method_value)
    {
        switch ($type)
        {
            case self::TYPE_DAILY:
                $agg_data = $this->initializeDaily($date_from, $date_to);
                return $this->processData($agg_data, $series, $data, 'Ymd', $method_date, $method_value);
            case self::TYPE_MONTHLY:
                $agg_data = $this->initializeMonthly($date_from, $date_to);
                return $this->processData($agg_data, $series, $data, 'Ym', $method_date, $method_value);
            case self::TYPE_WEEKLY:
                $agg_data = $this->initializeWeekly($date_from, $date_to);
                return $this->processData($agg_data, $series, $data, 'Y-W', $method_date, $method_value);
        }

        // TODO: throw exception
        return $this->chart;
    }

    public function initializeDaily($date_from, $date_to)
    {
        // make a checklist of dates to parse
        $agg_data = [];
        $interval = new DateInterval('P1D');
        for ($date = clone $date_from; $date <= $date_to; $date->add($interval))
        {
            // add to chart category
            $date_text = $date->format('m/d/Y');
            $this->chart->addCategory($date_text);

            // add to our initial data
            $index = $date->format('Ymd');
            $agg_data[$index] = 0.00;
        }

        return $agg_data;
    }

    public function initializeWeekly($date_from, $date_to)
    {
        $agg_data = [];

        // collect all weeks in date range
        $year_from = $date_from->format('Y');
        $year_to = $date_to->format('Y');
        $week_from = $date_from->format('W');
        $week_to = $date_to->format('W');

        // go through years
        for ($year = $year_from; $year <= $year_to; $year++)
        {
            // figure out max week
            if ($year == $year_to)
                $week_limit = $week_to;
            else
                $week_limit = 53;

            // figure out start week
            if ($year == $year_from)
                $week_start = $week_from;
            else
                $week_start = 1;

            // go through weeks
            for ($week = $week_start; $week <= $week_limit; $week++)
            {
                // add to chart category
                $date_text = "Week $week, $year";
                $this->chart->addCategory($date_text);

                // add to our aggregate data
                $agg_data[$year . '-' . $week] = 0.00;
            }
        }

        error_log(print_r($agg_data, true));

        return $agg_data;
    }

    public function initializeMonthly($date_from, $date_to)
    {
        $agg_data = [];

        // collect all months in date range
        $year_from = $date_from->format('Y');
        $year_to = $date_to->format('Y');
        $month_from = $date_from->format('m');
        $month_to = $date_to->format('m');

        // go through years
        for ($year = $year_from; $year <= $year_to; $year++)
        {
            // figure out max month
            if ($year == $year_to)
                $month_limit = $month_to;
            else
                $month_limit = 12;

            // figure out start month
            if ($year == $year_from)
                $month_start = $month_from;
            else
                $month_start = 1;

            // go through months
            for ($month = $month_start; $month <= $month_limit; $month++)
            {
                // add chart category
                $temp_date = DateTime::createFromFormat('!m', $month);
                $month_text = $temp_date->format('F');
                $date_text = "$month_text $year";
                $this->chart->addCategory($date_text);

                // add to our aggregate data
                $agg_data[$year . $temp_date->format('m')] = 0.00;
            }
        }

        return $agg_data;
    }

    protected function processData($agg_data, ChartSeries $series, $data, $date_format, $method_date, $method_value)
    {
        // iterate through the data and aggregate
        foreach ($data as $entry)
        {
            // update our aggregate data
            $index = $entry->$method_date()->format($date_format);
            $agg_data[$index] = bcadd($agg_data[$index], $entry->$method_value(), $this->precision);
        }

        // add data into series
        foreach ($agg_data as $entry)
            $series->addValue($entry);
        $this->chart->addSeries($series);

        return $this->chart;
    }

    public function getChart()
    {
        return $this->chart;
    }
}
