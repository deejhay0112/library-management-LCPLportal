<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPicture = isset($_POST['image']) ? $_POST['image'] : null;

    // Validate if image data is provided
    if (!empty($idPicture)) {
        // Define the upload directory
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/LCPL/id_pictures/';
        
        // Generate a unique file name for the image
        $imageFileName = uniqid() . '.png';
        $imagePath = $uploadDir . $imageFileName;

        // Remove the "data:image/png;base64," part and decode the data
        $idPicture = str_replace('data:image/png;base64,', '', $idPicture);
        $idPicture = str_replace(' ', '+', $idPicture);
        $decodedData = base64_decode($idPicture);

        // Check if decoding was successful
        if ($decodedData === false) {
            echo json_encode(['success' => false, 'message' => 'Invalid image data.']);
            exit;
        }

        // Save the image to the specified path
        if (file_put_contents($imagePath, $decodedData)) {
            echo json_encode(['success' => true, 'message' => 'Image saved successfully.', 'path' => '/experiment/id_pictures/' . $imageFileName]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save the image.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No image data received.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
