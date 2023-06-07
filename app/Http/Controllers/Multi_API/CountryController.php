<?php

namespace App\Http\Controllers\Multi_API;

use App\Http\Controllers\Controller;
use Goutte\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CountryController extends Controller
{
    public function getCountryFactory(Request $request, $limit)
    {
        try{
            $client = new Client();
            $url = 'https://www.iban.com/country-codes';
            $crawler = $client->request('GET', $url);

            $table = $crawler->filter('.tablesorter');
            $tbody = $table->filter('tbody');
            
            $clean = [];
            $tbody->filter('tr')->each(function ($item) {
                $name = $item->filter('td')
                    ->first()->text();
                $code = $item->filter('td:nth-child(2)')
                    ->first()->text();

                $this->results[] = [
                    "country_name" => $name,
                    "country_code" => $code
                ];
            });

            $data = $this->results;
            $collection = collect($data);
            $perPage = $limit;
            $page = request()->input('page', 1);
            $paginator = new LengthAwarePaginator(
                $collection->forPage($page, $perPage),
                $collection->count(),
                $perPage,
                $page,
                ['path' => url()->current()]
            );
            $clean = $paginator->appends(request()->except('page'));

            return response()->json([
                'status' => 'success',
                'message' => 'data fetched',
                'data' => $clean
            ], Response::HTTP_OK);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
