<?php
if (isset($_GET['added'])) {
?>
    <div class="alert alert-success my-3" role="alert">
        Election has been added Successfully!!!
    </div>
<?php
} else if (isset($_GET['delete_id'])) {
    $d_id = $_GET['delete_id'];
    mysqli_query($db, "DELETE  FROM elections WHERE id = '" . $d_id . "'") or die(mysqli_error($db));
?>

    <div class="alert alert-danger my-3" role="alert">
        Election has been deleted Successfully!!!
    </div>
<?php
}

?>

<div class="row my-3">
    <div class="col-4">
        <h3>Add New Elections</h3>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="election_topic" placeholder="Election Topic" class="form-control" required />
            </div><br>
            <div class="form-group">
                <input type="number" name="number_of_candidates" placeholder="No. of Candidates" class="form-control" required />
            </div><br>
            <div class="form-group">
                <input type="text" onfocus="this.type='date'" name="starting_date" placeholder="Starting Date" class="form-control" required />
            </div><br>
            <div class="form-group">
                <input type="text" onfocus="this.type='date'" name="ending_date" placeholder="Ending Date" class="form-control" required />
            </div><br>
            <input type="submit" value="Add Election" name="addElectionBtn" class="btn btn-success" />
        </form>
    </div>
    <div class="col-8">
        <h3>Upcoming Elections</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Election Name</th>
                    <th scope="col">No. of Candidates</th>
                    <th scope="col">Starting Date</th>
                    <th scope="col">Ending Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $fetchingData = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db));

                $isAnyElectionAdded = mysqli_num_rows($fetchingData);
                if ($isAnyElectionAdded > 0) {

                    $sno = 1;
                    while ($row = mysqli_fetch_assoc($fetchingData)) {
                        $election_id = $row['id'];
                ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><?php echo $row['election_topic']; ?></td>
                            <td><?php echo $row['no_of_candidates']; ?></td>
                            <td><?php echo $row['starting_date']; ?></td>
                            <td><?php echo $row['ending_date']; ?></td>
                            <?php
                            if (($row['status']) == 'Active') {
                            ?>

                                <td class="text-green"><?php echo $row['status']; ?></td>

                            <?php
                            } else if ($row['status'] == 'inActive') {
                            ?>
                                <td class="text-red"><?php echo $row['status']; ?></td>
                            <?php
                            } else {
                            ?>
                                <td class="text-red"><?php echo $row['status']; ?></td>
                            <?php
                            }
                            ?>

                            <td>

                                <button class="btn btn-sm btn-warning">Edit</a>
                                    <button class="btn btn-sm btn-danger" onclick="DeleteData(<?php echo $election_id; ?>)">Delete</a>
                            </td>

                        </tr>

                    <?php

                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7"> Any election is not added yet!! </td>
                    </tr>



                <?php
                }



                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const DeleteData = (e_id) => {
        let c = confirm("Are you really want to delete it??");

        if (c == true) {
            location.assign("index.php?addElectionPage=1&delete_id=" + e_id);
        }
    }
</script>

<?php

if (isset($_POST['addElectionBtn'])) {
    $election_topic = mysqli_real_escape_string($db, $_POST['election_topic']);
    $number_of_candidates = mysqli_real_escape_string($db, $_POST['number_of_candidates']);
    $starting_date = mysqli_real_escape_string($db, $_POST['starting_date']);
    $ending_date = mysqli_real_escape_string($db, $_POST['ending_date']);
    $inserted_by = $_SESSION['username'];
    $inserted_on = date("Y-m-d");

    $date1 = date_create($inserted_on);
    $date2 = date_create($starting_date);
    $diff = date_diff($date1, $date2);


    if ((int)$diff->format("%R%a") > 0) {
        $status = "inActive";
    } else {
        $status = "Active";
    }


    //inserting query
    mysqli_query($db, "INSERT INTO elections(election_topic,no_of_candidates,starting_date,ending_date,status,inserted_by,inserted_on) VALUES('" . $election_topic . "','" . $number_of_candidates . "','" . $starting_date . "','" . $ending_date . "','" . $status . "','" . $inserted_by . "','" . $inserted_on . "') ") or die(mysqli_error($db));

?>
    <script>
        location.assign("index.php?addElectionPage=1&added=1")
    </script>

<?php




}
?>