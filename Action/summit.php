<?php
session_start();
require '../Classes/test.php'; 
$db = new Database(); 

$form = $_SESSION['form'];
if (isset($_POST['submit'])) {
    $correct = 0;
    $userId = $_SESSION['user_id']; 

    foreach ($form as $field) {
        $reponse = isset($_POST['form'][$field['id']]) ? $_POST['form'][$field['id']] : '';

        $correctAnswer = $db->obtenirBonneReponse($field['id']); 

        echo '<div>';
        echo '<p>';
        echo $field["label"];
        echo ' Réponse : ';
        if (isset($correctAnswer) && $reponse == $correctAnswer) {
            echo '<span style="color: green;">' . $reponse . '</span>';
            $correct++;
        } else {
            echo '<span style="color: red;">' . $reponse . '</span>';
        }

        echo '</p>';
        echo '</div>';
    }

    $quizId = isset($_POST['quiz_id']) ? $_POST['quiz_id'] : null;
    
    if ($quizId) {
        $score = $correct / count($form) * 100; 
        $insertedScore = $db->insererResultatQuiz($userId, $quizId, $score);

        if (!$insertedScore) {
            echo 'Error inserting final quiz result for user_id: ' . $userId . ', quiz_id: ' . $quizId . '<br>';
            echo 'user_id exists in utilisateurs: ' . var_export($db->userExists($userId), true) . '<br>';
        }
    } else {
        echo 'Error: quiz_id is missing in POST data.';
    }

    echo '<p>bonne réponse: ' . $correct . '</p>';
    
    echo '<form method="GET" action="questionnaires.php">';
    echo '<button type="submit" name="submit">Retour aux questionnaires</button>';
    echo '</form>';
}
?>
