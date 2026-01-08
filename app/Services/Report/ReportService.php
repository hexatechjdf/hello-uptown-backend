<?php

namespace App\Services\Report;

use App\Models\Redemption;
use App\Models\Category;
use Carbon\Carbon;

class ReportService
{
    private $colorPalette = [
        '#1a1a1a', '#4ecdc4', '#f5a623', '#54b7c8', '#9b59b6',
        '#e74c3c', '#3498db', '#2ecc71', '#f39c12', '#8e44ad',
        '#d35400', '#c0392b', '#16a085', '#27ae60', '#2980b9',
    ];

    public function getRedemptionsTrend($businessId, $period = 'weekly', $type = 'all', $startDate = null, $endDate = null)
    {
        $query = Redemption::whereHas('business', function ($q) use ($businessId) {
            $q->where('id', $businessId);
        });

        // Filter by type
        if ($type !== 'all') {
            $query->where('type', $type);
        }

        // Apply date filters
        [$startDate, $endDate] = $this->getDateRange($period, $startDate, $endDate);
        $query->whereBetween('redeemed_at', [$startDate, $endDate]);

        // Group by period
        return match ($period) {
            'daily' => $this->getDailyTrend($query),
            'weekly' => $this->getWeeklyTrend($query),
            'monthly' => $this->getMonthlyTrend($query),
            'yearly' => $this->getYearlyTrend($query),
            default => $this->getWeeklyTrend($query),
        };
    }

    public function getCategoryDistribution($businessId, $period = 'weekly', $type = 'all', $startDate = null, $endDate = null)
    {
        $query = Redemption::whereHas('business', function ($q) use ($businessId) {
            $q->where('id', $businessId);
        });

        // Filter by type
        if ($type !== 'all') {
            $query->where('type', $type);
        }

        // Apply date filters
        [$startDate, $endDate] = $this->getDateRange($period, $startDate, $endDate);
        $query->whereBetween('redeemed_at', [$startDate, $endDate]);

        // Load categories efficiently
        $categories = Category::all()->keyBy('id');

        // Get all redemptions with their parent items and categories
        $redemptions = $query->with(['coupon.category', 'deal.category'])->get();

        $categoryCounts = [];
        $categoryColors = $this->getCategoryColors($categories);

        foreach ($redemptions as $redemption) {
            $category = $this->getRedemptionCategory($redemption, $categories);
            $categoryName = $category ? $category->name : 'Uncategorized';

            if (!isset($categoryCounts[$categoryName])) {
                $categoryCounts[$categoryName] = [
                    'name' => $categoryName,
                    'value' => 0,
                    'color' => $category ? ($categoryColors[$category->id] ?? '#9b59b6') : '#9b59b6'
                ];
            }
            $categoryCounts[$categoryName]['value']++;
        }

        // Convert to array and sort by value descending
        $categoryData = array_values($categoryCounts);
        usort($categoryData, function ($a, $b) {
            return $b['value'] <=> $a['value'];
        });

        return $categoryData;
    }

    private function getRedemptionCategory($redemption, $categories)
    {
        if ($redemption->type === 'coupon' && $redemption->coupon && $redemption->coupon->category_id) {
            return $categories->get($redemption->coupon->category_id);
        } elseif ($redemption->type === 'deal' && $redemption->deal && $redemption->deal->category_id) {
            return $categories->get($redemption->deal->category_id);
        }

        return null;
    }

    private function getCategoryColors($categories)
    {
        $colors = [];
        $index = 0;

        foreach ($categories as $category) {
            $colors[$category->id] = $this->colorPalette[$index % count($this->colorPalette)];
            $index++;
        }

        return $colors;
    }

    private function getDateRange($period, $startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            return [Carbon::parse($startDate), Carbon::parse($endDate)];
        }

        $now = Carbon::now();

        return match ($period) {
            'daily' => [
                $now->copy()->startOfDay(),
                $now->copy()->endOfDay()
            ],
            'weekly' => [
                $now->copy()->startOfWeek(),
                $now->copy()->endOfWeek()
            ],
            'monthly' => [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth()
            ],
            'yearly' => [
                $now->copy()->startOfYear(),
                $now->copy()->endOfYear()
            ],
            'today' => [
                $now->copy()->startOfDay(),
                $now->copy()->endOfDay()
            ],
            default => [
                $now->copy()->subDays(7),
                $now->copy()
            ],
        };
    }

    private function getDailyTrend($query)
    {
        $data = $query->selectRaw('HOUR(redeemed_at) as hour, COUNT(*) as count')
            ->whereDate('redeemed_at', Carbon::today())
            ->groupBy('hour')
            ->pluck('count', 'hour');

        $formattedData = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $formattedData[] = [
                'label' => sprintf('%02d:00', $hour),
                'redemptions' => $data[$hour] ?? 0
            ];
        }

        return $formattedData;
    }

    private function getWeeklyTrend($query)
    {
        $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        $data = $query->selectRaw('DAYOFWEEK(redeemed_at) as day_of_week, COUNT(*) as count')
            ->groupBy('day_of_week')
            ->pluck('count', 'day_of_week');

        $formattedData = [];
        foreach ($daysOfWeek as $index => $day) {
            // MySQL DAYOFWEEK: 1=Sunday, 2=Monday, etc.
            $mysqlDay = ($index + 2) > 7 ? 1 : ($index + 2);

            $formattedData[] = [
                'label' => $day,
                'redemptions' => $data[$mysqlDay] ?? 0
            ];
        }

        return $formattedData;
    }

    private function getMonthlyTrend($query)
    {
        $data = $query->selectRaw('DAY(redeemed_at) as day, COUNT(*) as count')
            ->whereMonth('redeemed_at', Carbon::now()->month)
            ->groupBy('day')
            ->pluck('count', 'day');

        $daysInMonth = Carbon::now()->daysInMonth;
        $formattedData = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $formattedData[] = [
                'label' => (string)$day,
                'redemptions' => $data[$day] ?? 0
            ];
        }

        return $formattedData;
    }

    private function getYearlyTrend($query)
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $data = $query->selectRaw('MONTH(redeemed_at) as month, COUNT(*) as count')
            ->whereYear('redeemed_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('count', 'month');

        $formattedData = [];
        foreach ($months as $index => $month) {
            $formattedData[] = [
                'label' => $month,
                'redemptions' => $data[$index + 1] ?? 0
            ];
        }

        return $formattedData;
    }
}
