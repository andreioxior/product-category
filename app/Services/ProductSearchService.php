<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductSearchService
{
    public function enhancedAutocomplete(string $query, array $options = []): array
    {
        if (strlen($query) < 2) {
            return [
                'results' => [],
                'total' => 0,
                'facets' => []
            ];
        }

        $searchType = $options['search_type'] ?? 'all';
        $limit = $options['limit'] ?? 10;

        $searchParams = $this->buildSearchParams($searchType, $limit);

        $results = Product::search($query)
            ->withSearchParameters($searchParams)
            ->where('is_active', true)
            ->raw();

        return [
            'results' => $this->formatResults($results['hits'] ?? [], $query),
            'total' => $results['found'] ?? 0,
            'facets' => $this->extractFacets($results['facet_counts'] ?? [])
        ];
    }

    private function buildSearchParams(string $searchType, int $limit): array
    {
        $baseParams = [
            'highlight_fields' => 'name,manufacturer,model,year',
            'highlight_start_tag' => '<mark>',
            'highlight_end_tag' => '</mark>',
            'per_page' => $limit,
            'facet_by' => 'manufacturer,model,year',
            'max_facet_values' => 5
        ];

        return match($searchType) {
            'name' => array_merge($baseParams, [
                'query_by' => 'name,description,category_name',
                'query_by_weights' => '10,1,1'
            ]),
            'manufacturer' => array_merge($baseParams, [
                'query_by' => 'manufacturer',
                'query_by_weights' => '10'
            ]),
            'model' => array_merge($baseParams, [
                'query_by' => 'model',
                'query_by_weights' => '10'
            ]),
            'year' => array_merge($baseParams, [
                'query_by' => 'year',
                'query_by_weights' => '10'
            ]),
            default => array_merge($baseParams, [
                'query_by' => 'name,manufacturer,model,year,description,category_name',
                'query_by_weights' => '5,4,4,3,1,1'
            ])
        };
    }

    private function formatResults(array $hits, string $query): array
    {
        return array_map(function ($hit) use ($query) {
            $document = $hit['document'];
            $matchedFields = $this->detectMatchedFields($hit);
            $primaryField = $this->getPrimaryMatchedField($matchedFields);

            return [
                'id' => $document['id'],
                'name' => $document['name'],
                'manufacturer' => $document['manufacturer'] ?? null,
                'model' => $document['model'] ?? null,
                'year' => $document['year'] ?? null,
                'highlighted_name' => $this->getHighlightedField($hit, 'name') ?? $document['name'],
                'highlighted_manufacturer' => $this->getHighlightedField($hit, 'manufacturer'),
                'highlighted_model' => $this->getHighlightedField($hit, 'model'),
                'highlighted_year' => $this->getHighlightedField($hit, 'year'),
                'matched_fields' => $matchedFields,
                'primary_match_field' => $primaryField,
                'field_indicators' => $this->generateFieldIndicators($matchedFields),
                'relevance_score' => $hit['text_match'] ?? 0
            ];
        }, $hits);
    }

    private function detectMatchedFields(array $hit): array
    {
        $matchedFields = [];
        $highlights = $hit['highlights'] ?? [];

        foreach ($highlights as $highlight) {
            $field = $highlight['field'];
            if (in_array($field, ['name', 'manufacturer', 'model', 'year'])) {
                $matchedFields[] = $field;
            }
        }

        return array_unique($matchedFields);
    }

    private function getPrimaryMatchedField(array $matchedFields): string
    {
        if (empty($matchedFields)) {
            return 'general';
        }

        $fieldPriority = ['name' => 1, 'manufacturer' => 2, 'model' => 3, 'year' => 4];
        
        usort($matchedFields, function ($a, $b) use ($fieldPriority) {
            $priorityA = $fieldPriority[$a] ?? 999;
            $priorityB = $fieldPriority[$b] ?? 999;
            return $priorityA <=> $priorityB;
        });

        return $matchedFields[0];
    }

    private function getHighlightedField(array $hit, string $fieldName): ?string
    {
        $highlights = $hit['highlights'] ?? [];
        
        foreach ($highlights as $highlight) {
            if ($highlight['field'] === $fieldName) {
                return $highlight['snippet'] ?? $highlight['value'] ?? null;
            }
        }

        return null;
    }

    private function generateFieldIndicators(array $matchedFields): array
    {
        $indicators = [];
        foreach ($matchedFields as $field) {
            $indicators[$field] = [
                'label' => ucwords(str_replace('_', ' ', $field)),
                'icon' => $this->getFieldIcon($field),
                'color' => $this->getFieldColor($field),
                'bg_color' => $this->getFieldBgColor($field)
            ];
        }
        return $indicators;
    }

    private function getFieldIcon(string $field): string
    {
        return match($field) {
            'name' => 'tag',
            'manufacturer' => 'building',
            'model' => 'gear',
            'year' => 'calendar',
            default => 'search'
        };
    }

    private function getFieldColor(string $field): string
    {
        return match($field) {
            'name' => 'blue',
            'manufacturer' => 'green', 
            'model' => 'purple',
            'year' => 'orange',
            default => 'gray'
        };
    }

    private function getFieldBgColor(string $field): string
    {
        return match($field) {
            'name' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
            'manufacturer' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
            'model' => 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200',
            'year' => 'bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200',
            default => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200'
        };
    }

    private function extractFacets(array $facetCounts): array
    {
        $facets = [];
        foreach ($facetCounts as $facet) {
            $fieldName = $facet['field_name'];
            $values = array_map(function ($count) {
                return [
                    'value' => $count['value'],
                    'count' => $count['count']
                ];
            }, $facet['counts'] ?? []);
            
            $facets[$fieldName] = array_slice($values, 0, 5); // Limit to top 5
        }
        return $facets;
    }

    public function getFieldIconSvg(string $icon): string
    {
        return match($icon) {
            'tag' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>',
            'building' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',
            'gear' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
            'calendar' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
            'search' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>',
            default => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>'
        };
    }
}