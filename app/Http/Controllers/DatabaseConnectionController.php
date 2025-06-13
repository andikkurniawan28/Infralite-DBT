<?php

namespace App\Http\Controllers;

use App\Models\DatabaseType;
use Illuminate\Http\Request;
use App\Models\DatabaseConnection;

class DatabaseConnectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $databaseTypes = DatabaseType::all();
        $connections = DatabaseConnection::with('database_type')->get();
        return view('database_connection.index', compact('databaseTypes', 'connections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'database_type_id' => 'required|exists:database_types,id',
            'description'      => 'nullable|string',
            'host'             => 'required|string',
            'port'             => 'required|string',
            'username'         => 'required|string',
            'password'         => 'required|string',
            'db_name'          => 'required|string',
            'charset'          => 'nullable|string',
            'collation'        => 'nullable|string',
            'schema'           => 'nullable|string',
        ]);

        DatabaseConnection::create($request->all());

        return redirect()->back()->with('success', 'Database connection added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DatabaseConnection $databaseConnection)
    {
        $request->validate([
            'database_type_id' => 'required|exists:database_types,id',
            'description'      => 'nullable|string',
            'host'             => 'required|string',
            'port'             => 'required|string',
            'username'         => 'required|string',
            'password'         => 'required|string',
            'db_name'          => 'required|string',
            'charset'          => 'nullable|string',
            'collation'        => 'nullable|string',
            'schema'           => 'nullable|string',
        ]);

        $databaseConnection->update($request->all());

        return redirect()->back()->with('success', 'Database connection updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DatabaseConnection $databaseConnection)
    {
        $databaseConnection->delete();
        return redirect()->back()->with('success', 'Database connection deleted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DatabaseConnection $databaseConnection)
    {
        // Memuat relasi database_type untuk mendapatkan icon, brand, dll
        $databaseConnection->load('database_type');

        return view('database_connection.show', [
            'connection' => $databaseConnection,
        ]);
    }

}
