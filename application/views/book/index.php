<?php if($sendok === true) {
    echo '<p>Message enregistré!</p>';
}
?>
<h3>Vos messages :</h3>

<?php
foreach ($book as $message) {
    echo "<h4>{$message['pseudo']}</h4>";
    echo "<p><strong>Le {$message['date']}</strong><br/>
    {$message['message']}</p><hr/>";
}

echo $pagination;