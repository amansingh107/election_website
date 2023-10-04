
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
            <h1 class="size-50">Vote<br />For <?php echo $election_topic; ?></h1>
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

                        while ($candidateData = mysqli_fetch_assoc($fetchingCandidates)) {
                            $candidate_id = $candidateData['id'];
                            $candidate_photo = $candidateData['candidate_photo'];

                            // Fetching Candidate Votes 
                            $fetchingVotes = mysqli_query($db, "SELECT * FROM voting WHERE candidate_id = '". $candidate_id . "'") or die(mysqli_error($db));
                        ?>
                        <!-- single work -->
                        <div class="col-md-4 col-sm-6  fashion logo">
                            <a id="demo01" href="#animatedModal" class="portfolio_item">
                                <img style="height:250px"src="<?php echo $candidate_photo; ?>" alt="image" class="img-responsive" />
                                <div class="portfolio_item_hover">
                                    <div class="portfolio-border clearfix">
                                        <div class="item_info">
                                            <span><?php echo $candidateData['candidate_name']; ?></span>
                                            <em>
                                            <?php
                                            $checkIfVoteCasted = mysqli_query($db, "SELECT * FROM voting WHERE voters_id = '". $_SESSION['user_id'] ."' AND election_id = '". $election_id ."'") or die(mysqli_error($db));    
                                            $isVoteCasted = mysqli_num_rows($checkIfVoteCasted);

                                            if($isVoteCasted > 0)
                                            {
                                                $voteCastedData = mysqli_fetch_assoc($checkIfVoteCasted);
                                                $voteCastedToCandidate = $voteCastedData['candidate_id'];

                                                if($voteCastedToCandidate == $candidate_id)
                                                {
                                    ?>
 

                                                    <img src="../assets/images/vote.png" width="100px;">
                                    <?php
                                                }
                                            }else {
                                                
                                              
                                    ?> 
                                                <button style="margin-left:10px; background:black; color:white;"onclick="YesVote(<?php echo $election_id; ?>, <?php echo $candidate_id; ?>, <?php echo $_SESSION['user_id']; ?>)"> Yes </button>
                                                <button style="margin-left:10px; background:black; color:white;" onclick="NoVote(<?php echo $election_id; ?>, <?php echo $candidate_id; ?>, <?php echo $_SESSION['user_id']; ?>)"> No </button>
                                                <button style="margin-left:10px; background:black; color:white;" onclick="NeutralVote(<?php echo $election_id; ?>, <?php echo $candidate_id; ?>, <?php echo $_SESSION['user_id']; ?>)"> Neutral </button>
                                                <button style="margin-left:10px; background:black; color:white;" onclick="NoneVote(<?php echo $election_id; ?>, <?php echo $candidate_id; ?>, <?php echo $_SESSION['user_id']; ?>)"> None </button>
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



	<script>
    const YesVote = (election_id, customer_id, voters_id) => 
    {
        $.ajax({
            type: "POST", 
            url: "inc/yesajaxCalls.php",
            data: "e_id=" + election_id + "&c_id=" + customer_id + "&v_id=" + voters_id, 
            success: function(response) {
                
                if(response == "Success")
                {
                    location.assign("index.php?voteCasted=1");
                }else {
                    location.assign("index.php?voteNotCasted=1");
                }
            }
        });
    }

    const NoVote = (election_id, customer_id, voters_id) => 
    {
        $.ajax({
            type: "POST", 
            url: "inc/noajaxCalls.php",
            data: "e_id=" + election_id + "&c_id=" + customer_id + "&v_id=" + voters_id, 
            success: function(response) {
                
                if(response == "Success")
                {
                    location.assign("index.php?voteCasted=1");
                }else {
                    location.assign("index.php?voteNotCasted=1");
                }
            }
        });
    }

    const NeutralVote = (election_id, customer_id, voters_id) => 
    {
        $.ajax({
            type: "POST", 
            url: "inc/neutralajaxCalls.php",
            data: "e_id=" + election_id + "&c_id=" + customer_id + "&v_id=" + voters_id, 
            success: function(response) {
                
                if(response == "Success")
                {
                    location.assign("index.php?voteCasted=1");
                }else {
                    location.assign("index.php?voteNotCasted=1");
                }
            }
        });
    }


    const NoneVote = (election_id, customer_id, voters_id) => 
    {
        $.ajax({
            type: "POST", 
            url: "inc/noneajaxCalls.php",
            data: "e_id=" + election_id + "&c_id=" + customer_id + "&v_id=" + voters_id, 
            success: function(response) {
                
                if(response = "Success")
                {
                    location.assign("index.php?voteCasted=1");
                }else {
                    location.assign("index.php?voteNotCasted=1");
                }
            }
        });
    }

</script>



<?php
    require_once("inc/footer.php");
?>

