<!DOCTYPE html>
<html>
<head>
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #218838;
            color: white;
        }
        h1 {
            text-align: center;
            font-weight: bold;
        }

        dl {
            font-size: 14px;
            margin: 0;
            padding-bottom: 20px;
        }

        dt, dd {
            display: inline-block;
            margin: 0;
        }

        dt {
            font-weight: bold;
        }

        dt::after {
            content: ":";
        }

        dd {
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <h1>Hasil Ujian</h1>
    <dl>
        <dt>No Peserta</dt>
        <dd>{{ $user->no_peserta }}</dd><br>

        <dt>NIS</dt>
        <dd>{{ $user->nis }}</dd><br>

        <dt>Nama</dt>
        <dd>{{ $user->name }}</dd><br>

        <dt>Kelas</dt>
        <dd>{{ $user->kelas['class'] ?? '' }}</dd><br>

        <dt>Semester</dt>
        <dd>{{ $user->kelas['semester'] ?? '' }}</dd><br>
    </dl>
    <table id="customers">
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>KKM</th>
                <th>Benar</th>
                <th>Salah</th>
                <th>Nilai</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attempts as $attempt)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $attempt['mapel'] }}</td>
                    <td>{{ $attempt['pass_marks'] }}</td>

                    <td class="total-correct-answers" data-attempt-id="{{ $attempt['id'] }}">{{ $attempt['correct'] }}</td>

                    <td class="total-incorrect-answers" data-attempt-id="{{ $attempt['id'] }}">{{ $attempt['incorrect'] }}</td>

                    <td class="total-score" data-attempt-id="{{ $attempt['id'] }}">{{ number_format($attempt['score'], 1) }}</td>

                    <td>{{ $attempt['status'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
