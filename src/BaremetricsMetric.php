<?php

namespace Jackcruden\NovaBaremetricsMetrics;

use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Trend;

class BaremetricsMetric extends Trend
{
    protected $metric;

    public function __construct($metric = 'mrr')
    {
        $this->metric = $metric;
        $this->name = Baremetrics::name($this->metric);
        $this->withMeta(['metric' => $metric]);
    }

    /**
     * Calculate the value of the metric.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function calculate(Request $request)
    {
        $metric = explode('-', $request->input('range'))[0];
        $months = explode('-', $request->input('range'))[1];
        $data = (new Baremetrics($metric))->metric((int) $months);

        return $this->result(array_values(array_slice($data['trend'], -1))[0])
            ->trend($data['trend'])
            ->prefix($data['prefix']);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            $this->metric.'-1'  => '1 Month',
            $this->metric.'-2'  => '2 Months',
            $this->metric.'-3'  => '3 Months',
            $this->metric.'-6'  => '6 Months',
            $this->metric.'-12' => '1 Year',
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        return now()->addMinutes(10);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'baremetrics-metrics';
    }
}
