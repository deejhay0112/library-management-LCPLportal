<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin-login.php");
    exit();
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: 0");
header("Pragma: no-cache");

$currentPage = 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
 <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@3.20.0/dist/tf.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
 <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS (Optional, if you need JS components like dropdowns, modals) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>




</head>
<style>

body {
    background-color: #0f172a;
    font-family: 'Poppins', sans-serif;
    color: #f1f5f9;
}

.chart-container {
    background-color: #1e293b;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    overflow: hidden;
}

.c-dashboardInfo {
    background: linear-gradient(135deg, #1f2937, #2c384a);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.6);
}

select, input, button {
    border-radius: 8px;
    padding: 10px;
}

button:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(255, 255, 255, 0.2);
}

.card {
    background: #1f2937;
    color: #f1f5f9;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

h3 {
    color: #38bdf8;
}
  .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        box-sizing: border-box;
    }

    /* Combined Dashboard Container */
    .combined-dashboard-container {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        background-color: #1e293b;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
        margin-bottom: 40px;
    }

    /* Left Side Cards */
    .dashboard-cards {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 300px;
    }

    .c-dashboardInfo {
        background: linear-gradient(135deg, rgba(31, 41, 55, 0.9), rgba(44, 56, 74, 0.95));
        color: #f1f5f9;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        text-align: center;
    }

    .c-dashboardInfo:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(255, 255, 255, 0.1);
    }

    .c-dashboardInfo__title {
        font-size: 1.1rem;
        margin-bottom: 10px;
    }

    .c-dashboardInfo__count {
        font-size: 2rem;
        font-weight: bold;
        color: #38bdf8;
    }

    /* Right Side Chart Section */
    .gender-chart-section {
        flex: 1; /* Takes the remaining space */
        background-color: #1f2937;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
    }

    .form-group {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
        justify-content: center;
    }

    .form-group select, .form-group button {
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 1rem;
        border: none;
        outline: none;
    }

    .form-group select {
        background-color: #334155;
        color: #f1f5f9;
    }

    .form-group button {
        background-color: #38bdf8;
        color: #0f172a;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .form-group button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(56, 189, 248, 0.3);
    }

    .chart-container {
        background-color: #1e293b;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
    }
 /* General Container Styling */
    .visitor-chart-container {
        background-color: #1e293b;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
        margin-bottom: 40px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Form Group */
    .form-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        align-items: center;
    }

    .form-group label {
        font-size: 1rem;
        color: #f1f5f9;
    }

    .form-group select {
        padding: 10px 15px;
        border-radius: 8px;
        background-color: #334155;
        color: #f1f5f9;
        border: none;
        font-size: 1rem;
        outline: none;
    }

    .form-group button {
        background-color: #38bdf8;
        color: #0f172a;
        font-weight: 600;
        border: none;
        padding: 10px 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-group button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(56, 189, 248, 0.3);
    }

    /* Chart Container */
    .chart-container {
        background-color: #1f2937;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
        overflow: hidden;
    }
      .education-chart-container {
        background-color: #1e293b;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
        margin-bottom: 40px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Section Title */
    .education-chart-container h3 {
        font-size: 1.5rem;
        color: #38bdf8;
        font-weight: 600;
        text-align: center;
        margin-bottom: 15px;
    }

    /* Form Group for Controls */
    .form-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        align-items: center;
    }

    .form-group input, .form-group button {
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 1rem;
        border: none;
        outline: none;
    }

    .form-group input {
        background-color: #334155;
        color: #f1f5f9;
    }

    .form-group button {
        background-color: #38bdf8;
        color: #0f172a;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-group button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(56, 189, 248, 0.3);
    }

    /* Chart Container */
    .chart-container {
        background-color: #1f2937;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }
    /* General Container Styling */
    .student-retiree-container {
        background-color: #1e293b;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
        margin-bottom: 40px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Section Title */
    .student-retiree-container h3 {
        font-size: 1.5rem;
        color: #38bdf8;
        font-weight: 600;
        text-align: center;
        margin-bottom: 15px;
    }

    /* Form Group */
    .form-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        align-items: center;
    }

    .form-group label {
        font-size: 1rem;
        color: #f1f5f9;
    }

    .form-group input, .form-group button {
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 1rem;
        border: none;
        outline: none;
    }

    .form-group input {
        background-color: #334155;
        color: #f1f5f9;
    }

    .form-group button {
        background-color: #38bdf8;
        color: #0f172a;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-group button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(56, 189, 248, 0.3);
    }

    /* Chart Container */
    .chart-container {
        background-color: #1f2937;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 500px;
    }
   /* Visitor Trends Container */
    .visitor-trends-container {
        background-color: #1e293b;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
        margin-bottom: 40px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Section Title */
    .visitor-trends-container h1 {
        font-size: 1.8rem;
        color: #38bdf8;
        font-weight: 600;
        text-align: center;
        margin-bottom: 15px;
    }

    /* Controls Styling */
    .controls {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
    }

    .controls label {
        font-size: 1rem;
        color: #f1f5f9;
    }

    .controls select, .controls input, .controls button {
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 1rem;
        border: none;
        outline: none;
    }

    .controls select, .controls input {
        background-color: #334155;
        color: #f1f5f9;
    }

    .controls button {
        background-color: #38bdf8;
        color: #0f172a;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .controls button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(56, 189, 248, 0.3);
    }

    /* Notification Styling */
    #notification {
        text-align: center;
        font-size: 1rem;
        color: #f1f5f9;
        margin-bottom: 15px;
    }

    /* Card Container */
    .card-container {
        display: flex;
        gap: 20px;
        justify-content: center;
    }

    .card {
        flex: 1;
        background-color: rgba(31, 41, 55, 0.9);
        color: #f1f5f9;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card h3 {
        font-size: 1.2rem;
        margin-bottom: 10px;
        color: #38bdf8;
    }

    .card p {
        font-size: 1.75rem;
        font-weight: 600;
        margin: 0;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(255, 255, 255, 0.1);
    }

    /* Chart Container */
    /* Chart Container - Enhanced */
/* Chart Container - Bigger and Responsive */
.chart-container {
    background-color: #1f2937; /* Background color for contrast */
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%; /* Take full width of parent container */
    min-height: 500px; /* Set a minimum height */
    max-height: 700px; /* Set a maximum height */
}

/* Canvas - Full Size */
.chart-container canvas {
    width: 100% !important; /* Force canvas to stretch horizontally */
    height: 100% !important; /* Force canvas to stretch vertically */
}

/* Adjust Chart Grid and Axis Label Colors */
.chart-container .chartjs-render-monitor {
    color: #f8f9fa;
}

       /* General Container Styling */
    .student-retiree-container {
        background-color: #1e293b;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
        margin-bottom: 40px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Section Title */
    .student-retiree-container h3 {
        font-size: 1.5rem;
        color: #38bdf8;
        font-weight: 600;
        text-align: center;
        margin-bottom: 15px;
    }

    /* Form Group Styling */
    .form-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        align-items: center;
    }

    .form-group label {
        font-size: 1rem;
        color: #f1f5f9;
        font-weight: 500;
    }

    .form-group input {
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 1rem;
        border: none;
        outline: none;
        background-color: #334155;
        color: #f1f5f9;
        transition: all 0.2s ease;
    }

    .form-group input:focus {
        box-shadow: 0 0 8px rgba(56, 189, 248, 0.4);
        border: 1px solid #38bdf8;
    }

    .form-group button {
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        background-color: #38bdf8;
        color: #0f172a;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
    }

    .form-group button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(56, 189, 248, 0.3);
    }

    .form-group button i {
        font-size: 1.2rem;
    }

    /* Chart Container */
    /* Chart Container */
                        .chart-container {
                            background-color: #1f2937;
                            border-radius: 12px;
                            padding: 20px;
                            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            width: 100%;
                            min-height: 500px;
                            max-height: 700px;
                            overflow: hidden;
                        }
                        
                        /* Canvas - Full Size */
                        canvas {
                            width: 100% !important;
                            height: 100% !important;
                            display: block;
                        }


    /* Responsive */
    @media (max-width: 768px) {
        .form-group {
            flex-direction: column;
            gap: 10px;
        }

        .student-retiree-container h3 {
            font-size: 1.3rem;
        }

        canvas {
            max-width: 100%;
        }
    }
    /* Student-Retiree Container */
