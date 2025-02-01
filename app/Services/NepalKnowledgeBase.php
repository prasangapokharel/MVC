<?php

namespace Godsu\Mvc\Services;

class NepalKnowledgeBase
{
    private static $data = [
        'general_context' => "You are Nepal AI, an artificial intelligence trained with extensive knowledge about Nepal. You understand both English and Nepali languages. You have deep knowledge about Nepal's history, culture, geography, politics, and current affairs.",
        
        'language_context' => [
            'nepali_script' => 'देवनागरी',
            'common_phrases' => [
                'नमस्ते' => 'Hello',
                'धन्यवाद' => 'Thank you',
                'स्वागत छ' => 'Welcome'
            ]
        ],

        'cultural_knowledge' => [
            'festivals' => [
                'Dashain',
                'Tihar',
                'Holi',
                'Losar',
                'Chhath'
            ],
            'traditions' => [
                'Namaste greeting',
                'Tika ceremony',
                'Rice feeding ceremony'
            ]
        ],

        'geography' => [
            'regions' => [
                'Himalayan',
                'Hilly',
                'Terai'
            ],
            'major_cities' => [
                'Kathmandu',
                'Pokhara',
                'Bharatpur',
                'Lalitpur',
                'Birgunj'
            ]
        ],

        'current_affairs' => [
            'government_type' => 'Federal Democratic Republic',
            'capital' => 'Kathmandu',
            'president' => 'Ram Chandra Poudel',
            'prime_minister' => 'Pushpa Kamal Dahal'
        ]
    ];

    public static function getSystemPrompt(): string
    {
        return json_encode(self::$data);
    }

    public static function getContextForQuery(string $query): string
    {
        // Detect if query is in Nepali
        $containsNepali = preg_match('/[\x{0900}-\x{097F}]/u', $query);
        
        $context = self::$data['general_context'];
        
        if ($containsNepali) {
            $context .= " प्राथमिक भाषा: नेपाली";
        }

        return $context;
    }
}