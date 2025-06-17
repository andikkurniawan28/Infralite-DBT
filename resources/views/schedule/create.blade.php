@extends('template.master')

@section('content')
<main class="container py-1">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white rounded px-3 py-2 shadow-sm">
            <li class="breadcrumb-item">
                    <a href="{{ route('welcome') }}">
                        <i class="bi bi-house-door"></i>
                    </a>
                </li>
            <li class="breadcrumb-item"><a href="{{ route('schedule.index') }}">Scheduled Backup</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create Schedule</li>
        </ol>
    </nav>

    <h4 class="mb-3">Add Schedule</h4>

    <form action="{{ route('schedule.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Database Connection</label>
            <select name="database_connection_id" class="form-control" required>
                <option value="">-- Select Database --</option>
                @foreach ($connections as $conn)
                    <option value="{{ $conn->id }}">
                        {{ $conn->db_name }}
                        @php echo '@';@endphp
                        {{ $conn->host }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="schedule-rows">
            <div class="schedule-row border rounded p-3 mb-3">
                <label class="form-label">Select Day and Hour</label>
                <div class="row">
                    @php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    @endphp
                    @foreach ($days as $day)
                        <div class="col-6 col-md-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="schedules[0][day]"
                                    value="{{ $day }}" id="day-0-{{ $day }}" required>
                                <label class="form-check-label" for="day-0-{{ $day }}">
                                    {{ $day }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-2">
                    <input type="time" name="schedules[0][hour]" class="form-control" required>
                </div>
            </div>
        </div>

        <button type="button" id="add-row" class="btn btn-outline-primary mb-3">+ Add Schedule Row</button>

        <div class="text-end">
            <a href="{{ route('schedule.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Schedules</button>
        </div>
    </form>
</main>
@endsection

@section('script')
<script>
    let index = 1;

    document.getElementById('add-row').addEventListener('click', function () {
        const container = document.getElementById('schedule-rows');

        const row = document.createElement('div');
        row.classList.add('schedule-row', 'border', 'rounded', 'p-3', 'mb-3');

        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        let dayRadios = '';

        days.forEach(day => {
            const id = `day-${index}-${day}`;
            dayRadios += `
                <div class="col-6 col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="schedules[${index}][day]" value="${day}" id="${id}" required>
                        <label class="form-check-label" for="${id}">${day}</label>
                    </div>
                </div>
            `;
        });

        row.innerHTML = `
            <label class="form-label">Select Day and Hour</label>
            <div class="row">
                ${dayRadios}
            </div>
            <div class="mt-2">
                <input type="time" name="schedules[${index}][hour]" class="form-control" required>
            </div>
        `;

        container.appendChild(row);
        index++;
    });
</script>
@endsection
