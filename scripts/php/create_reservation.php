<?php
    require_once('db_connect.php'); // Include the database connection file
    require '../../libs/PHPMailer-master/src/Exception.php';
    require '../../libs/PHPMailer-master/src/PHPMailer.php';
    require '../../libs/PHPMailer-master/src/SMTP.php';

    $data = $_POST;

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into tbl_customers
        $stmt = $conn->prepare("INSERT INTO tbl_customers (first_name, last_name, email, cellphone_number) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception($conn->error);
        }
        $stmt->bind_param("ssss", $data['firstName'], $data['lastName'], $data['email'], $data['phoneNumber']);
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $customerId = $conn->insert_id;

        // Generate a unique booking ID
        $bookingId = time();

        // Insert into tbl_bookings
        $status = 'pending';
        $stmt = $conn->prepare("INSERT INTO tbl_bookings (booking_id, customer_id, check_in_date, check_out_date, status) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception($conn->error);
        }
        $stmt->bind_param("iisss", $bookingId, $customerId, $data['bookingData'][0]['checkInDate'], end($data['bookingData'])['checkOutDate'], $status);
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }

        // Insert into tbl_bills
        $totalAmount = array_sum(array_column($data['bookingData'], 'itemTotal'));
        $totalPaid = 0;
        $status = 'unpaid';
        $dueDate = date('Y-m-d', strtotime('+3 days'));
        $stmt = $conn->prepare("INSERT INTO tbl_bills (booking_id, total_amount, total_paid, status, due_date) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception($conn->error);
        }
        $stmt->bind_param("iddss", $bookingId, $totalAmount, $totalPaid, $status, $dueDate);
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $billId = $conn->insert_id;

        // Loop through bookingData
        foreach ($data['bookingData'] as $booking) {
            // Insert into tbl_booking_details
            $notes = isset($booking['notes']) ? $booking['notes'] : NULL;
            $stmt = $conn->prepare("INSERT INTO tbl_booking_details (booking_id, room_id, number_of_adults, number_of_children, note) VALUES (?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception($conn->error);
            }
            $stmt->bind_param("iiiss", $bookingId, $booking['roomNumber'], $booking['adults'], $booking['kids'], $notes);
            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            // Insert into tbl_billing_items
            $description = $booking['roomTypeName'];
            $stmt = $conn->prepare("INSERT INTO tbl_billing_items (bill_id, description, price, room_id) VALUES (?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception($conn->error);
            }
            $stmt->bind_param("isdi", $billId, $description, $booking['roomPrice'], $booking['roomNumber']);
            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }
        }

        // Generate a random alphanumeric password with a length of 5 characters
        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);

        // Insert into tbl_booking_password
        $stmt = $conn->prepare("INSERT INTO tbl_booking_password (booking_id, password) VALUES (?, ?)");
        if ($stmt === false) {
            throw new Exception($conn->error);
        }
        $stmt->bind_param("is", $bookingId, $password);
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        
        // CREATE FOLDER
        // Create a directory with the booking ID as the name
        $dir = "../../booking/$bookingId";
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                throw new Exception("Failed to create directory '$dir'");
            }
        }

        // If no errors, commit the transaction
        $conn->commit();

        // Send email
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->Host = 'smtp-relay.sendinblue.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'projectlagun@gmail.com';
        $mail->Password = 'LJs4gqRby8EwD3ha';
        $mail->setFrom('projectlagun@gmail.com', 'Lagun Hotel');
        $mail->addAddress($data['email'], $data['firstName'] . ' ' . $data['lastName']);
        $mail->Subject = 'Booking Summary';
        $mail->Body = '
            <h1 style="color:blue;">Booking Summary</h1>
            <p style="color:red;">Dear ' . $data['firstName'] . ' ' . $data['lastName'] . ',</p>
            <p style="font-size:20px;">Thank you for your booking. Here are the details of your reservation:</p>
            <table style="width:100%; border: 1px solid black;">
                <tr>
                    <th style="border: 1px solid black;">Booking ID</th>
                    <th style="border: 1px solid black;">Check-in Date</th> 
                    <th style="border: 1px solid black;">Check-out Date</th>
                    <th style="border: 1px solid black;">Total Amount</th>
                    <th style="border: 1px solid black;">Password</th>
                </tr>
                <tr>
                    <td style="border: 1px solid black;">' . $bookingId . '</td>
                    <td style="border: 1px solid black;">' . $data['bookingData'][0]['checkInDate'] . '</td> 
                    <td style="border: 1px solid black;">' . end($data['bookingData'])['checkOutDate'] . '</td>
                    <td style="border: 1px solid black;">' . $totalAmount . '</td>
                    <td style="border: 1px solid black;">' . $password . '</td>
                </tr>
            </table>
            <br>
            <h2 style="color:blue;">Billing Items</h2>
            <table style="width:100%; border: 1px solid black;">
                <tr>
                    <th style="border: 1px solid black;">Description</th>
                    <th style="border: 1px solid black;">Price</th>
                </tr>';

        foreach ($data['bookingData'] as $booking) {
            $mail->Body .= '
                <tr>
                    <td style="border: 1px solid black;">' . $booking['roomTypeName'] . '</td>
                    <td style="border: 1px solid black;">' . $booking['roomPrice'] . '</td>
                </tr>';
        }

        $mail->Body .= '
            </table>
            <br>
            <p style="font-size:20px;">Total: ' . $totalAmount . '</p>
            <p style="font-size:20px;">VAT (12%): ' . ($totalAmount * 0.12) . '</p>
            <p style="font-size:20px;">Total Amount Due: ' . ($totalAmount * 1.12) . '</p>
            <p style="font-size:20px;">Please keep this email for your records. If you have any questions, feel free to contact us.</p>
            <p style="font-size:20px;">Best regards,</p>
            <p style="font-size:20px;">Lagun Hotel</p>
        ';

        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message sent!';
        }
    } catch (Exception $e) {
        // An error occurred; rollback the transaction
        $conn->rollback();
        echo "Failed: " . $e->getMessage();
    }

    $stmt->close();
    $conn->close();
?>
