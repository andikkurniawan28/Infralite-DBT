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
                    User
                </li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Users</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">+ Add User</button>
        </div>

        <table class="table table-bordered bg-white shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->is_active ? 'Yes' : 'No' }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $user->id }}">Edit</button>
                            <form action="{{ route('user.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this user?')">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('user.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="name" value="{{ $user->name }}"
                                            class="form-control mb-2" placeholder="Name" required>
                                        <input type="email" name="email" value="{{ $user->email }}"
                                            class="form-control mb-2" placeholder="Email" required>
                                        <input type="password" name="password" class="form-control mb-2"
                                            placeholder="New Password (leave blank to keep current)">
                                        <select name="is_active" class="form-control">
                                            <option value="0"
                                                @if ($user->is_active == '0') {{ 'selected' }} @endif>Inactive
                                            </option>
                                            <option value="1"
                                                @if ($user->is_active == '1') {{ 'selected' }} @endif>Active
                                            </option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>

        <!-- Create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
                            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                            <input type="password" name="password" class="form-control mb-2" placeholder="Password"
                                required>
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input">
                                <label class="form-check-label">Active</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
