<?php
require_once('db_connect.php'); // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['amenity_id'])) {
        // Fetch a specific amenity by amenity_id
        $amenityId = $_GET['amenity_id'];

        $sql = "SELECT * FROM tbl_amenities WHERE amenity_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $amenityId);

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    $stmt->close();
                    $conn->close();
                    header('Content-Type: application/json');
                    echo json_encode($row);
                    exit;
                }
            }
            $stmt->close();
        }
    } else {
        // Fetch all amenities if no amenity_id is provided
        $sql = "SELECT * FROM tbl_amenities";
        $result = $conn->query($sql);

        $data = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        $conn->close();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

// Invalid request
echo 'Invalid request.';
?>