.student-retiree-container {
    background-color: #1e293b;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
    margin-bottom: 40px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Section Title */
.student-retiree-container h3 {
    font-size: 1.5rem;
    color: #38bdf8;
    font-weight: 600;
    text-align: center;
    margin-bottom: 15px;
}

/* Form Group */
.student-retiree-container .form-group {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
    align-items: center;
}

.student-retiree-container .form-group label {
    font-size: 1rem;
    color: #f1f5f9;
    font-weight: 500;
}

.student-retiree-container .form-group input {
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 1rem;
    border: none;
    outline: none;
    background-color: #334155;
    color: #f1f5f9;
    transition: all 0.2s ease;
}

.student-retiree-container .form-group input:focus {
    box-shadow: 0 0 8px rgba(56, 189, 248, 0.4);
    border: 1px solid #38bdf8;
}

.student-retiree-container .form-group button {
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    background-color: #38bdf8;
    color: #0f172a;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s ease;
}

.student-retiree-container .form-group button:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(56, 189, 248, 0.3);
}

/* Chart Container */
.student-retiree-chart-container {
    background-color: #1f2937;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    max-width: 600px; /* Limit the width for proper chart proportions */
    margin: 0 auto; /* Center the container */
    aspect-ratio: 1 / 1; /* Ensures the chart stays square */
}

/* Canvas Styling */
.student-retiree-chart-container canvas {
    width: 100% !important;
    height: 100% !important;
    display: block;
}
/* Combined Chart Container - Side by Side */
.combined-chart-container {
    display: flex; /* Use Flexbox for side-by-side layout */
    gap: 30px; /* Add space between the two sections */
    background-color: #1e293b;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
}

/* Each Chart Section */
.education-chart-section, 
.student-retiree-section {
    flex: 1; /* Each section takes equal space */
    background-color: #1f2937;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Section Titles */
.education-chart-section h3, 
.student-retiree-section h3 {
    color: #38bdf8;
    font-size: 1.5rem;
    font-weight: 600;
    text-align: center;
    margin-bottom: 20px;
}

/* Form Group for Student and Retiree Section */
.form-group {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
    margin-bottom: 20px;
}

.form-group label {
    font-size: 1rem;
    color: #f1f5f9;
    font-weight: 500;
}

.form-group input, 
.form-group button {
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 1rem;
    border: none;
    background-color: #334155;
    color: #f1f5f9;
    transition: all 0.2s ease;
}

.form-group button {
    background-color: #38bdf8;
    color: #0f172a;
    font-weight: 600;
    cursor: pointer;
}

.form-group button:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(56, 189, 248, 0.3);
}

/* Chart Containers */
.chart-container {
    background-color: #1e293b;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    max-width: 100%;
    margin: 0 auto;
}

.chart-container canvas {
    width: 100% !important;
    height: auto !important;
    display: block;
}

/* Responsive Layout */
@media (max-width: 768px) {
    .combined-chart-container {
        flex-direction: column; /* Stack charts vertically on smaller screens */
        gap: 20px;
    }
}
/* General Chart Container */
.chart-container {
    background-color: #1f2937;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%; /* Full width */
    height: 400px; /* Fixed height to keep uniformity */
    margin: 0 auto;
}

/* Canvas Styling */
.chart-container canvas {
    width: 100% !important;
    height: 100% !important;
    display: block;
}

/* Combined Chart Container */
.combined-chart-container {
    display: flex;
    gap: 30px;
    background-color: #1e293b;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
}

/* Individual Chart Sections */
.occupation-chart-section
.education-chart-section,
.student-retiree-section {
    flex: 1;
    background-color: #1f2937;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Titles */
.education-chart-section h3,
.student-retiree-section h3 {
    color: #38bdf8;
    font-size: 1.5rem;
    font-weight: 600;
    text-align: center;
    margin-bottom: 15px;
}

/* Form Group (for Filters) */
.form-group {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-bottom: 15px;
}

.form-group label,
.form-group input,
.form-group button {
    font-size: 1rem;
    color: #f1f5f9;
}

.form-group input,
.form-group button {
    padding: 10px 15px;
    border-radius: 8px;
    background-color: #334155;
    border: none;
    color: #f1f5f9;
    outline: none;
}

.form-group button {
    background-color: #38bdf8;
    color: #0f172a;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.form-group button:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(56, 189, 248, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .combined-chart-container {
        flex-direction: column;
        gap: 20px;
    }
    .chart-container {
        height: 300px; /* Smaller height for mobile */
    }
}

/* Student and Retiree Chart Section */
.student-retiree-section {
    flex: 1; /* Takes equal space in the container */
    background-color: #1f2937; /* Dark background for the chart section */
    border-radius: 12px; /* Rounded corners */
    padding: 20px; /* Inner padding */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); /* Subtle shadow effect */
    display: flex; /* Enables flexible layout */
    flex-direction: column; /* Align items in a column */
    justify-content: center; /* Centers chart vertically */
    align-items: center; /* Centers chart horizontally */
}

/* Student and Retiree Chart Title */
.student-retiree-section h3 {
    color: #38bdf8; /* Bright blue color for the title */
    font-size: 1.5rem; /* Title font size */
    font-weight: 600; /* Makes the title bold */
    text-align: center; /* Center-align the title */
    margin-bottom: 15px; /* Space below the title */
}

/* Chart Container */
.student-retiree-section .chart-container {
    background-color: #1e293b; /* Slightly darker background for the chart */
    border-radius: 12px; /* Rounded corners */
    padding: 20px; /* Space inside the container */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5); /* Box shadow for depth */
    display: flex; /* Enables flexible alignment */
    justify-content: center; /* Centers the chart horizontally */
    align-items: center; /* Centers the chart vertically */
    width: 100%; /* Full width of the parent container */
    height: 400px; /* Fixed height for uniform appearance */
    margin: 0 auto; /* Center the container */
}

