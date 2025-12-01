<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Kode Koper</th>
            <th>Tour Leader</th>
            <th>Waktu Scan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($scans as $scan)
        <tr>
            <td>{{ $scan->id }}</td>
            <td>{{ $scan->koper_code }}</td>
            <td>{{ $scan->user->name }}</td>
            <td>{{ $scan->scanned_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
