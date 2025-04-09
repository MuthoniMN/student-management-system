<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $results['name'] }} - Report</title>
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
        <h1>{{ $results['name'] }}'s Report</h1>
        <p>Student ID: {{ $results['studentId'] }}</p>
    </div>

    <div class="results">
        <p>Total Marks: {{ current($results['results'])['total'] }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>CAT 1</th>
                <th>CAT 2</th>
                <th>Final Exam</th>
                <th>Average</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach (current($results['results'])['subjects'] as $result)
                <tr>
                    <td>{{ $result['subject_name'] }}</td>
                    <td>{{ $result['marks'][0] }}</td>
                    <td>{{ $result['marks'][1] }}</td>
                    <td>{{ $result['marks'][2] }}</td>
                    <td>{{ $result['average_marks'] }}</td>
                    <td>{{ $result['grade'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