/* Chart Canvas Styling */
.student-retiree-section .chart-container canvas {
    width: 100% !important; /* Forces canvas to stretch to the container width */
    height: 100% !important; /* Forces canvas to stretch to the container height */
    display: block; /* Ensures proper rendering */
}
        /* Peak Hours Container */
        .peak-hours-chart-section {
            background-color: #1e293b; /* Dark blue */
            border-radius: 16px; /* Rounded corners */
            padding: 30px; /* Inner spacing */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); /* Depth effect */
            margin: 20px auto; /* Centered */
            max-width: 900px; /* Responsive width */
            display: flex;
            flex-direction: column;
            gap: 20px;
            text-align: center; /* Center align title */
        }

        /* Section Title */
        .peak-hours-chart-section h3 {
            font-size: 2rem;
            color: #38bdf8; /* Light blue accent */
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        /* Chart Container */
        .chart-container {
            background-color: #0f172a; /* Slightly darker background for contrast */
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #peakHoursChart {
            width: 100%;
            height: 400px; /* Default chart height */
        }

        /* Responsive */
        @media (max-width: 768px) {
            .peak-hours-chart-section {
                padding: 20px;
            }

            .peak-hours-chart-section h3 {
                font-size: 1.5rem;
            }
        }
        /* Container Styles */
/* Container Styles */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    box-sizing: border-box;
}

/* Dashboard Grid Layout */
.combined-dashboard-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 equal columns */
    grid-template-rows: repeat(2, auto); /* 2 rows */
    gap: 20px; /* Spacing between cards */
    background-color: #1e293b;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
}

/* Dashboard Card Styling */
.c-dashboardInfo {
    background: linear-gradient(135deg, rgba(31, 41, 55, 0.9), rgba(44, 56, 74, 0.95));
    color: #f1f5f9;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    text-align: center;
    transition: all 0.3s ease;
}

.c-dashboardInfo:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 18px rgba(255, 255, 255, 0.1);
}

.c-dashboardInfo__title {
    font-size: 1.1rem;
    margin-bottom: 10px;
    color: #f1f5f9;
}

.c-dashboardInfo__count {
    font-size: 2rem;
    font-weight: bold;
    color: #38bdf8; /* Light blue accent */
}

/* Responsive Design */
@media (max-width: 768px) {
    .combined-dashboard-container {
        grid-template-columns: repeat(2, 1fr); /* 2 columns on smaller screens */
    }
}

