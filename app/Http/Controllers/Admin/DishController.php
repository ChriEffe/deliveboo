<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Dish;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DishController extends Controller
{
    // public function myValidator(array $data)
    // {
    //     $parameters = [
    //         'name' => ['required', 'max:100'],
    //         'description' => ['nullable', 'max:2000'],
    //         'ingredients' => ['nullable'],
    //         'image' => ['nullable', 'image'],
    //         'price' => ['required', 'numeric', 'min:0.01', 'max:999'],
    //         'availability' => ['required'],
    //         'course' => ['required'],
    //     ];

    //     $messages = [
    //         'name.required' => 'Il nome è richiesto',
    //         'description.max' => 'La descrizione può contenere massimo :max caratteri',
    //         'price.required' => 'Il prezzo è richiesto',
    //         'price.numeric' => 'Il prezzo deve essere un numero',
    //         'price.min.max' => 'Il prezzo può essere di massimo :max euro',
    //         'availability.required' => 'La disponibilità è richiesta',
    //         'course.required' => 'Il tipo di piatto è richiesto',
    //     ];

    //     return Validator::make($data, $parameters, $messages);
    // }

    protected $validationParams = [
        'name' => 'required|max:100',
        'description' => 'nullable|max:2000',
        'ingredients' => 'nullable',
        'image' => 'nullable|image',
        'price' => 'required|numeric|min:0.01|max:999',
        'availability' => 'required',
        'course' => 'required',
    ];

    public $courses = [
        'Primo',
        'Secondo',
        'Pizza',
        'Frutta',
        'Dolce',
        'Bibita',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dishes.index', [
            'dishes' => Dish::where('user_id', Auth::user()->id)->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.dishes.create', ['courses' => $this->courses]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->validationParams);
        $data = $request->all();
        if (!empty($data['image'])) {
            $data['image'] = Storage::put('uploads', $data['image']);
        }

        $dish = new Dish();
        $dish->fill($data);
        $dish->user_id = Auth::user()->id;
        $dish->slug = $dish->createSlug($data['name']);
        $dish->save();

        return redirect()->route('admin.dishes.show', $dish->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Model\Dish
     * @return \Illuminate\Http\Response
     */
    public function show(Dish $dish)
    {
        return view('admin.dishes.show', compact('dish'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Model\Dish
     * @return \Illuminate\Http\Response
     */
    public function edit(Dish $dish)
    {
        $data = [
            'dish' => $dish,
            'courses' => $this->courses,
        ];

        return view('admin.dishes.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Model\Dish
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dish $dish)
    {
        $request->validate($this->validationParams);
        $data = $request->all();
        if (Auth::user()->id != $dish->user_id) {
            abort('403');
        }
        if ($data['name'] != $dish->name) {
            $dish->name = $data['name'];
        }
        if ($data['description'] != $dish->description) {
            $dish->description = $data['description'];
        }
        if ($data['ingredients'] != $dish->ingredients) {
            $dish->ingredients = $data['ingredients'];
        }
        if ($data['price'] != $dish->price) {
            $dish->price = $data['price'];
        }
        if ($data['availability'] != $dish->availability) {
            $dish->availability = $data['availability'];
        }
        if ($data['course'] != $dish->course) {
            $dish->course = $data['course'];
        }
        if (!empty($data['image'])) {
            Storage::delete($dish->image);
            $dish->image = Storage::put('uploads', $data['image']);
        }
        $dish->update();

        return redirect()->route('admin.dishes.show', $dish);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Model\Dish
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dish $dish)
    {
        // $dish->orders()->detach();
        $dish->delete();
        return redirect()
            ->route('admin.dishes.index')
            ->with('status', "Hai eliminato il piatto '$dish->name'");
    }
}
