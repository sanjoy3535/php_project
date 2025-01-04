<style>
    body {
        cursor: pointer;
    }

    input[type='submit'] {
        cursor: pointer;
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
    }

    input[name='approve'] {
        background-color: #4CAF50;
        color: white;
    }

    input[name='deny'] {
        background-color: #f44336;
        color: white;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    table, th, td {
        border: 1px solid black;
        text-align: center;
        padding: 10px;
    }

    th {
        background-color: #f2f2f2;
    }

    .approved {
        color: blue;
    }
</style>

<?php 
require('database.php');

try {
    $smtp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch Data
    $select = "SELECT id, name, email, contact, status FROM register where name BETWEEN 'o' AND 'one' ";
    $prepare = $smtp->prepare($select);
    $prepare->execute();

    $fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);

    if (count($fetch) > 0) {
        // Display Table
        echo "<table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Action</th>
        </tr>";

        foreach ($fetch as $value) { ?>
            <tr>
                <td><?php echo htmlspecialchars($value['name']); ?></td>
                <td><?php echo htmlspecialchars($value['email']); ?></td>
                <td><?php echo htmlspecialchars($value['contact']); ?></td>
                <td>
                    <form action="" method="POST">
                        <input type="hidden" name="id" value="<?php echo intval($value['id']); ?>">
                        <?php if ($value['status'] !== 'approve') { ?>
                            <input type="submit" name="approve" value="Approve">
                        <?php } else { ?>
                            <span class="approved">Approved</span>
                        <?php } ?>
                        <input type="submit" name="deny" value="Deny" onclick="return confirm('Are you sure you want to deny this user?');">
                    </form>
                </td>
            </tr>
        <?php }
        echo "</table>";
    } else {
        echo "<p>No Data Found</p>

        ";
    }

    // Approve Logic
    if (isset($_POST['approve'])) {
        $id = intval($_POST['id']);
        $update = "UPDATE register SET status='approve' WHERE id=:id";
        $stmt = $smtp->prepare($update);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<script>
               
                window.location.reload();
            </script>";
        }
    }

    // Deny Logic
    if (isset($_POST['deny'])) {
        $id = intval($_POST['id']);
        $delete = "DELETE FROM register WHERE id=:id";
        $stmt = $smtp->prepare($delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<script>
                alert('User denied successfully.');
                window.location.href='user_data.php';
            </script>";
        } 
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "<p style='color:red;'>An error occurred. Please try again later.</p>";
}
?>