@media (max-width: 480px) {
    .combined-dashboard-container {
        grid-template-columns: 1fr; /* Single column layout for mobile */
    }
}
/* Top Borrowed Books Container */
.top-borrowed-books-container {
    background-color: #1e293b;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
    max-width: 1200px;
    margin: 40px auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Title */
.top-borrowed-books-container h1 {
    text-align: center;
    font-size: 1.8rem;
    color: #38bdf8;
    font-weight: 600;
    margin-bottom: 20px;
}

/* Card Container */
/* Card Container */
.card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.card {
    flex: 1 1 200px;
    max-width: 250px;
    background-color: #1f2937; /* Darker background for better contrast */
    border-radius: 12px;
    padding: 20px;
    color: #f1f5f9; /* White text for better readability */
    text-align: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(56, 189, 248, 0.3);
}

/* Headings and Numbers */
.card h3 {
    color: #f1f5f9; /* White for better visibility */
    font-size: 1rem;
    margin-bottom: 10px;
    font-weight: 600;
}

.card p {
    font-size: 2rem; /* Larger font for numbers */
    font-weight: bold;
    margin: 0;
    color: #38bdf8; /* Blue accent for numbers */
}

/* Specific Card Backgrounds */
.bg-yellow-400 { background-color: #facc15; color: #1e293b; }
.bg-green-400 { background-color: #22c55e; color: #1e293b; }
.bg-red-400 { background-color: #ef4444; color: #1e293b; }
.bg-gray-500 { background-color: #6b7280; color: #f1f5f9; }

/* Controls Section */
.controls {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
}

.controls button {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    background-color: #38bdf8;
    color: #0f172a;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.controls button:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(56, 189, 248, 0.3);
}

/* Chart Section */
.chart-container {
    background-color: #1f2937;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 400px;
}

.chart-container canvas {
    width: 100% !important;
    height: 100% !important;
    display: block;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .controls {
        flex-direction: column;
        gap: 10px;
    }

    .card-container {
        flex-direction: column;
        align-items: center;
    }

    .card {
        max-width: 100%;
    }
}
#filter-date, #filter-chart {
    display: none;
}
    #mapeResult {
        display: none; /* Hidden by default */
    }

#p

</style>


<body id="body-pd">
<header class="header" id="header">
    <div class="header__toggle">
        <i class='bx bx-menu' id="header-toggle"></i>
    </div>
    <!-- Adding a date picker to the header -->
    <div class="header__date-picker">
        <input type="date" id="header-date-picker" name="header-date-picker">
    </div>
</header>

    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav__logo">
                    <img src="logo/vls_logo.jpg" alt="Library Logo" class="nav__logo-img">
                    <span class="nav__logo-name">LIPA CITY PUBLIC LIBRARY</span>
                </a>
                <div class="nav__list">
                    <a href="books.php" class="nav__link <?php echo ($currentPage == 'library_management') ? 'active' : ''; ?>">
                        <i class='bx bx-grid-alt nav__icon'></i>
                        <span class="nav__name">Library Management</span>
                    </a>

                    <a href="logbookAdmin.php" class="nav__link <?php echo ($currentPage == 'logbook') ? 'active' : ''; ?>">
                        <i class='bx bx-message-square-detail nav__icon'></i>
                        <span class="nav__name">Logbook</span>
                    </a>

                    <a href="dashboard.php
                    " class="nav__link <?php echo ($currentPage == 'dashboard') ? 'active' : ''; ?>">
                        <i class='bx bx-bar-chart-alt-2 nav__icon'></i>
                        <span class="nav__name">Analytics</span>
                    </a>

                    <a href="transaction_book.php" class="nav__link <?php echo ($currentPage == 'transaction_books') ? 'active' : ''; ?>">
                        <i class='bx bx-book nav__icon'></i>
                        <span class="nav__name">Transaction Books</span>
                    </a>
                    <a href="admin_account.php" class="nav__link <?php echo ($currentPage == 'admin_account') ? 'active' : ''; ?>">
                    <i class='bx bx-user nav__icon'></i>
                        <span class="nav__name">Admin</span>
                    </a>
                </div>
            </div>
            <a href="logout.php" class="nav__link">
                <i class='bx bx-log-out nav__icon'></i>
                <span class="nav__name">Log Out</span>
            </a>
    </div>
   <main class="container py-5">
 <h1>Visitor Forecasting with Chart</h1>
    
    <!-- Filter Dropdown -->
    <div style="text-align: center; margin: 20px;">
        <label for="filter">View: </label>
        <select id="filter" onchange="updateChart()">
            <option value="monthly" selected>Monthly</option>
            <option value="weekly">Weekly</option>
        </select>
    </div>

    <!-- Chart -->
    <div class="chart-section">
        <canvas id="forecastChart"></canvas>
    </div>
    <div id="mapeResult"></div>
 <!-- Combined Dashboard Section -->
<div class="container">
    <div class="combined-dashboard-container">
        <!-- Top Row: 3 Cards -->
        <div class="c-dashboardInfo">
            <h4 class="c-dashboardInfo__title">Total Visitors</h4>
            <span class="c-dashboardInfo__count" id="visitor-count">0</span>
        </div>

        <div class="c-dashboardInfo">
            <h4 class="c-dashboardInfo__title">Borrowed Books</h4>
            <span class="c-dashboardInfo__count" id="borrowed-count">0</span>
        </div>

        <div class="c-dashboardInfo">
            <h4 class="c-dashboardInfo__title">Total Books</h4>
            <span class="c-dashboardInfo__count" id="total-books">Loading...</span>
        </div>

        <!-- Bottom Row: 3 Cards -->
        <div class="c-dashboardInfo">
            <h4 class="c-dashboardInfo__title">Unique Authors</h4>
            <span class="c-dashboardInfo__count" id="unique-authors">Loading...</span>
        </div>

        <div class="c-dashboardInfo">
            <h4 class="c-dashboardInfo__title">Most Recent Acquisition</h4>
            <span class="c-dashboardInfo__count" id="recent-date">Loading...</span>
        </div>

        <div class="c-dashboardInfo">
            <h4 class="c-dashboardInfo__title">Most Common Author</h4>
            <span class="c-dashboardInfo__count" id="common-author">Loading...</span>
        </div>
    </div>
</div>


            </div>

            <!-- Right Side: Gender Chart Section -->
            <div class="gender-chart-section">
                <h3>Gender Distribution</h3>
                <div class="form-group">
                    <!-- Age Range Dropdown -->
                    <select id="age-range" name="age-range" required>
                        <option value="0-10">0-10</option>
                        <option value="11-20">11-20</option>
                        <option value="21-30">21-30</option>
                        <option value="31-40">31-40</option>
                        <option value="41-50">41-50</option>
                        <option value="51-60">51-60</option>
                        <option value="61-70">61-70</option>
                        <option value="71-80">71-80</option>
                        <option value="81-90">81-90</option>
                        <option value="91-100">91-100</option>
                    </select>

                    <!-- Fetch Data Buttons -->
                    <button id="fetch-chart-data">
                        <i class='bx bx-search'></i> Get Age Range Distribution
                    </button>
                    <button id="fetch-daily-data">
                        <i class='bx bx-bar-chart'></i> Get Daily Gender Distribution
                    </button>
                </div>

                <!-- Chart Section -->
                <div class="chart-container">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>
    </div>

  <!-- Visitor Chart Section -->
    <div class="container">
        <div class="visitor-chart-container">
            <h3 class="text-center">Visitor Chart</h3>
            <!-- Form Group for Year Selection -->
            <div class="form-group">
                <label for="chart-year">Select Year:</label>
                <select id="chart-year" name="chart-year" required>
                    <option value="">Select Year</option>
                    <!-- Year options will be populated dynamically -->
                </select>
                <button class="button" id="fetch-visitor-data">
                    <i class='bx bx-search'></i> Fetch Data
                </button>
            </div>
            <!-- Chart Display Section -->
            <div class="chart-container">
                <canvas id="visitorChart"></canvas> <!-- Unique ID for visitor chart -->
            </div>
        </div>
    </div>
                </div>
<div class="container">
    <!-- Combined Chart Container -->
    <div class="combined-chart-container">
        <!-- Education Chart Section -->
        <div class="education-chart-section">
            <h3>Education Chart</h3>
            <div class="chart-container">
                <canvas id="donutChart"></canvas>
            </div>
        </div>

        <!-- Student and Retiree Chart Section -->

        <div class="student-retiree-section">
            <h3>Visitor Destribution</h3>
            <!-- Filter Form -->
            <div class="form-group">
                <label for="filter-date"></label>
                <canvas id="occupationChart"></canvas>
            </div>
        </div>
    </div>
</div>


   <div class="container">
    <!-- Visitor Trends Section -->
    <div class="visitor-trends-container">
        <h1>Visitor Trends</h1>

        <!-- Controls -->
        <div class="controls">
            <label for="trendSelect">Select Trend:</label>
            <select id="trendSelect" onchange="handleTrendChange()">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>

            <label for="startDate">Start Date:</label>
            <input type="date" id="startDate" disabled>

            <label for="endDate">End Date:</label>
            <input type="date" id="endDate" disabled>

            <button id="filterBtn" onclick="fetchTrends()" disabled>
                <i class='bx bx-search'></i> Filter
            </button>
        </div>

        <!-- Notification -->
        <div id="notification">No data loaded yet. Please select options above.</div>

        <!-- Cards Section -->
        <div class="card-container">
            <div class="card">
                <h3>Last Week's Total Visits</h3>
                <p id="lastWeekTotal">0</p>
            </div>
            <div class="card">
                <h3>Last Month's Visits</h3>
                <p id="monthlyTotal">0</p>
            </div>
            <div class="card">
                <h3>Yesterday's Total Visits</h3>
                <p id="lastDayTotal">0</p>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-container">
            <canvas id="visitChart"></canvas>
        </div>
    </div>
</div>
 <div class="peak-hours-chart-section">
        <h3>Peak Hours Comparison</h3>
        <div class="chart-container">
            <canvas id="peakHoursChart"></canvas>
        </div>
    </div>
<div class="top-borrowed-books-container">
    <!-- Title -->
    <h1>Top Borrowed Books</h1>

    <!-- Card Section -->
   <div class="card-container">
    <!-- Pending Card -->
    <div class="card">
        <h3>Pending</h3>
        <p id="pendingCount">1</p>
    </div>

    <!-- Returned Card -->
    <div class="card">
        <h3>Returned</h3>
        <p id="returnedCount">20</p>
    </div>

    <!-- Late Return Card -->
    <div class="card">
        <h3>Late Return</h3>
        <p id="lateReturnCount">1</p>
    </div>

    <!-- Not Returned Card -->
    <div class="card">
        <h3>Not Returned</h3>
        <p id="notReturnedCount">1</p>
    </div>
</div>

    <!-- Controls Section -->
    <div class="controls">
        <button data-filter="daily" class="active" onclick="setFilter('daily')">Daily</button>
<button data-filter="weekly" onclick="setFilter('weekly')">Weekly</button>
<button data-filter="monthly" onclick="setFilter('monthly')">Monthly</button>

<select id="categoryFilter">
    <option value="">All Categories</option>
</select>
    </div>

    <!-- Chart Section -->
    <div class="chart-container">
        <canvas id="lineChart"></canvas>
    </div>
</div>



    <!-- Chart.js CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>


    <!-- JavaScript to handle chart and data -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/visitor_fetch.js"></script>
    <script src="assets/js/fetch_date.js"></script>
    <script src="assets/js/visitor_chart.js"></script>
    <script src="assets/js/visitor_chart_date.js"></script>
    <script src="assets/js/gender_chart.js"></script>
    <script src="assets/js/borrowbook_date.js"></script>
    <script src="assets/js/donut_chart.js"></script>
    <script src="assets/js/dashboard_date_picker.js"></script>
    <link rel="stylesheet" href="assets/js/weeklytrends.js">


<script>
let chartInstance;

// Fetch data for weekly or monthly
async function fetchDataFromDB(filter = 'monthly') {
    try {
        const response = await fetch(`fetch_preddata.php?filter=${filter}`);
        if (!response.ok) throw new Error("Error fetching data");
        const data = await response.json();
        console.log(`Fetched Data (${filter}):`, data);
        const dates = data.map(row => row.Date);
        const visitors = data.map(row => row.visitors);
        return { dates, visitors };
    } catch (error) {
        console.error("Failed to load data:", error);
        return { dates: [], visitors: [] };
    }
}

// Moving average function
function movingAverage(data, windowSize = 3) {
    return data.map((_, idx, arr) => {
        const start = Math.max(0, idx - windowSize + 1);
        const window = arr.slice(start, idx + 1);
        return window.reduce((sum, val) => sum + val, 0) / window.length;
    });
}

// MAPE calculation
function calculateMAPE(actual, predicted) {
    let errorSum = 0;
    for (let i = 0; i < actual.length; i++) {
        if (actual[i] !== 0) errorSum += Math.abs((actual[i] - predicted[i]) / actual[i]);
    }
    return (errorSum / actual.length) * 100;
}

// Improved model with more layers
async function trainOrLoadModel(inputs, targets) {
    let model;
    try {
        model = await tf.loadLayersModel('localstorage://visitor-forecast-model');
        console.log("Model loaded from storage!");
    } catch (error) {
        console.warn("No saved model found. Training a new model...");
        model = tf.sequential();
        model.add(tf.layers.dense({ units: 16, inputShape: [inputs.shape[1]], activation: 'relu' }));
        model.add(tf.layers.dense({ units: 8, activation: 'relu' }));
        model.add(tf.layers.dense({ units: 1 }));
        model.compile({ optimizer: tf.train.adam(0.001), loss: 'meanAbsoluteError' });

        await model.fit(inputs, targets, { epochs: 200, batchSize: 8, verbose: 0 });
        await model.save('localstorage://visitor-forecast-model');
        console.log("Model trained and saved!");
    }
    return model;
}

async function plotForecast(filter = 'monthly') {
    const { dates, visitors } = await fetchDataFromDB(filter);

    if (dates.length === 0 || visitors.length === 0) {
        document.getElementById('mapeResult').innerText = "No data available.";
        return;
    }

    // Get only the last 6 months of historical data
    const historicalDates = dates.slice(-6);
    const historicalVisitors = visitors.slice(-6);
    const smoothedVisitors = movingAverage(historicalVisitors);

    const minVisitor = Math.min(...smoothedVisitors);
    const maxVisitor = Math.max(...smoothedVisitors);

    const normalize = value => (value - minVisitor) / (maxVisitor - minVisitor);
    const denormalize = value => value * (maxVisitor - minVisitor) + minVisitor;

    const normalizedVisitors = smoothedVisitors.map(normalize);

    // Prepare data for the model
    const xs = tf.tensor2d(normalizedVisitors.map((v, i) => [i, v]));
    const ys = tf.tensor1d(normalizedVisitors);

    // Train or load the model
    const model = await trainOrLoadModel(xs, ys);

    // Predict the next 6 months
    const lastIndex = normalizedVisitors.length;
    const futureIndices = Array.from({ length: 6 }, (_, i) => lastIndex + i);
    const futureXs = tf.tensor2d(futureIndices.map(i => [i, normalizedVisitors.slice(-1)[0]]));
    const predictions = model.predict(futureXs).dataSync().map(denormalize);

    // Calculate MAPE for historical predictions
    const trainPredictions = model.predict(xs).dataSync().map(denormalize);
    const mape = calculateMAPE(smoothedVisitors, trainPredictions);

    // Display MAPE result
    document.getElementById('mapeResult').innerText = `MAPE: ${mape.toFixed(2)}%`;

    // Generate future dates
    const futureDates = Array.from({ length: 6 }, (_, i) => {
        const d = new Date(historicalDates[historicalDates.length - 1]);
        filter === 'weekly' ? d.setDate(d.getDate() + 7 * (i + 1)) : d.setMonth(d.getMonth() + (i + 1));
        return d.toISOString().slice(0, 10);
    });

    // Combine historical and forecasted data
    const combinedDates = [...historicalDates, ...futureDates];
    const combinedData = [...smoothedVisitors, ...predictions];

    // Destroy the existing chart instance
    if (chartInstance) chartInstance.destroy();

    // Create the new chart
    const ctx = document.getElementById('forecastChart').getContext('2d');
    chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: combinedDates,
            datasets: [
                {
                    label: 'Historical Data',
                    data: [...smoothedVisitors, null, null, null, null, null],
                    borderColor: '#27ae60',
                    backgroundColor: 'rgba(39, 174, 96, 0.1)',
                    fill: true,
                    pointRadius: 3,
                    tension: 0.3
                },
                {
                    label: 'Forecasted Data',
                    data: [null, null, null, null, null, null, ...predictions],
                    borderColor: '#3498db',
                    borderDash: [5, 5],
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    fill: true,
                    pointRadius: 4,
                    tension: 0.3
                }
            ]
        },
        options: {
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: { beginAtZero: false }
            }
        }
    });
}



