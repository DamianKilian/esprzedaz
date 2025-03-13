<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = $request->page ?? 1;
        $petsPerPage = 10;
        $response = Http::get('https://petstore.swagger.io/v2/pet/findByStatus', [
            'status' => 'available',
        ]);
        $pets = $response->collect();
        $lengthAwarePaginator = new LengthAwarePaginator(
            $pets->forPage($page, $petsPerPage),
            $pets->count(),
            $petsPerPage,
            $page,
            ['path' => route('pets.index')]
        );
        return view('pets.index', [
            'pets' => $lengthAwarePaginator,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->getCategories();
        $tags = $this->getTags();
        $statuses = $this->getStatuses();
        return view('pets.create', [
            'statuses' => $statuses,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    protected function getCategories(int|null $id = null)
    {
        $categories = [
            ['id' => 1, 'name' => 'cat1'],
            ['id' => 2, 'name' => 'cat2'],
            ['id' => 3, 'name' => 'cat3'],
        ];
        if ($id) {
            foreach ($categories as $category) {
                if ($id === $category['id']) {
                    $return = $category;
                    break;
                }
            }
        } else {
            $return = $categories;
        }
        return $return;
    }

    protected function getTags(array|null $ids = null)
    {
        $tags = [
            ['id' => 1, 'name' => 'tag1'],
            ['id' => 2, 'name' => 'tag2'],
            ['id' => 3, 'name' => 'tag3'],
        ];
        if ($ids) {
            foreach ($tags as $tag) {
                if (false !== array_search((string)$tag['id'], $ids)) {
                    $return[] = $tag;
                }
            }
        } else {
            $return = $tags;
        }
        return $return;
    }

    protected function getStatuses()
    {
        return ['available', 'pending', 'sold',];
    }

    protected function photoUrlsToArr(string $photoUrls)
    {
        return array_map('trim', explode(',', $photoUrls));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PetRequest $request)
    {
        $petArr = [];
        if ($request->category) {
            $petArr['category'] = $this->getCategories((int)$request->category);
        }
        $petArr['name'] = $request->name;
        if ($request->photoUrls) {
            $petArr['photoUrls'] = $this->photoUrlsToArr($request->photoUrls);
        }
        if ($request->tags) {
            $petArr['tags'] = $this->getTags($request->tags);
        }
        if ($request->status) {
            $petArr['status'] = $request->status;
        }
        $response = Http::post('https://petstore.swagger.io/v2/pet', $petArr);
        if ($response->failed()) {
            return redirect()->route('pets.create')
                ->withError('ERROR');
        }
        return redirect()->route('pets.index')
            ->withSuccess('New pet is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pet = Http::get("https://petstore.swagger.io/v2/pet/$id");
        return view('pets.edit', [
            'pet' => $pet->json()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PetRequest $request, string $id)
    {
        $petArr = [];
        if ($request->category) {
            $petArr['category'] = $this->getCategories((int)$request->category);
        }
        $petArr['name'] = $request->name;
        if ($request->photoUrls) {
            $petArr['photoUrls'] = $this->photoUrlsToArr($request->photoUrls);
        }
        if ($request->tags) {
            $petArr['tags'] = $this->getTags($request->tags);
        }
        if ($request->status) {
            $petArr['status'] = $request->status;
        }
        $response = Http::put('https://petstore.swagger.io/v2/pet', $petArr);
        if ($response->failed()) {
            return redirect()->route('pets.create')
                ->withError('ERROR');
        }
        return redirect()->route('pets.index')
            ->withSuccess('New pet is added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = Http::delete("https://petstore.swagger.io/v2/pet/$id");
        if ($response->failed()) {
            return redirect()->route('pets.create')
                ->withError('ERROR');
        }
        return redirect()->route('pets.index')
            ->withSuccess('Pet is deleted successfully.');
    }
}
