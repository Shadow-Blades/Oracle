<?php
// Set the directory path
$directory = 'C:/inetpub/logs/LogFiles/W3SVC2/';

// Set the number of days
$days = 14;

// Convert days to seconds
$timeLimit = $days * 24 * 60 * 60;

// Get the current time
$currentTime = time();

// Open the directory
if ($handle = opendir($directory)) {
    while (false !== ($file = readdir($handle))) {
        // Skip '.' and '..'
        if ($file != '.' && $file != '..') {
            $filePath = $directory . DIRECTORY_SEPARATOR . $file;

            // Check if it is a file
            if (is_file($filePath)) {
                // Get the file's last modification time
                $fileModificationTime = filemtime($filePath);

                // Calculate the file's age
                $fileAge = $currentTime - $fileModificationTime;

                // Delete the file if it is older than the time limit
                if ($fileAge > $timeLimit) {
                    unlink($filePath);
                    echo "Deleted: $filePath\n";
                }
            }
        }
    }

    // Close the directory
    closedir($handle);
} else {
    echo "Could not open directory: $directory";
}
?>
