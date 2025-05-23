<?php
require_once 'config.php';
require_once 'utils.php';

function weight_responses($responses) {
    $weights = [
        'mental_health' => 1.5,
        'substance_use' => 2.0,
        'trauma_history' => 1.8,
        'housing_situation' => 1.2
    ];

    $weighted_responses = [];
    foreach ($responses as $response) {
        $section = $response['section'];
        $weight = $weights[$section] ?? 1.0;
        $weighted_responses[] = [
            'question_id' => $response['question_id'],
            'response' => $response['response'],
            'weighted_score' => $response['response'] * $weight
        ];
    }

    return $weighted_responses;
}

function identify_patterns($responses) {
    $patterns = [
        'mental_health' => [],
        'substance_use' => [],
        'trauma_history' => [],
        'housing_situation' => []
    ];

    foreach ($responses as $response) {
        $section = $response['section'];
        $patterns[$section][] = $response['response'];
    }

    return $patterns;
}

function generate_assessment($responses) {
    $weighted_responses = weight_responses($responses);
    $patterns = identify_patterns($responses);

    $summary = "Based on your responses, we have identified the following needs and recommended support services.";
    $recommendations = "We recommend the following support services based on your responses.";
    $priority_areas = "The following areas have been identified as priority areas for intervention.";
    $severity_ratings = "The severity ratings for each category are as follows.";

    return [
        'summary' => $summary,
        'recommendations' => $recommendations,
        'priority_areas' => $priority_areas,
        'severity_ratings' => $severity_ratings
    ];
}

function store_assessment($user_id, $assessment) {
    global $pdo;

    $stmt = $pdo->prepare("INSERT INTO assessments (user_id, summary, recommendations, priority_areas, severity_ratings, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute([$user_id, $assessment['summary'], $assessment['recommendations'], $assessment['priority_areas'], $assessment['severity_ratings']]);
}
?>
