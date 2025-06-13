@extends('template.master')

@section('content')
<main class="container py-1">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white rounded px-3 py-2 shadow-sm">
            <li class="breadcrumb-item">
                <a href="{{ route('welcome') }}">
                    Home
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Scheduled Backup
            </li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Scheduled Backup</h4>
        <a href="{{ route('schedule.create') }}" class="btn btn-primary">+ Add Schedule</a>
    </div>

    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-light">
            <tr>
                <th>DB Connection</th>
                <th>Type</th>
                <th>Day</th>
                <th>Hour</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($connections as $conn)
                @if($conn->schedule->isEmpty())
                    <tr>
                        <td>{{ $conn->db_name }} @ {{ $conn->host }}</td>
                        <td>
                            <img src="{{ $conn->database_type->icon }}" alt="{{ $conn->database_type->brand }}" width="20" height="20" class="me-1">
                            {{ $conn->database_type->brand }}
                        </td>
                        <td colspan="3" class="text-muted">No schedule set</td>
                    </tr>
                @else
                    @foreach ($conn->schedule as $schedule)
                        <tr>
                            <td>{{ $conn->db_name }} @ {{ $conn->host }}</td>
                            <td>
                                <img src="{{ $conn->database_type->icon }}" alt="{{ $conn->database_type->brand }}" width="20" height="20" class="me-1">
                                {{ $conn->database_type->brand }}
                            </td>
                            <td>{{ $schedule->day }}</td>
                            <td>{{ $schedule->hour }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $schedule->id }}">Edit</button>
                                <form action="{{ route('schedule.destroy', $schedule) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this schedule?')">Delete</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $schedule->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('schedule.update', $schedule->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Schedule</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <select name="database_connection_id" class="form-control mb-2" required>
                                                <option value="">-- Select Database --</option>
                                                @foreach ($connections as $c)
                                                    <option value="{{ $c->id }}"
                                                        @if ($schedule->database_connection_id == $c->id) selected @endif>
                                                        {{ $c->db_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <select name="day" class="form-control mb-2" required>
                                                <option value="">-- Select Day --</option>
                                                @php
                                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                                @endphp
                                                @foreach ($days as $day)
                                                    <option value="{{ $day }}" {{ $schedule->day === $day ? 'selected' : '' }}>
                                                        {{ $day }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="time" name="hour" value="{{ $schedule->hour }}"
                                                class="form-control mb-2" placeholder="Hour (e.g. 03:00)" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
</main>
@endsection
