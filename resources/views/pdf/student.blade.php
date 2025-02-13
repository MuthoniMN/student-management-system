<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $name }} - Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .results {
            display: flex;
            justify-items: space-between;
            align-items: center;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $name }}'s Report</h1>
        <p>Student ID: {{ $studentId }}</p>
    </div>

    <div class="results">
        <p>Total Marks: {{ $total_marks }}</p>
        <p>Rank: {{ $position }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Marks</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subjects as $result)
                <tr>
                    <td>{{ $result['subject_name'] }}</td>
                    <td>{{ $result['average_marks'] }}</td>
                    <td>{{ $result['grade'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
