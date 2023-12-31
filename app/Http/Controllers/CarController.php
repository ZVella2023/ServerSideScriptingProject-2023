<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Manufacturer;


class CarController extends Controller
{
    public function index()
    {
        if (request('manufacturer_id') == null) {
            $cars = Car::all();
        } else {
            $cars = Car::where('manufacturer_id', request('manufacturer_id'))->get();
        }

        $manufacturers = Manufacturer::orderBy('name')->pluck('name', 'id')->prepend('All Manufacturers', '');
        return view('cars.index', compact('cars', 'manufacturers'));
    }

    public function create()
    {
        $car = new Car();
        $manufacturers = Manufacturer::orderBy('name')->pluck('name', 'id')->prepend('All Manufacturers', '');
        return view('cars.create', compact('manufacturers', 'car'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'model'=>'required',
            'year'=>'required',
            'salesperson_email'=>'required|email',
            'manufacturer_id'=>'required|exists:manufacturers,id'
        ]);

        Car::create($request->all());
        return redirect()->route('cars.index')->with('message', 'Car has been added successfully');
    }

    public function show($id)
    {
        $car = Car::find($id);
        return view('cars.show', compact('car'));
    }

    public function edit($id)
    {
        $car = Car::find($id);
        $manufacturers = Manufacturer::orderBy('name')->pluck('name', 'id')->prepend('All Manufacturers', '');
        return view('cars.edit', compact('manufacturers', 'car'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'model'=>'required',
            'year'=>'required',
            'salesperson_email'=>'required|email',
            'manufacturer_id'=>'required|exists:manufacturers,id'
        ]);

        $car = Car::find($id);
        $car->update($request->all());
        return redirect()->route('cars.index')->with('message', 'Car has been updated successfully');
    }

    public function destroy($id)
    {
        $car = Car::find($id);
        $car->delete();
        return back()->with('message', 'Car has been deleted successfully');
    }
}