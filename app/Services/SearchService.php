<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class SearchService
{
    /**
     * Apply search filters to an Offer query.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function apply(Builder $query, array $filters): Builder
    {
        // Filter by metal
        if (!empty($filters['metal'])) {
            $query->where('metal', $filters['metal']);
        }

        // Filter by weight with comparison (converting units)
        if (!empty($filters['weight']) && !empty($filters['gtlt']) && !empty($filters['units'])) {
            $operator = $filters['gtlt'] === 'gte' ? '>=' : '<=';
            $searchWeight = (float) $filters['weight'];
            $searchUnits = $filters['units'];

            // Convert search weight to both units for comparison
            // 1 oz = 31.1035 grams
            $ozToGram = 31.1035;

            if ($searchUnits === 'oz') {
                $weightInOz = $searchWeight;
                $weightInGram = $searchWeight * $ozToGram;
            } else {
                $weightInGram = $searchWeight;
                $weightInOz = $searchWeight / $ozToGram;
            }

            // Match rows where the weight in the row's native unit satisfies the condition
            $query->where(function ($q) use ($operator, $weightInOz, $weightInGram) {
                $q->where(function ($sub) use ($operator, $weightInOz) {
                    $sub->where('weight_unit', 'oz')
                        ->where('weight', $operator, $weightInOz);
                })->orWhere(function ($sub) use ($operator, $weightInGram) {
                    $sub->where('weight_unit', 'gram')
                        ->where('weight', $operator, $weightInGram);
                });
            });
        }

        // Filter by keywords in description
        if (!empty($filters['keywords'])) {
            $keywords = preg_split('/\s+/', trim($filters['keywords']));
            $query->where(function (Builder $q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('description', 'like', '%' . $keyword . '%');
                }
            });
        }

        // Apply ordering
        if (!empty($filters['orderby'])) {
            $direction = $filters['direction'] ?? 'asc';
            $query->orderBy($filters['orderby'], $direction);
        }

        return $query;
    }

    /**
     * Get the current filter values for passing to the view.
     *
     * @param array $filters
     * @return array
     */
    public function getFilterValues(array $filters): array
    {
        return [
            'metal' => $filters['metal'] ?? '',
            'weight' => $filters['weight'] ?? '',
            'gtlt' => $filters['gtlt'] ?? '',
            'units' => $filters['units'] ?? '',
            'orderby' => $filters['orderby'] ?? '',
            'direction' => $filters['direction'] ?? '',
            'keywords' => $filters['keywords'] ?? '',
        ];
    }
}
