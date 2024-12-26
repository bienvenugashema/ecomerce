<?php
// Include the database configuration file
include 'config.php';

// Initialize variables for success/error messages
$message = '';
$messageType = '';

// Add order_status column if it doesn't exist
$checkColumn = $conn->query("SHOW COLUMNS FROM orders LIKE 'order_status'");
if ($checkColumn->num_rows == 0) {
    $conn->query("ALTER TABLE orders ADD COLUMN order_status VARCHAR(20) DEFAULT 'pending'");
}

// Function to safely get status filter
function getStatusFilter() {
    return isset($_GET['status']) ? filter_var($_GET['status'], FILTER_SANITIZE_STRING) : '';
}

// Function to update order status
function updateOrderStatus($conn, $orderId, $newStatus) {
    // Validate status values
    $validStatuses = ['pending', 'delivered', 'canceled'];
    if (!in_array($newStatus, $validStatuses)) {
        return [false, "Invalid status value"];
    }

    try {
        $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $newStatus, $orderId);
        
        if ($stmt->execute()) {
            return [true, "Order status updated successfully"];
        } else {
            return [false, "Failed to update order status"];
        }
    } catch (Exception $e) {
        error_log("Error updating order status: " . $e->getMessage());
        return [false, "An error occurred while updating the status"];
    }
}

// Handle status update
if (isset($_POST['update_status']) && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $orderId = filter_var($_POST['order_id'], FILTER_VALIDATE_INT);
    $newStatus = filter_var($_POST['new_status'], FILTER_SANITIZE_STRING);
    
    if ($orderId === false) {
        $message = "Invalid order ID";
        $messageType = "danger";
    } else {
        [$success, $updateMessage] = updateOrderStatus($conn, $orderId, $newStatus);
        $message = $updateMessage;
        $messageType = $success ? "success" : "danger";
        
        // Redirect to refresh the page after successful update
        if ($success) {
            header("Location: orders.php?message=" . urlencode($updateMessage) . "&type=success");
            exit();
        }
    }
}

// Get message from URL if redirected
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    $messageType = $_GET['type'] ?? 'info';
}

// Fetch orders from database
$statusFilter = getStatusFilter();
try {
    if ($statusFilter) {
        $stmt = $conn->prepare("SELECT *, COALESCE(order_status, 'pending') as current_status FROM orders WHERE order_status = ?");
        $stmt->bind_param("s", $statusFilter);
    } else {
        $stmt = $conn->prepare("SELECT *, COALESCE(order_status, 'pending') as current_status FROM orders");
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
} catch (Exception $e) {
    error_log("Error fetching orders: " . $e->getMessage());
    $message = "Error loading orders";
    $messageType = "danger";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management</title>
    <link rel="stylesheet" href="bootstrap.css">
    <style>
        .status-pending { color: #ffc107; }
        .status-delivered { color: #28a745; }
        .status-canceled { color: #dc3545; }
        .alert {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'dashboard.php'; ?>

    <div class="container-fluid px-4">
        <h2 class="mt-4">Orders Management</h2>

        <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php endif; ?>

        <!-- Order Status Filter -->
        <form method="GET" class="mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <label for="status">Filter by Status:</label>
                </div>
                <div class="col-auto">
                    <select name="status" id="status" class="form-control">
                        <option value="">All Orders</option>
                        <option value="pending" <?php echo ($statusFilter == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="delivered" <?php echo ($statusFilter == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                        <option value="canceled" <?php echo ($statusFilter == 'canceled') ? 'selected' : ''; ?>>Canceled</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <!-- Orders Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Client Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Country</th>
                        <th>Payment Method</th>
                        <th>Total Price (USD)</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): 
                        $currentStatus = $row['current_status'] ?? 'pending';
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['buyer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['buyer_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['buyer_phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['buyer_address']); ?></td>
                            <td><?php echo htmlspecialchars($row['buyer_city']); ?></td>
                            <td><?php echo htmlspecialchars($row['buyer_state']); ?></td>
                            <td><?php echo htmlspecialchars($row['buyer_country']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                            <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                            <td class="status-<?php echo htmlspecialchars($currentStatus); ?>">
                                <?php echo ucfirst(htmlspecialchars($currentStatus)); ?>
                            </td>
                            <td>
                                <form method="POST" class="status-update-form" onsubmit="return confirmStatusUpdate(this);">
                                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                    <div class="input-group">
                                        <select name="new_status" class="form-control form-control-sm">
                                            <option value="pending" <?php echo ($currentStatus == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="delivered" <?php echo ($currentStatus == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                            <option value="canceled" <?php echo ($currentStatus == 'canceled') ? 'selected' : ''; ?>>Canceled</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="submit" name="update_status" class="btn btn-primary btn-sm">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="13" class="text-center">No orders found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function confirmStatusUpdate(form) {
        const orderId = form.querySelector('[name="order_id"]').value;
        const newStatus = form.querySelector('[name="new_status"]').value;
        return confirm(`Are you sure you want to update Order #${orderId} status to ${newStatus}?`);
    }
    </script>
</body>
</html>

<?php
if (isset($stmt)) {
    $stmt->close();
}

?>