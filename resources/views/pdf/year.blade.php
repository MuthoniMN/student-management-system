<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $student->name }} - Report {{ $year->year }}</title>
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
        <h1>{{ $student->name }}'s Report for {{ $year->year }}</h1>
        <p>Student ID: {{ $student->studentId }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th></th>
                <th colspan="2">Semester 1</th>
                <th colspan="2">Semester 2</th>
                <th colspan="2">Average</th>
            </tr>
            <tr>
                <th>Subject</th>
                <th>Marks</th>
                <th>Grade</th>
                <th>Marks</th>
                <th>Grade</th>
                <th>Marks</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results as $result)
                <tr>
                    <td>{{ $result['subject'] }}</td>
                    <td>{{ $result['Semester 1'] }}</td>
                    <td>{{ $result['Semester 1_grade'] }}</td>
                    <td>{{ $result['Semester 2'] }}</td>
                    <td>{{ $result['Semester 2_grade'] }}</td>
                    <td>{{ $result['average'] }}</td>
                    <td>{{ $result['grade'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Totals</td>
                <td colspan="2">{{ $totals['Semester 1'] }}</td>
                <td colspan="2">{{ $totals['Semester 2'] }}</td>
                <td colspan="2">{{ $totals['average'] }}</td>
            </tr>
            <tr>
                <td>Rank</td>
                <td colspan="2">{{ $ranks['Semester 1'] }}</td>
                <td colspan="2">{{ $ranks['Semester 2'] }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
