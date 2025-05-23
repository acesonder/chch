<?php
require_once 'config.php';
require_once 'utils.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $responses = $_POST['responses'];

    foreach ($responses as $question_id => $response) {
        $stmt = $pdo->prepare("INSERT INTO responses (user_id, question_id, response, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute([$user_id, $question_id, $response]);
    }

    // Redirect to next step or assessment results
    header("Location: next_step.php");
    exit();
}

// Fetch questions from the database
$stmt = $pdo->prepare("SELECT * FROM questions ORDER BY section, id");
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intake Form</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Intake Form</h1>
        <form action="intake_form.php" method="POST">
            <?php foreach ($questions as $question): ?>
                <div class="question">
                    <label for="question_<?php echo $question['id']; ?>"><?php echo $question['question_text']; ?></label>
                    <?php if ($question['question_type'] === 'multiple_choice'): ?>
                        <select name="responses[<?php echo $question['id']; ?>]" id="question_<?php echo $question['id']; ?>">
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    <?php elseif ($question['question_type'] === 'yes_no'): ?>
                        <input type="radio" name="responses[<?php echo $question['id']; ?>]" id="question_<?php echo $question['id']; ?>_yes" value="yes">
                        <label for="question_<?php echo $question['id']; ?>_yes">Yes</label>
                        <input type="radio" name="responses[<?php echo $question['id']; ?>]" id="question_<?php echo $question['id']; ?>_no" value="no">
                        <label for="question_<?php echo $question['id']; ?>_no">No</label>
                    <?php elseif ($question['question_type'] === 'likert_scale'): ?>
                        <input type="range" name="responses[<?php echo $question['id']; ?>]" id="question_<?php echo $question['id']; ?>" min="1" max="5">
                    <?php elseif ($question['question_type'] === 'open_ended'): ?>
                        <textarea name="responses[<?php echo $question['id']; ?>]" id="question_<?php echo $question['id']; ?>"></textarea>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <button type="submit">Next</button>
        </form>
    </div>
</body>
</html>
