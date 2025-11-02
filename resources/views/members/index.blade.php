@extends('layouts.app')

@section('title', 'Members - Library TPS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><i class="fas fa-users me-2"></i>Members Management</h2>
        <p class="text-muted">Manage library members</p>
    </div>
    <a href="{{ route('members.create') }}" class="btn btn-gradient">
        <i class="fas fa-user-plus me-2"></i>Register Member
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($members->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Member ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Active Borrows</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $member)
                        <tr>
                            <td><code>{{ $member->member_id }}</code></td>
                            <td>
                                <strong>{{ $member->name }}</strong>
                                <br><small class="text-muted">Member since {{ $member->membership_date->format('M Y') }}</small>
                            </td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->phone }}</td>
                            <td>
                                <span class="badge bg-{{ $member->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $member->activeBorrows->count() }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('members.destroy', $member) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $members->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Members Found</h4>
                <p class="text-muted">Start by registering your first member.</p>
                <a href="{{ route('members.create') }}" class="btn btn-gradient">
                    <i class="fas fa-user-plus me-2"></i>Register First Member
                </a>
            </div>
        @endif
    </div>
</div>
@endsection