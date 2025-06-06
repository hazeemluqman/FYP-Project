@extends('layout')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
    <!-- Header Section -->
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-users text-blue-500 mr-2"></i>
            All User Accounts
        </h2>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        No.
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Role
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Phone
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Created At
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $loop->iteration }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $user->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <span
                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $user->role ?? '-' }}
                            </span>
                            <button onclick="openEditModal('{{ $user->id }}', '{{ $user->role }}')"
                                class="text-gray-400 hover:text-blue-500 transition-colors duration-200">
                                <i class="fas fa-pencil-alt text-xs"></i>
                            </button>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->phone ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at->format('M j, Y h:i A') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Role Modal -->
<div id="editRoleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="display: none;">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Edit User Role</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editRoleForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="editRoleSelect" class="block text-sm font-medium text-gray-700 mb-2">Select Role</label>
                <select id="editRoleSelect" name="role"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="manager">Manager</option>
                    <option value="worker">Worker</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit" id="saveChangesBtn"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Save Changes
                </button>
            </div>
        </form>
        <script>
        // Disable editing if role is admin
        document.addEventListener('DOMContentLoaded', function() {
            window.openEditModal = function(userId, currentRole) {
                const modal = document.getElementById('editRoleModal');
                const form = document.getElementById('editRoleForm');
                const select = document.getElementById('editRoleSelect');
                const saveBtn = document.getElementById('saveChangesBtn');

                form.action = `/users/${userId}/role`;
                select.value = currentRole;

                if (currentRole === 'admin') {
                    select.disabled = true;
                    saveBtn.disabled = true;
                    saveBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    select.disabled = false;
                    saveBtn.disabled = false;
                    saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                modal.style.display = 'flex';
            }
        });
        </script>
    </div>
</div>
<script>
// Hide edit icon if role is admin
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('tbody tr').forEach(function(row) {
        const roleCell = row.querySelector('td:nth-child(4) span');
        const editBtn = row.querySelector('td:nth-child(4) button');
        if (roleCell && editBtn && roleCell.textContent.trim() === 'admin') {
            editBtn.style.display = 'none';
        }
    });
});
</script>

<!-- Include Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<script>
function openEditModal(userId, currentRole) {
    const modal = document.getElementById('editRoleModal');
    const form = document.getElementById('editRoleForm');
    const select = document.getElementById('editRoleSelect');

    // Set form action
    form.action = `/users/${userId}/role`;

    // Set current role
    select.value = currentRole;

    // Show modal
    modal.style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editRoleModal').style.display = 'none';
}
</script>
@endsection