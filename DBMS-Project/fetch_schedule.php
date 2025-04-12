<?php
include 'connection.php';

if (isset($_GET['route_id'])) {
    $route_id = $_GET['route_id'];

    $locationToDSCQuery = "SELECT bus_name, time FROM Location_to_DSC WHERE route_id = ?";
    $stmt = $conn->prepare($locationToDSCQuery);
    $stmt->bind_param("i", $route_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $locationToDSC = [];
    while ($row = $result->fetch_assoc()) {
        $locationToDSC[] = $row;
    }

    $DSCToLocationQuery = "SELECT bus_name, time FROM DSC_to_Location WHERE route_id = ?";
    $stmt = $conn->prepare($DSCToLocationQuery);
    $stmt->bind_param("i", $route_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $DSCToLocation = [];
    while ($row = $result->fetch_assoc()) {
        $DSCToLocation[] = $row;
    }

    echo json_encode(["locationToDSC" => $locationToDSC, "DSCToLocation" => $DSCToLocation]);
}
?>