// Update chart when the filter changes
function updateChart() {
    const filter = document.getElementById('filter').value;
    plotForecast(filter);
}

document.addEventListener("DOMContentLoaded", () => plotForecast('monthly'));

</script>

<script>
$(document).ready(function () {
    const today = new Date().toISOString().split('T')[0];
    $('#chart-date, #header-date-picker').val(today); // Set both date pickers to today's date

    let genderChart;

    // Function to render the gender chart with specific ages in the tooltip
   function renderGenderChart(data, title, ageRange = null) {
    const maleCount = data.male || 0;
    const femaleCount = data.female || 0;
    const lgbtCount = data.lgbt || 0;

    const maleAges = Array.isArray(data.ages?.male) ? data.ages.male : [];
    const femaleAges = Array.isArray(data.ages?.female) ? data.ages.female : [];
    const lgbtAges = Array.isArray(data.ages?.lgbt) ? data.ages.lgbt : [];

    const ctx = document.getElementById('genderChart').getContext('2d');
    if (genderChart) genderChart.destroy();

    genderChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Male', 'Female', 'LGBTQ'],
            datasets: [{
                label: 'User Count',
                data: [maleCount, femaleCount, lgbtCount],
                backgroundColor: ['rgba(0, 123, 255, 0.6)', 'rgba(255, 192, 203, 0.6)', 'rgba(40, 167, 69, 0.6)'],
                borderColor: ['rgba(0, 123, 255, 1)', 'rgba(255, 192, 203, 1)', 'rgba(40, 167, 69, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#94a3b8', // Light grayish-blue for legend text
                        font: { size: 12 }
                    }
                },
                title: {
                    display: true,
                    text: title,
                    color: '#94a3b8', // Light grayish-blue for title
                    font: { size: 18, weight: 'bold' }
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            const gender = tooltipItem.label;
                            const count = tooltipItem.raw;

                            let ageList = "None";
                            switch (gender) {
                                case "Male":
                                    ageList = maleAges.length > 0 ? `Ages: ${maleAges.join(", ")}` : "None";
                                    break;
                                case "Female":
                                    ageList = femaleAges.length > 0 ? `Ages: ${femaleAges.join(", ")}` : "None";
                                    break;
                                case "LGBTQ":
                                    ageList = lgbtAges.length > 0 ? `Ages: ${lgbtAges.join(", ")}` : "None";
                                    break;
                            }

                            const rangeText = ageRange ? ` (Age Range: ${ageRange})` : "";
                            return `${gender}: ${count}${rangeText}\n${ageList}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'User Count',
                        color: '#94a3b8', // Light grayish-blue for Y-axis title
                        font: { size: 14, weight: 'bold' }
                    },
                    ticks: {
                        color: '#94a3b8', // Light grayish-blue for Y-axis ticks
                        font: { size: 12 }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)' // Subtle grid lines
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Gender',
                        color: '#94a3b8', // Light grayish-blue for X-axis title
                        font: { size: 14, weight: 'bold' }
                    },
                    ticks: {
                        color: '#94a3b8', // Light grayish-blue for X-axis ticks
                        font: { size: 12 }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)' // Subtle grid lines
                    }
                }
            }
        }
    });
}

    // Function to fetch daily gender data
    function fetchDailyGenderData(date) {
        $.ajax({
            url: 'fetch_gender_data.php',
            type: 'GET',
            data: { date: date },
            dataType: 'json',
            success: function (data) {
                console.log("Fetched Data:", data); // Log the fetched data
                renderGenderChart(data, `Daily User Distribution by Gender (${date})`);
            },
            error: function () {
                console.error('Error fetching daily gender data.');
            }
        });
    }

    // Function to fetch gender data with age range filtering
    function fetchGenderDataWithAgeRange(date, ageRange) {
        $.ajax({
            url: 'fetch_gender_data.php',
            type: 'GET',
            data: { date: date, age_range: ageRange },
            dataType: 'json',
            success: function (data) {
                console.log("Fetched Data with Age Range:", data); // Log the fetched data
                renderGenderChart(data, `User Distribution by Gender (Age Range: ${ageRange})`, ageRange);
            },
            error: function () {
                console.error('Error fetching gender data with age range.');
            }
        });
    }

    $('#fetch-chart-data').on('click', function () {
        const selectedDate = $('#header-date-picker').val();
        const selectedAgeRange = $('#age-range').val();
        if (selectedDate && selectedAgeRange) {
            fetchGenderDataWithAgeRange(selectedDate, selectedAgeRange);
        } else {
            alert("Please select both a date in the dashboard date picker and an age range.");
        }
    });

    $('#fetch-daily-data').on('click', function () {
        const selectedDate = $('#header-date-picker').val();
        if (selectedDate) fetchDailyGenderData(selectedDate);
    });

    $('#reset-daily-data').on('click', function () {
        const selectedDate = $('#header-date-picker').val();
        $('#age-range').val('0-10');
        fetchDailyGenderData(selectedDate);
    });

    $('#header-date-picker').on('change', function () {
        const selectedDate = $(this).val();
        $('#chart-date').val(selectedDate);
        fetchDailyGenderData(selectedDate);
    });

    fetchDailyGenderData(today);
});

</script>
<script>
// Function to fetch total visitors for a given date
function fetchTotalVisitors(date) {
    fetch(`fetch_visitors.php?date=${date}`) // Fetch from your PHP script
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            // Update visitor count
            document.getElementById('visitor-count').textContent = data.total_visitors !== undefined ? data.total_visitors : 0;
        })
        .catch(error => {
            console.error('Error fetching total visitors:', error);
            document.getElementById('visitor-count').textContent = 0; // Reset count on error
        });
}

// Call the function when the page loads to set today's date
window.onload = function() {
    const today = new Date(); // Get today's date
    const formattedDate = today.toISOString().split('T')[0]; // Format: YYYY-MM-DD

    // Set the search date input's value to today's date
    document.getElementById('search-date').value = formattedDate; 

    // Fetch total visitors for today's date
    fetchTotalVisitors(formattedDate);
};

// Event listener for button click to fetch data for the selected date


</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const filterButton = document.getElementById("filter-chart");
    const dateInput = document.getElementById("header-date-picker"); // Use header date picker
    const studentRetireeChart = document.getElementById("studentRetireeChart").getContext('2d');

    let chartInstance; // Holds the chart instance

    // Function to get today's date in YYYY-MM-DD format
    function getTodayDate() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0'); // Add leading zero
        const day = String(today.getDate()).padStart(2, '0'); // Add leading zero
        return `${year}-${month}-${day}`;
    }

    // Function to fetch data for a specific date and update the chart
    function fetchAndUpdateChart(filterDate = '') {
        // Make the request to backend with the selected date
        fetch(`fetch_registered_user.php?date=${filterDate}`)
            .then(response => response.json())
            .then(data => {
                // Prepare chart data
                const chartData = {
                    labels: ["Students", "Retirees"],
                    datasets: [{
                        label: 'Count',
                        data: [data.students, data.retirees],
                        backgroundColor: ['#36a2eb', '#ff6384'],
                    }]
                };

                // Destroy previous chart instance if exists
                if (chartInstance) {
                    chartInstance.destroy();
                }

                // Create a new chart instance
                chartInstance = new Chart(studentRetireeChart, {
                    type: 'pie', // Using a pie chart here
                    data: chartData,
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: `Student and Retiree Distribution (Date: ${filterDate})`,
                            },
                        },
                    },
                });
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                alert("Failed to fetch data for the selected date.");
            });
    }

    // Set the input date to today's date and fetch data automatically
    const todayDate = getTodayDate();
    dateInput.value = todayDate; // Set date input value to today
    fetchAndUpdateChart(todayDate); // Fetch data for today's date

    // Event listener for the filter button
    filterButton.addEventListener("click", function () {
        const filterDate = dateInput.value;
        if (filterDate) {
            fetchAndUpdateChart(filterDate); // Fetch data for the selected date
        } else {
            alert("Please select a date to filter.");
        }
    });

    // Event listener for date input change (when the user changes the date)
    dateInput.addEventListener("change", function () {
        const selectedDate = dateInput.value;
        if (selectedDate) {
            fetchAndUpdateChart(selectedDate); // Fetch data for the new selected date
        } else {
            alert("Please select a valid date.");
        }
        
    });
});

</script>
<script>
    let trendchart = null;

    // Shorten to Month, Day, and Year (e.g., "Jan 1, 2024")
    function formatToShortMonthDayYear(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleString('default', { month: 'short', day: 'numeric', year: 'numeric' });
        // Example Output: "Jan 1, 2024"
    }

    // Enable or disable date filter based on trend selection
    function handleTrendChange() {
        const trend = document.getElementById('trendSelect').value;
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const filterBtn = document.getElementById('filterBtn');

        const isDaily = trend === 'daily';
        startDate.disabled = !isDaily;
        endDate.disabled = !isDaily;
        filterBtn.disabled = !isDaily;

        fetchTrends(); // Fetch trends dynamically
    }

    // Fetch totals for the cards
    function fetchTotals() {
        fetch('get_trends.php?trend=last_week_total')
            .then(response => response.json())
            .then(data => document.getElementById('lastWeekTotal').innerText = data.total_visits || 0);

        fetch('get_trends.php?trend=monthly_total')
            .then(response => response.json())
            .then(data => document.getElementById('monthlyTotal').innerText = data.total_visits || 0);

        fetch('get_trends.php?trend=last_day_total')
            .then(response => response.json())
            .then(data => document.getElementById('lastDayTotal').innerText = data.total_visits || 0);
    }

    // Fetch trends dynamically for the chart
    function fetchTrends() {
        const trend = document.getElementById('trendSelect').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        let url = `get_trends.php?trend=${trend}`;
        if (trend === 'daily' && startDate && endDate) {
            if (startDate > endDate) {
                document.getElementById('notification').innerText = "Start date cannot be after end date.";
                return;
            }
            url += `&start_date=${startDate}&end_date=${endDate}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const labels = data.map(row => formatToShortMonthDayYear(row.visit_date || row.visit_week || row.visit_month));
                const values = data.map(row => row.total_visits);
                updateChart(labels, values);
            })
            .catch(error => console.error("Error fetching trends:", error));
    }

    // Update Chart
    function updateChart(labels, values) {
    const ctx = document.getElementById('visitChart').getContext('2d');
    if (trendchart) trendchart.destroy();

   trendchart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Visits',
                data: values,
                borderColor: '#38bdf8', // Light blue line color
                backgroundColor: 'rgba(56, 189, 248, 0.2)', // Fill area color
                borderWidth: 2,
                pointBackgroundColor: '#ffffff', // White data points
                pointBorderColor: '#38bdf8',
                pointRadius: 5,
                tension: 0.4, // Smooth curve
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#94a3b8', // Light grayish-blue legend labels
                        font: {
                            size: 12
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Visits Over Time',
                    color: '#94a3b8', // Light grayish-blue chart title
                    font: {
                        size: 18,
                        weight: 'bold'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleFont: { size: 14, weight: 'bold', color: '#ffffff' },
                    bodyFont: { size: 12, color: '#ffffff' },
                    cornerRadius: 5
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date',
                        color: '#94a3b8', // X-axis title color
                        font: { size: 14, weight: 'bold' }
                    },
                    ticks: {
                        color: '#94a3b8', // X-axis labels
                        font: { size: 12 }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)' // Subtle grid lines
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Total Visits',
                        color: '#94a3b8', // Y-axis title color
                        font: { size: 14, weight: 'bold' }
                    },
                    ticks: {
                        color: '#94a3b8', // Y-axis labels
                        font: { size: 12 }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)' // Subtle grid lines
                    },
                    beginAtZero: true
                }
            }
        }
    });
}

    // Clear Chart when no data is available
    function clearChart() {
        if (trendchart) trendchart.destroy();
        trendchart = null;
    }

    // On page load
    window.onload = function () {
        fetchTotals();
        handleTrendChange();
    };
