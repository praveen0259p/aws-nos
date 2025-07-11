<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mobile PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            padding: 10px;
        }
        .container {
            width: 100%;
            word-wrap: break-word;
        }
        h1 {
            font-size: 18px;
            text-align: center;
        }
        p {
            font-size: 12px;
        }

        /* Responsive styles for mobile */
        @media screen and (max-width: 600px) {
            h1 {
                font-size: 16px;
            }
            p {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Submitted Data</h1>
        <p>This is a mobile-optimized PDF generated using PHP and DOMPDF.</p>
    </div>
</body>
</html>
