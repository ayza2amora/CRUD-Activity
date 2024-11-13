<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;

class ItemTable extends Component
{
    public $search = ''; // Property for search input
    public $isOpen = false;
    public $item_id, $name, $selectedOrganization, $selectedDepartment;
    // Hardcoded lists for organizations and departments
    public $organizations = [
        ['id' => 1, 'name' => 'Obrero Campus'],
        ['id' => 2, 'name' => 'Mintal Campus'],
        ['id' => 3, 'name' => 'Tagum-Mabini Campus'],
        ['id' => 3, 'name' => 'Malabog Extension Campus'],
    ];

    public $departments = [
        ['id' => 1, 'name' => 'CED - College of Education'],
        ['id' => 2, 'name' => 'CIC - College of Information and Computing '],
        ['id' => 3, 'name' => 'COE - College of Engineering'],
        ['id'=> 4, 'name'=> 'CBA - College of Business Administration'],
        ['id'=> 5, 'name'=> 'CAS - College of Arts and Sciences'],
        ['id'=> 6, 'name'=> 'CT - College of Technology'],
        ['id'=> 7, 'name'=> 'CAEC - College of Agriculture and Economics'],
        ['id'=> 8, 'name'=> 'SOL - School of Law'],
    ];

    public function render()
    {
        // Fetch items filtered by search term
        $items = Item::when($this->search, function($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })->get();

        return view('livewire.item-table', ['items' => $items]);
    }


    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->selectedOrganization = "";
        $this->selectedDepartment = "";
        $this->item_id = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string',
            'selectedOrganization' => 'required|string',
            'selectedDepartment' => 'required|string',
        ]);

        Item::updateOrCreate(['id' => $this->item_id], [
            'name' => $this->name,
            'organization' => $this->selectedOrganization,
            'department' => $this->selectedDepartment,
        ]);

        session()->flash('message', $this->item_id ? 'Item Updated Successfully.' : 'Item Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
{
    $item = Item::findOrFail($id);
    $this->item_id = $id;
    $this->name = $item->name;
    $this->selectedOrganization = $item->organization;
    $this->selectedDepartment = $item->department; 

    $this->openModal();
}
    public function delete($id)
    {
        Item::find($id)->delete();
        session()->flash('message', 'Item Deleted Successfully.');
    }
}
