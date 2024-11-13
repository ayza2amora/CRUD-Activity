<div>
    <!-- resources/views/livewire/item-table.blade.php -->

    <div class="container mx-auto px-4 py-6">
    <!-- Center the heading -->
    <h2 class="text-2xl font-bold mb-4 text-center text-black">USeP Student Profiling</h2>

    <!-- Display success message with theme color -->
    @if (session()->has('message'))
        <div class="bg-red-800 text-green-100 px-4 py-2 rounded mb-4 text-center">
            {{ session('message') }}
        </div>
    @endif

    <!-- Search input and create button -->
    <div class="flex justify-between mb-4">
        <!-- Search input field -->
        <div class="flex space-x-2">
        <!-- Search input field -->
        <input type="text" id="searchInput" placeholder="Search by Name" 
               class="w-full max-w-xs px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-500">

        <!-- Search button positioned right next to the input -->
        <button onclick="performSearch()" class="bg-red-800 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
        <svg class="h-6 w-6 text-gray-100"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <circle cx="11" cy="11" r="8" />  <line x1="21" y1="21" x2="16.65" y2="16.65" /></svg>
        </button>
    </div>

        <!-- Create button positioned to the right -->
        <button wire:click="create()" class="bg-red-300 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <svg class="h-5 w-5 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </button>
    </div>

    <!-- Dark themed data table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white text-gray-200 border border-white rounded-lg shadow-md">
            <thead class="bg-red-800">
                <tr>
                    <th class="px-6 py-3 border-b border-gray-700 text-center font-semibold text-white">ID</th>
                    <th class="px-6 py-3 border-b border-gray-700 text-left font-semibold text-white">Name</th>
                    <th class="px-6 py-3 border-b border-gray-700 text-left font-semibold text-white">Campus</th>
                    <th class="px-6 py-3 border-b border-gray-700 text-left font-semibold text-white">College</th>
                    <th class="px-6 py-3 border-b border-gray-700 text-center font-semibold text-white">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($items as $item)
                    <tr class="hover:bg-red-200">
                        <td class="px-6 py-4 border-b border-gray-700 text-center text-black">{{ $item->id }}</td>
                        <td class="px-6 py-4 border-b border-gray-700 text-left text-black">{{ $item->name }}</td>
                        <td class="px-6 py-4 border-b border-gray-700 text-left text-black">{{ $item->organization }}</td>
                        <td class="px-6 py-4 border-b border-gray-700 text-left text-black">{{ $item->department }}</td>
                        <td class="px-6 py-4 border-b border-gray-700 text-center">
                            <button wire:click="edit({{ $item->id }})" class="bg-white hover:bg-blue-500 border-b border-gray-700 text-black py-1 px-3 rounded text-sm">Edit</button>
                            <button wire:click="delete({{ $item->id }})" class="bg-red-500 hover:bg-red-600 border-b border-gray-700 text-white py-1 px-3 rounded text-sm">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-black py-4">No results found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function performSearch() {
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');

        rows.forEach(row => {
            let found = false;
            const cells = row.querySelectorAll('td');

            cells.forEach(cell => {
                if (cell.textContent.toLowerCase().includes(searchValue)) {
                    found = true;
                }
            });

            row.style.display = found ? '' : 'none';
        });
    }
</script>


        <!-- Modal for Adding/Editing Item -->
        @if($isOpen)
            <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                <div class="bg-white text-black w-full max-w-lg p-6 rounded-lg shadow-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">{{ $item_id ? 'Edit Profile' : 'Profile Details' }}</h3>
                        <button wire:click="closeModal()" class="text-gray-400 hover:text-gray-100">✖</button>
                    </div>

                    <form>
                        <div class="mb-4">
                            <label for="name" class="block text-black">Name</label>
                            <input type="text" wire:model="name" id="name"
                                class="w-full px-4 py-2 mt-2 bg-white border border-gray-600 rounded-lg focus:outline-none focus:ring focus:ring-blue-500">
                            @error('name') <span class="text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="organization" class="block text-black">Organization</label>
                            <select wire:model="selectedOrganization" id="organization"
                                class="w-full px-4 py-2 mt-2 bg-white border border-gray-600 rounded-lg focus:outline-none focus:ring focus:ring-blue-500">
                                <option value="" disabled selected>Select Campus</option>
                                @foreach($organizations as $organization)
                                    <option value="{{ $organization['name'] }}">{{ $organization['name'] }}</option>
                                @endforeach
                            </select>
                            @error('selectedOrganization') <span class="text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="department" class="block text-black">College</label>
                            <select wire:model="selectedDepartment" id="department"
                                class="w-full px-4 py-2 mt-2 bg-white border border-gray-600 rounded-lg focus:outline-none focus:ring focus:ring-blue-500">
                                <option value="" disabled selected>Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department['name'] }}">{{ $department['name'] }}</option>
                                @endforeach
                            </select>
                            @error('selectedDepartment') <span class="text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="button" wire:click="closeModal()"
                                class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded mr-2">Cancel</button>

                            <!-- Add @click.prevent to prevent default form submission -->
                            <button type="button" wire:click.prevent="store()"
                                class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                                {{ $item_id ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

</div>