</script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById("peakHoursChart").getContext('2d');
            const peakHoursChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["8 AM", "9 AM", "10 AM", "11 AM", "12 PM", "1 PM", "2 PM", "3 PM", "4 PM", "5 PM", "6 PM"],
                    datasets: [{
                        label: "Visitors Count per Hour",
                        data: [15, 25, 30, 45, 60, 70, 55, 65, 85, 90, 75],
                        borderColor: '#38bdf8',
                        backgroundColor: 'rgba(56, 189, 248, 0.2)',
                        borderWidth: 3,
                        pointBackgroundColor: '#38bdf8',
                        pointBorderColor: '#fff',
                        pointRadius: 5,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Hour of the Day',
                                color: '#cbd5e1',
                                font: { size: 14 }
                            },
                            ticks: {
                                color: '#94a3b8'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Number of Visitors',
                                color: '#cbd5e1',
                                font: { size: 14 }
                            },
                            beginAtZero: true,
                            ticks: {
                                color: '#94a3b8'
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Peak Hours of Visitor Comparison',
                            color: '#e2e8f0',
                            font: { size: 18, weight: '600' }
                        },
                        legend: {
                            labels: {
                                color: '#e2e8f0'
                            }
                        }
                    }
                }
            });
        });
</script>
<script>
// Function to fetch total visitors for a given date
function fetchTotalVisitors(date) {
    fetch(`fetch_visitors.php?date=${date}`) // Fetch from your PHP script
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            // Update visitor count
            const visitorCountElement = document.getElementById('visitor-count');
            if (visitorCountElement) {
                visitorCountElement.textContent = data.total_visitors !== undefined ? data.total_visitors : 0;
            } else {
                console.warn('Element #visitor-count not found in the DOM.');
            }
        })
        .catch(error => {
            console.error('Error fetching total visitors:', error);
            document.getElementById('visitor-count').textContent = 0; // Reset count on error
        });
}

