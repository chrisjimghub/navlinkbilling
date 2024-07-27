<?php

namespace App\Http\Controllers\Admin\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Backpack\Settings\app\Models\Setting;

trait Raisepon2Api
{
    private $jwtToken = null;

    private function baseUrl()
    {
        return Setting::get('raisepon_url') ?? null;
    }

    public function authenticate()
    {
        $response = Http::post($this->baseUrl() . 'api_login.php', [
            'username' => Setting::get('raisepon_username'),
            'password' => Setting::get('raisepon_password'),
        ]);

        $data = $response->json();

        if (isset($data['jwt'])) {
            $this->jwtToken = $data['jwt'];
            return response()->json($data);
        }

        return response()->json(['message' => 'Authentication failed'], 401);
    }

    public function testConnection($baseUrl, $username, $password)
    {
        $response = Http::post($baseUrl . 'api_login.php', [
            'username' => $username,
            'password' => $password,
        ]);

        $data = $response->json();

        if (isset($data['jwt'])) {
            return true;
        }

        return '401: Authentication failed';
    }

    public function listServices()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->jwtToken,
        ])->post($this->baseUrl() . 'get_services.php');

        $data = $response->json();

        return response()->json($data);
    }

    public function listCustomers()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->jwtToken,
        ])->post($this->baseUrl() . 'get_customers.php');

        $data = $response->json();

        return response()->json($data);
    }

    public function getCustomerBySn(Request $request)
    {
        $sn = $request->input('sn');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->jwtToken,
        ])->post($this->baseUrl() . 'get_customers_one.php', [
            'sn' => $sn,
            'jwt' => $this->jwtToken,
        ]);

        $data = $response->json();

        return response()->json($data);
    }

    public function createCustomer(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->jwtToken,
        ])->post($this->baseUrl() . 'create_customer.php', $request->all());

        $data = $response->json();

        return response()->json($data);
    }

    public function updateCustomer(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->jwtToken,
        ])->post($this->baseUrl() . 'update_customer.php', $request->all());

        $data = $response->json();

        return response()->json($data);
    }

    public function deleteCustomer(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->jwtToken,
        ])->post($this->baseUrl() . 'delete_customer.php', [
            'sn' => $request->input('sn'),
            'jwt' => $this->jwtToken,
        ]);

        $data = $response->json();

        return response()->json($data);
    }
}
