<?php

namespace Jackcruden\NovaBaremetricsMetrics;

use Carbon\Carbon;
use GuzzleHttp\Client;

class Baremetrics
{
    protected $client;
    protected $api_key;
    protected $metric;

    public function __construct($metric = 'active_customers')
    {
        $this->client = new Client([
            'base_uri' => 'https://api.baremetrics.com/v1/metrics/'.$metric,
            'headers'  => [
                'Authorization' => 'Bearer '.config('services.baremetrics.api_key'),
            ],
        ]);

        $this->metric = $metric;
    }

    public function metric(int $months): array
    {
        $response = $this->client->get($this->metric, [
            'query' => [
                'start_date' => Carbon::create()->subMonths($months)->setTimezone('UTC')->format('Y-m-d'),
                'end_date'   => Carbon::create()->setTimezone('UTC')->format('Y-m-d'),
            ],
        ])->getBody()->getContents();
        $entries = json_decode($response)->metrics;

        $data = [
            'trend'  => [],
            'prefix' => null,
        ];

        // Format the values
        $trend = [];
        foreach ($entries as $entry) {
            $value = $entry->value;

            if ('money' == $this->type()) {
                $value = $value / 100;
            } else {
                $value = $value;
            }

            $trend[date('Y-m-d', $entry->date)] = $value;
        }
        $data['trend'] = $trend;

        // Final formatting
        if ('money' == $this->type()) {
            $data['prefix'] = '$';
        }

        return $data;
    }

    public function type()
    {
        $metricTypes = [
            'active_customers'     => 'number',
            'active_subscriptions' => 'number',
            'add_on_mrr'           => 'money',
            'arpu'                 => 'money',
            'arr'                  => 'money',
            'cancellations'        => 'number',
            'coupons'              => 'money',
            'downgrades'           => 'money',
            'failed_charges'       => 'number',
            'fees'                 => 'money',
            'ltv'                  => 'money',
            'mrr'                  => 'money',
            'net_revenue'          => 'money',
            'new_customers'        => 'number',
            'new_subscriptions'    => 'number',
            'other_revenue'        => 'money',
            'reactivations'        => 'number',
            'refunds'              => 'money',
            'revenue_churn'        => 'money',
            'trial_conversion'     => 'number',
            'upgrades'             => 'number',
            'user_churn'           => 'number',
        ];

        return $metricTypes[$this->metric];
    }

    public static function name($metric)
    {
        $metricNames = [
            'active_customers'     => 'Active Customers',
            'active_subscriptions' => 'Active Subscriptions',
            'add_on_mrr'           => 'Add on MRR',
            'arpu'                 => 'ARPU',
            'arr'                  => 'ARR',
            'cancellations'        => 'Cancellations',
            'coupons'              => 'Coupons',
            'downgrades'           => 'Downgrades',
            'failed_charges'       => 'Failed Charges',
            'fees'                 => 'Fees',
            'ltv'                  => 'LTV',
            'mrr'                  => 'MRR',
            'net_revenue'          => 'Net Revenue',
            'new_customers'        => 'New Customers',
            'new_subscriptions'    => 'New Subscriptions',
            'other_revenue'        => 'Other Revenue',
            'reactivations'        => 'Reactivations',
            'refunds'              => 'Refunds',
            'revenue_churn'        => 'Revenue Churn',
            'trial_conversion'     => 'Trial Conversion',
            'upgrades'             => 'Upgrades',
            'user_churn'           => 'User Churn',
        ];

        return $metricNames[$metric];
    }
}
