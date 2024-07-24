<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerImport implements 
    ToModel, 
    WithValidation, 
    WithHeadingRow
{

    public function model(array $row)
    {
        return new Customer($row);
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'date_of_birth' => 'nullable|date',
            'contact_number' => 'required',
        ];
    }

    
}
