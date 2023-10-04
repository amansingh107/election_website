<?php 
    $election_id = $_GET['viewResult'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Election Results</title>
    <style>
        table {
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>
</head>
<body>
   
   

<?php

// Replace these variables with your actual database credentials


// Step 2: Retrieve the data from the database for a specific position (election_id)

$fetchingActiveElections = mysqli_query($db, "SELECT * FROM election WHERE status = 'Active'") or die(mysqli_error($db));
$totalActiveElections = mysqli_num_rows($fetchingActiveElections);

if($totalActiveElections > 0) 
{
    while($data = mysqli_fetch_assoc($fetchingActiveElections))
    {
        $election_id = $data['id'];
        $election_topic = $data['election_topic'];
        
        ?>|
         <h2>Election Results for Position  <?php echo $election_topic; ?></h2>
         <?php    

$sql = "SELECT candidate_id,
               SUM(voters_option = 'Yes') AS yes_votes,
               SUM(voters_option = 'No') AS no_votes,
               SUM(voters_option = 'Neutral') AS neutral_votes,
               SUM(voters_option = 'None') AS nota_votes
        FROM voting
        WHERE election_id = $election_id
        GROUP BY candidate_id";

$result = $db->query($sql);

// Step 3: Process the results and determine the election outcome


// ... (previous code remains unchanged)

// Step 3 and Step 4: Process the results and determine the election outcome
$results_table = "<table>
                    <tr>
                        <th>Photo</th>
                        <th>Candidate Name</th>
                        <th>Yes</th>
                        <th>No</th>
                        <th>Neutral</th>
                        <th>NOTA</th>
                        <th>Result</th>
                    </tr>";

if ($result->num_rows > 0) {
    $candidates = array();
    while ($row = $result->fetch_assoc()) {
        $candidate_id = $row["candidate_id"];
        
        // Retrieve candidate details from candidate_details table
        $candidate_sql = "SELECT candidate_name, candidate_photo FROM candidate_detail WHERE id = $candidate_id";
        $candidate_result = $db->query($candidate_sql);
        $candidate_row = $candidate_result->fetch_assoc();

        $candidate_name = $candidate_row["candidate_name"];
        $candidate_photo = $candidate_row["candidate_photo"];
        $yes_votes = $row["yes_votes"];
        $no_votes = $row["no_votes"];
        $neutral_votes = $row["neutral_votes"];
        $nota_votes = $row["nota_votes"];
        
        // Add the candidate details to an array for further processing
        $candidates[] = array(
            "candidate_id" => $candidate_id,
            "candidate_name" => $candidate_name,
            "candidate_photo" => $candidate_photo,
            "yes_votes" => $yes_votes,
            "no_votes" => $no_votes,
            "neutral_votes" => $neutral_votes,
            "nota_votes" => $nota_votes
        );
    }

    // Sort the candidates based on the number of 'Yes' votes in descending order
    usort($candidates, function ($a, $b) {
        if ($a["yes_votes"] === $b["yes_votes"]) {
            // If two candidates have the same number of 'Yes' votes, sort based on 'No' votes in ascending order
            return $a["no_votes"] - $b["no_votes"];
        }
        return $b["yes_votes"] - $a["yes_votes"];
    });

    // Determine the winner(s) and election outcome
    $total_candidates = count($candidates);
    foreach ($candidates as $index => $candidate) {
        $candidate_name = $candidate["candidate_name"];
        $candidate_photo = $candidate["candidate_photo"];
        $yes_votes = $candidate["yes_votes"];
        $no_votes = $candidate["no_votes"];
        $neutral_votes = $candidate["neutral_votes"];
        $nota_votes = $candidate["nota_votes"];

        // If only one candidate, and the candidate has less 'Yes' votes than 'No' votes,
        // add the 'Neutral' votes to 'Yes' votes and check if it exceeds the count of 'No' votes.
        // Decide the result accordingly.
        if ($total_candidates === 1 && $yes_votes < $no_votes) {
            $yes_votes += $neutral_votes;
            if ($yes_votes > $no_votes) {
                $result = "Elected";
            } else {
                $result = "Not Elected";
            }
        } else {
            // If multiple candidates, the candidate with the most 'Yes' votes is the winner
            $result = ($index === 0) ? "Elected" : "Not Elected";
        }

        // Display the results in the table format
        $results_table .= "<tr>
                            <td><img src='$candidate_photo' alt='$candidate_name' width='100' height='100'></td>
                            <td>$candidate_name</td>
                            <td>$yes_votes</td>
                            <td>$no_votes</td>
                            <td>$neutral_votes</td>
                            <td>$nota_votes</td>
                            <td>$result</td>
                          </tr>";
    }
} else {
    $results_table .= "<tr><td colspan='7'>No candidates found for this election.</td></tr>";
}

$results_table .= "</table>";

// ... (rest of the code remains unchanged)
echo $results_table;
}
?>


<?php
}else {
    echo "No any active election.";
}


?>
</body>
</html>