// Function to initialize the dashboard with today's date
function initializeDashboard() {
    const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD

    // Set the date picker's value to today's date
    const datePickers = ['#header-date-picker', '#search-date']; // Include all relevant date pickers
    datePickers.forEach(picker => {
        if (document.querySelector(picker)) {
            document.querySelector(picker).value = today;
        }
    });

    // Fetch total visitors for today's date
    fetchTotalVisitors(today);
}

// Document Ready Handler
$(document).ready(function() {
    initializeDashboard(); // Initialize dashboard on page load

    // Event listener for changes in the dashboard date picker
    $('#header-date-picker').on('change', function() {
        const selectedDate = $(this).val();
        fetchTotalVisitors(selectedDate); // Fetch visitors for the selected date
    });

    // Event listener for other date pickers (optional)
    $('#search-date').on('change', function() {
        const selectedDate = $(this).val();
        fetchTotalVisitors(selectedDate); // Fetch visitors for the selected date
    });
});

</script>
<script>
$(document).ready(function() {
    // Set the date input to today's date
    const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
    $('#borrow-date').val(today);

    // Function to fetch borrowed books
    function fetchBorrowedBooks(date) {
        $('#borrowed-count').text('Loading...'); // Show loading message

        $.ajax({
            url: 'fetch_borrowed_books.php',
            type: 'GET',
            data: { date: date }, // Pass the selected date
            dataType: 'json',
            success: function(response) {
                // Update the display based on the response
                if (response.success) {
                    $('#borrowed-count').text(response.total_borrowed); // Show total borrowed
                } else {
                    $('#borrowed-count').text('Error fetching data');
                }
            },
            error: function() {
                $('#borrowed-count').text('Error fetching data');
            }
        });
    }

    // Fetch borrowed books automatically for today's date
    fetchBorrowedBooks(today);

    // Event listener for button click
    $('#fetch-borrowed-data').on('click', function() {
        const selectedDate = $('#borrow-date').val();
        fetchBorrowedBooks(selectedDate); // Fetch data for the selected date
    });
});

