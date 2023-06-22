<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voltage = $_POST['voltage'];
    $current = $_POST['current'];
    $currentRate = $_POST['currentRate'];

    function calculateElectricityRates($voltage, $current, $currentRate)
    {
        $hourlyAndDailyRates = [];

        for ($hour = 1; $hour <= 24; $hour++) {
            $power = $voltage * $current;
            $energy = $power * $hour;
            $totalCharge = $energy * ($currentRate / 100);

            $hourlyRate = $totalCharge * $hour;

            $hourlyAndDailyRates[] = [
                'hour' => $hour,
                'energy' => $energy,
                'totalCharge' => $totalCharge,
                'hourlyRate' => $hourlyRate,
            ];
        }

        return $hourlyAndDailyRates;
    }

    $hourlyAndDailyRates = calculateElectricityRates($voltage, $current, $currentRate);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Electricity Rates Calculator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .container {
            background-color: var(--light-bg);
            width: fit-content;
            display: block;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .output_hourly,
        .daily_rate {
            background-color: var(--light-bg);
            display: none;
            width: fit-content;
            display: block;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .table-wrapper {
            width: fit-content;
            max-height: 300px;
            overflow-y: auto;
            display: flex;
            justify-content: space-between;
        }

        .header {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .col-md-6{
            display: none;
        }
    </style>
</head>

<body>
    <h1 class="header">Electricity Rate Calculator</h1>
    <div class="container">
        <form method="POST" id="calculatorForm">
            <label for="voltage">Please Input Voltage</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="voltage_value"> Sample: 19</span>
                </div>
                <input type="number" class="form-control" name="voltage" placeholder="Enter voltage value" id="voltage" aria-describedby="voltage_value" step="any" required>
            </div>
            <label for="voltage">Please Input Current</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="current_value"> Sample: 3.54</span>
                </div>
                <input type="number" class="form-control" id="current" name="current" step="any" placeholder="Enter Current Value" aria-describedby="current_value" required>
            </div>
            <label for="currentRate">Please Input Current Rate in cents</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="currentRate_value"> Sample: 21.80 cents</span>
                </div>
                <input type="number" class="form-control" id="currentRate" name="currentRate" step="any" placeholder="Enter Current Rate" aria-describedby="currentRate_value" required>
            </div>
            <button type="submit" class="btn btn-success">Calculate</button>
            <button type="reset" class="btn btn-danger">Reset</button>
        </form>
    </div>
    <?php if (isset($hourlyAndDailyRates)) : ?>
    <div class="container">
        <h2>Total Power and Rate</h2>
        <?php
        $firstHourlyRate = $hourlyAndDailyRates[0];
        $firstPower = $firstHourlyRate['energy'];
        $rateFromUser = $_POST['currentRate'];
        ?>
        <p>Power: <?php echo ($firstPower / 1000); ?> kWh</p>
        <p> Rate: RM <?php echo number_format(($rateFromUser/ 100), 4); ?></p>
    </div>
<?php endif; ?>

    <div class="row">
        <div class="col-md-6"<?php if (isset($hourlyAndDailyRates)) echo 'style="display: block;"'; ?>>
            <div class="output_hourly" <?php if (isset($hourlyAndDailyRates)) echo 'style=" margin-left:300px;"'; ?>>
                <h2 class="header"  >Hourly Rate</h2>
                <?php if (isset($hourlyAndDailyRates)) : ?>
                    <div class="table-wrapper">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>Hour</th>
                                    <th>Energy (kWh)</th>
                                    <th>Total Charges</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($hourlyAndDailyRates as $index => $rate) : ?>
                                    <?php
                                    $hour = $index + 1;
                                    $energy = $rate['energy'] / 1000;
                                    $totalCharge = 'RM ' . number_format(($rate['totalCharge'] * 100), 2);
                                    ?>
                                    <tr>
                                        <td><?php echo $hour; ?></td>
                                        <td><?php echo $hour; ?></td>
                                        <td><?php echo $energy; ?></td>
                                        <td><?php echo $totalCharge; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6" <?php if (isset($hourlyAndDailyRates)) echo 'style="display: block;"'; ?>>
            <div class="daily_rate">
                <h2 class="header" >Daily Rate</h2>
                <?php if (isset($hourlyAndDailyRates)) : ?>
                    <div class="table-wrapper">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>Day</th>
                                    <th>Total Energy (kWh)</th>
                                    <th>Total Charges</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($day = 1; $day <= 30; $day++) : ?>
                                    <?php
                                    $power = $voltage * $current;
                                    $energy = $power * 24 * $day;
                                    $totalCharge = $energy * ($currentRate / 100);
                                    $formattedTotalCharge = 'RM ' . number_format(($totalCharge / 100), 2);
                                    ?>
                                    <tr>
                                        <td><?php echo $day; ?></td>
                                        <td><?php echo $day; ?></td>
                                        <td><?php echo $energy / 1000; ?></td>
                                        <td><?php echo $formattedTotalCharge; ?></td>
                                    </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>