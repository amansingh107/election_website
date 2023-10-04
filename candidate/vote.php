<?php 
    require_once("inc/header.php");
  ?>
    <div class="section" id="projects">
    <div class="container">
        <?php 
        $fetchingActiveElections = mysqli_query($db, "SELECT * FROM election WHERE status = 'Active'") or die(mysqli_error($db));
        $totalActiveElections = mysqli_num_rows($fetchingActiveElections);

        if ($totalActiveElections > 0) {
            $cont = 1;
            while ($data = mysqli_fetch_assoc($fetchingActiveElections)) {
                $election_id = $data['id'];
                $election_topic = $data['election_topic'];
        ?>
        <div class="col-md-12">
            <h4><?php echo $cont++; ?></h4>
            <h1 class="size-50">Present Election : <?php echo $election_topic;?></h1>
        </div>
        <!-- main container -->
        <div class="main-container portfolio-inner clearfix">
            <!-- portfolio div -->
            <!-- portfolio_filter -->
            <div class="portfolio">
                <!-- portfolio_filter -->
                <div class="no-padding portfolio_container clearfix" data-aos="fade-up">
                    <div class="portfolio-div">
                        <?php 
                        $fetchingCandidates = mysqli_query($db, "SELECT * FROM candidate_detail WHERE election_id = '". $election_id ."'") or die(mysqli_error($db));
                         $fetchingrowcandidate=mysqli_num_rows($fetchingCandidates);
                         if ($fetchingrowcandidate==0){
                           echo "No candidates available";
                         }
                        while ($candidateData = mysqli_fetch_assoc($fetchingCandidates)) {
                            $candidate_id = $candidateData['id'];
                            $candidate_photo = $candidateData['candidate_photo'];

                            // Fetching Candidate Votes 
                            $fetchingVotes = mysqli_query($db, "SELECT * FROM voting WHERE candidate_id = '". $candidate_id . "'") or die(mysqli_error($db));
                        ?>
                        <!-- single work -->
                        <div class="col-md-4 col-sm-6  fashion logo">
                            <a id="demo01" href="#animatedModal" class="portfolio_item">
                                <img style=" height: 300px;" src="<?php echo $candidate_photo; ?>" alt="image" class="img-responsive" />
                                <div class="portfolio_item_hover">
                                    <div class="portfolio-border clearfix">
                                        <div class="item_info">
                                            <span><?php echo $candidateData['candidate_name']; ?></span>
                                            <em>
                                                <?php
                                                // Check if the form is submitted
                                                if ($_SERVER["REQUEST_METHOD"] === "POST") {
                                                    // Assuming you have already established a database connection ($db)...
                                                    // Candidate ID is not applicable in this case, so you can set it to null
                                                    // Get the selected option from the submitted form
                                                    $voter_option = $_POST["voter_option"];
                                                    // Insert the vote into the voting table
                                                    $query = "INSERT INTO voting (election_id, candidate_id, voters_option) VALUES ('". $election_id ."','". $candidate_id ."','". $voter_option ."')";
                                                    if (mysqli_query($db, $query)) {
                                                        // Vote successfully saved, hide the voting form
                                                        echo "You have already voted Thank you for voting!";
                                                    } else {
                                                        // Failed to save the vote
                                                        echo "Failed to submit the vote.";
                                                    }
                                                } else {
                                                ?>
                                                <!-- Display the voting buttons if the form is not submitted -->
                                                <form method="post">
                                                    <button type="submit" name="voter_option" value="Yes">Yes</button>
                                                    <button type="submit" name="voter_option" value="No">No</button>
                                                    <button type="submit" name="voter_option" value="Neutral">Neutral</button>
                                                    <button type="submit" name="voter_option" value="None">None</button>
                                                </form>
                                                <?php
                                                }
                                                ?>
                                            </em>
                                        </div>
                                    </div>
                                </div>
                            </a>	
                        </div>
                        <!-- end single work -->
                        <?php
                        }
                        ?>
                    </div>
                    <!-- end portfolio div -->
                </div>
                <!-- end portfolio_container -->
            </div>
            <!-- end portfolio -->
        </div>
        <!-- end main container -->
        <?php
            }
        } else {
            echo "No any active election.";
        }
        ?>
    </div>
</div>
<!-- ./projects -->