</script>
<script>
      // Fetch Metrics Data
        fetch('book_inventory_report.php?type=metrics')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-books').textContent = data.totalBooks;
                document.getElementById('unique-authors').textContent = data.uniqueAuthors;
                document.getElementById('recent-date').textContent = data.mostRecentDate;
                document.getElementById('common-author').textContent = data.mostCommonAuthor;
            })
            .catch(error => console.error('Error fetching metrics:', error));

        // Fetch Books Data
        fetch('book_inventory_report.php?type=books')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('book-table-body');
                tableBody.innerHTML = ''; // Clear placeholder rows

                // Populate the table with book data
                data.forEach(book => {
                    const row = `
                        <tr>
                            <td>${book.BookId}</td>
                            <td>${book.Title}</td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            })
            .catch(error => console.error('Error fetching books:', error));
</script>
   <script>
     let chart; // Chart instance
let currentFilter = 'daily'; // Default filter is 'daily'

// Fetch categories dynamically from the server
function fetchCategories() {
    fetch('get_categories.php') // PHP file to return categories as JSON
        .then(response => response.json())
        .then(categories => {
            const categorySelect = document.getElementById('categoryFilter');
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.name; // Use category name as value
                option.textContent = category.name; // Display category name in the dropdown
                categorySelect.appendChild(option);
            });

            // Add event listener to category dropdown
            document.getElementById('categoryFilter').addEventListener('change', fetchDataWithCategory);
        })
        .catch(error => console.error('Error fetching categories:', error));
}

// Function to handle category dropdown changes
function fetchDataWithCategory() {
    const category = document.getElementById('categoryFilter').value; // Selected category
    fetchData(currentFilter, category); // Fetch data using the current filter and selected category
}

// Function to handle button clicks for date filters (daily, weekly, monthly)
function setFilter(filter) {
    currentFilter = filter; // Update the current filter
    document.querySelectorAll('button').forEach(btn => btn.classList.remove('active')); // Remove active class from all buttons
    document.querySelector(`[data-filter="${filter}"]`).classList.add('active'); // Add active class to selected button

    fetchDataWithCategory(); // Fetch data using the updated filter and selected category
}

// Fetch data with both filter and category parameters
function fetchData(filter, category = '') {
    fetch(`top_borrowed_books.php?filter=${filter}&category=${category}`)
        .then(response => response.json())
        .then(data => {
            const labels = [];
            const borrowedCounts = [];

            // Process the fetched data to populate chart labels and data
            data.forEach(item => {
                labels.push(`${item.Title} (${item.BorrowDate})`); // Book title and borrow date
                borrowedCounts.push(item.BorrowedCount); // Count of how many times the book was borrowed
            });

            renderChart(labels, borrowedCounts, filter); // Render the chart with the new data
        })
        .catch(error => console.error('Error fetching data:', error));
}

// Function to render the line chart
function renderChart(labels, borrowedCounts, filter) {
    const ctx = document.getElementById('lineChart').getContext('2d');
    if (chart) chart.destroy(); // Destroy the previous chart if it exists

    // Create a new chart with the updated data
    chart = new Chart(ctx, {
        type: 'line', // Line chart type
        data: {
            labels: labels, // Labels (book title and borrow date)
            datasets: [{
                label: `Top Borrowed Books (${filter})`, // Dynamic label based on the filter
                data: borrowedCounts, // Data for the chart (borrow count)
                borderColor: 'rgba(54, 162, 235, 1)', // Line color
                backgroundColor: 'rgba(54, 162, 235, 0.2)', // Background color for the chart area
                borderWidth: 2,
                tension: 0.4, // Smoothness of the line
                fill: true // Fill the area under the line
            }]
        },
        options: {
            responsive: true, // Make the chart responsive to screen size
            maintainAspectRatio: false, // Maintain aspect ratio for responsiveness
            scales: {
                x: { title: { display: true, text: 'Books (Borrow Date)' } }, // X-axis title
                y: { title: { display: true, text: 'Borrow Count' }, beginAtZero: true } // Y-axis title and start from 0
            }
        }
    });
}

// Initial data load: Fetch categories and data for the default 'daily' filter
fetchCategories();
fetchData('daily'); // Fetch data for the default "daily" filter

    </script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch('fetch_return_status.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error fetching data:", data.error);
                return;
            }

            // Update the counts in the cards
            document.getElementById('pendingCount').textContent = data.pending;
            document.getElementById('returnedCount').textContent = data.returned;
            document.getElementById('lateReturnCount').textContent = data.late_return;
            document.getElementById('notReturnedCount').textContent = data.not_returned;
        })
        .catch(error => console.error("Error fetching return status counts:", error));
});
</script>
<script>
 let occupationChart; // Declare chart variable globally

// Function to update the occupation pie chart based on the selected date
function updateOccupationChart(date) {
    fetch('fetch_registered_user.php?date=' + date)  // Pass selected date to the server-side script
        .then(response => response.json())
        .then(data => {
            // Check for any errors in the data
            if (data.error) {
                console.error("Error: " + data.error);
                alert("Error: " + data.error);  // Notify user of the error
                return;
            }

            // Ensure all keys are defined, defaulting to 0 if missing
            const chartData = {
                employed: data.employed || 0,
                unemployed: data.unemployed || 0,
                self_employed: data.self_employed || 0,
                student: data.student || 0
            };

            // If a chart already exists, destroy it before creating a new one
            if (occupationChart) {
                occupationChart.destroy();
            }

            // Create the pie chart with fetched data
            const ctx = document.getElementById('occupationChart').getContext('2d');
            occupationChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Employed', 'Unemployed', 'Self-Employed', 'Students'],
                    datasets: [{
                        data: [
                            chartData.employed,
                            chartData.unemployed,
                            chartData.self_employed,
                            chartData.student
                        ],
                        backgroundColor: ['#36A2EB', '#FF6384', '#FFCD56', '#4BC0C0'], // Custom colors
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const label = tooltipItem.label || '';
                                    const value = tooltipItem.raw || 0;
                                    return `${label}: ${value}`;
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            alert('An error occurred while fetching data.');
        });
}

// Set up initial chart on page load using today's date
const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
document.getElementById('header-date-picker').value = today; // Set default date in the picker
updateOccupationChart(today); // Initialize chart with today's date

// Event listener for date picker changes
document.getElementById('header-date-picker').addEventListener('change', function () {
    const selectedDate = this.value;
    updateOccupationChart(selectedDate); // Update chart with selected date
});


</script>

</body>
</html>
