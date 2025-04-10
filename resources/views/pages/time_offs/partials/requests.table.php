<div class="table-responsive">
    <table id="timeOffTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee</th>
                <th>Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Days</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($timeOffs as $timeOff)
            <tr>
                <td>{{ $timeOff->id }}</td>
                <td>{{ $timeOff->user->name }}</td>
                <td>
                    @if($timeOff->type == 'annual_leave')
                        <span class="badge badge-success">Annual Leave</span>
                    @elseif($timeOff->type == 'sick_leave')
                        <span class="badge badge-danger">Sick Leave</span>
                    @elseif($timeOff->type == 'unpaid_leave')
                        <span class="badge badge-warning">Unpaid Leave</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($timeOff->start_date)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($timeOff->end_date)->format('d M Y') }}</td>
                <td>{{ $timeOff->days }}</td>
                <td>
                    @if($timeOff->status == 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @elseif($timeOff->status == 'approved')
                        <span class="badge badge-success">Approved</span>
                    @elseif($timeOff->status == 'rejected')
                        <span class="badge badge-danger">Rejected</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('time_offs.show', $timeOff->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($timeOff->status == 'pending')
                        <a href="{{ route('time_offs.edit', $timeOff->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('time_offs.approve', $timeOff->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to approve this request?')">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <form action="{{ route('time_offs.reject', $timeOff->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to reject this request?')">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No time off requests found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
