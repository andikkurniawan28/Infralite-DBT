<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\DatabaseConnection;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // Tampilkan daftar schedule
    public function index()
    {
        $connections = DatabaseConnection::all();
        $schedules = Schedule::with('database_connection')->latest()->get();
        return view('schedule.index', compact('schedules', 'connections'));
    }

    // Tampilkan form tambah
    public function create()
    {
        $connections = DatabaseConnection::all();
        return view('schedule.create', compact('connections'));
    }

    // Simpan schedule baru
    public function store(Request $request)
    {
        $request->validate([
            'database_connection_id' => 'required|exists:database_connections,id',
            'schedules' => 'required|array',
            'schedules.*.day' => 'required|string',
            'schedules.*.hour' => 'required|string',
        ]);

        foreach ($request->schedules as $schedule) {
            Schedule::create([
                'database_connection_id' => $request->database_connection_id,
                'day' => $schedule['day'],
                'hour' => $schedule['hour'],
            ]);
        }

        return redirect()->route('schedule.index')->with('success', 'Schedules successfully created.');
    }


    // Tampilkan form edit
    public function edit(Schedule $schedule)
    {
        $connections = DatabaseConnection::all();
        return view('schedule.edit', compact('schedule', 'connections'));
    }

    // Update schedule
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'database_connection_id' => 'required|exists:database_connections,id',
            'day' => 'required|string',
            'hour' => 'required|string',
        ]);

        $schedule->update($request->all());

        return redirect()->route('schedule.index')->with('success', 'Schedule successfully updated.');
    }

    // Hapus schedule
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedule.index')->with('success', 'Schedule successfully deleted.');
    }
}
