# Nova Baremetrics Metrics

Display [Baremetrics](https://baremetrics.com) metrics on your Nova dashboard.

![Screenshot](https://github.com/jackcruden/nova-baremetrics-metrics/raw/master/screenshot.png)

## Installation

Install via Composer:
    composer require jackcruden/nova-baremetrics-metrics

Register the card in `app/Providers/NovaServiceProvider` and pass the metric you'd like to show (MRR by default):
```php
public function tools()
{
    return [
        new \Jackcruden\NovaBaremetricsMetrics\BaremetricsMetric('mrr'),
        new \Jackcruden\NovaBaremetricsMetrics\BaremetricsMetric('active_subscriptions'),
    ];
}
```

Add your [Baremetrics API key](https://app.baremetrics.com/settings/api) in your `services.php` config file:
```php
'baremetrics' => [
    'api_key' => env('BAREMETRICS_API_KEY'),
],
```

Supports all current Baremetrics metrics:
- active_customers
- active_subscriptions
- add_on_mrr
- arpu
- arr
- cancellations
- coupons
- downgrades
- failed_charges
- fees
- ltv
- mrr
- net_revenue
- new_customers
- new_subscriptions
- other_revenue
- reactivations
- refunds
- revenue_churn
- trial_conversion
- upgrades
- user_churn
