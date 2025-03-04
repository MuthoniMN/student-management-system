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
            @foreach ($subjects as $subject)
                <tr>
                    <td>{{ $subject }}</td>
                    <td>{{ $results['exams']['Semester 1']['subjects'][$subject] }}</td>
                    <td>
                        @if ($results['exams']['Semester 1']['subjects'][$subject] > 80)
                            A
                        @elseif ($results['exams']['Semester 1']['subjects'][$subject] > 65)
                            B
                        @elseif ($results['exams']['Semester 1']['subjects'][$subject] > 50)
                            C
                        @elseif ($results['exams']['Semester 1']['subjects'][$subject] > 40)
                            D
                        @else
                            E
                        @endif
                    </td>
                    <td>{{ $results['exams']['Semester 2']['subjects'][$subject] }}</td>
                    <td>
                        @if ($results['exams']['Semester 2']['subjects'][$subject] > 80)
                            A
                        @elseif ($results['exams']['Semester 2']['subjects'][$subject] > 65)
                            B
                        @elseif ($results['exams']['Semester 2']['subjects'][$subject] > 50)
                            C
                        @elseif ($results['exams']['Semester 2']['subjects'][$subject] > 40)
                            D
                        @else
                            E
                        @endif
                    </td>
                    <td>{{ $results['subject_averages'][$subject] }}</td>
                    <td>
                        @if ($results['subject_averages'][$subject] > 80)
                            A
                        @elseif ($results['subject_averages'][$subject] > 65)
                            B
                        @elseif ($results['subject_averages'][$subject] > 50)
                            C
                        @elseif ($results['subject_averages'][$subject] > 40)
                            D
                        @else
                            E
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Totals</td>
                <td colspan="2">{{ $results['exams']['Semester 1']['total'] }}</td>
                <td colspan="2">{{ $results['exams']['Semester 2']['total'] }}</td>
                <td colspan="2">{{ $results['total'] }}</td>
            </tr>
            <tr>
                <td>Rank</td>
                <td colspan="2">{{ $ranks['Semester 1']['rank'] }}</td>
                <td colspan="2">{{ $ranks['Semester 2']['rank'] }}</td>
                <td colspan="2">{{ $results['rank'] }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
