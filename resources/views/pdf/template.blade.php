<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $semester && $semester->title || $year && $year->year }} {{ $grade->name }} - Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    @if ($semester)
        <h1 class="title">{{ $year && $year->year }} {{ $grade->name }} - Results</h1>
    @else
    <h1 class="title">{{ $year && $year->year }} {{ $grade->name }} - Results</h1>
    @endif
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Marks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results as $key => $student)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $student['id'] }}</td>
                    <td>{{ $student['name'] }}</td>
                    <td>{{ $student['total'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
